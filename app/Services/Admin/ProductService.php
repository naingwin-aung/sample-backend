<?php
namespace App\Services\Admin;

use Exception;
use App\Models\Product;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public $imageArray = [];

    public function listing(Request $request)
    {
        $query = Product::query();

        if (isset($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query
            ->orderBy('id', 'desc')
            ->paginate($request->limit ?? 10);

        return $products;
    }

    public function create(Request $request)
    {
        $product = Product::create([
            'name'        => $request->name,
            'description' => $request->description,
        ]);

        $product->slug = $product->id . '-' . Str::slug($product->name);
        $product->update();

        // handle onboard pier
        if (isset($request->on_board_piers) && is_array($request->on_board_piers)) {
            $pierIds = array_map(function ($pier) {
                return $pier['id'];
            }, $request->on_board_piers);

            $product->piers()->sync($pierIds);
        }

        // Handle images
        if (!empty($request->images) && count($request->images) > 0) {
            if ($request->file('images')) {
                $this->_createImages($product, $request->file('images'));
            }
        }

        if (count($this->imageArray) > 0) {
            ProductImage::insert($this->imageArray);
        }

        // handle options
        if (isset($request->boats) && is_array($request->boats)) {
            foreach ($request->boats as $boatData) {
                $this->_createProductOption($product, $boatData);
            }
        }

        return $product;
    }

    public function getById($id)
    {
        $product = Product::with([
            'images',
            'piers',
            'options.boat',
            'options.productAdditionalOptions.additionalOption',
            'options.tickets.prices',
        ])->findOrFail($id);

        return $product;
    }

    public function update(Request $request, $id)
    {
        $product = $this->getById($id);

        $product->update([
            'name'        => $request->name,
            'slug'        => $product->id . '-' . Str::slug($request->name),
            'description' => $request->description,
        ]);

        // handle onboard piers
        if (isset($request->on_board_piers) && is_array($request->on_board_piers)) {
            $pierIds = array_map(function ($pier) {
                return $pier['id'];
            }, $request->on_board_piers);

            $product->piers()->sync($pierIds);
        }

        // start handle product images
        if (!empty($request->images)) {
            if ($product->images->count() <= 1 && empty($request->images)) {
                throw new Exception('At least one image is required for the product.');
            }

            $files = $product->images()
                ->whereIn('id', $request->old_images ?? [])
                ->get();

            if (count($files) > 0) {
                foreach ($files as $file) {
                    $oldImage = $file->getRawOriginal('url') ?? '';
                    Storage::delete($oldImage);
                }

                $product->images()->whereIn('id', $request->old_images)->delete();
            }
        }

        if (!empty($request->images) && count($request->images) > 0) {
            if ($request->file('images')) {
                $this->_createImages($product, $request->file('images'));
            }
        }

        if (count($this->imageArray) > 0) {
            ProductImage::insert($this->imageArray);
        }

        $this->_updateOptions($request, $product);

        return $product;
    }

    public function delete($id)
    {
        $product = $this->getById($id);
        $product->delete();
    }

    private function _createImages(Product $product, $files)
    {
        foreach ($files as $image) {
            $this->imageArray[] = [
                'product_id' => $product->id,
                'url'        => $image->store('products'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
    }

    private function _createProductOption(Product $product, $boatData)
    {
        $option = $product->options()->create([
            'boat_id'       => $boatData['boat']['id'],
            'start_time'    => $boatData['start_time'],
            'end_time'      => $boatData['end_time'],
            'start_date'    => $boatData['start_date'],
            'end_date'      => $boatData['end_date'],
            'closing_type'  => isset($boatData['closing_type']) ? $boatData['closing_type'] : null,
            'closing_dates' => isset($boatData['closing_type']) && $boatData['closing_type'] != 'closing_day' ? $boatData['closing_dates'] ?? [] : [],
            'closing_days'  => isset($boatData['closing_type']) && $boatData['closing_type'] == 'closing_day' ? $boatData['closing_days'] ?? [] : [],
        ]);

        // handel additional options
        if (isset($boatData['additional_options']) && is_array($boatData['additional_options'])) {
            foreach ($boatData['additional_options'] as $additionalOptionData) {
                if (!isset($additionalOptionData['option']['id'])) {
                    continue;
                }

                $option->productAdditionalOptions()->create([
                    'product_id'           => $product->id,
                    'additional_option_id' => isset($additionalOptionData['option']['id']) ? $additionalOptionData['option']['id'] : null,
                    'selling_price'        => $additionalOptionData['selling_price'],
                    'net_price'            => $additionalOptionData['net_price'],
                ]);
            }
        }

        // handle tickets
        if (isset($boatData['tickets']) && is_array($boatData['tickets'])) {
            foreach ($boatData['tickets'] as $ticketData) {
                $ticket = $option->tickets()->create([
                    'product_id'        => $product->id,
                    'name'              => $ticketData['name'],
                    'short_description' => $ticketData['short_description'] ?? null,
                ]);

                // handle prices
                if (isset($ticketData['prices']) && is_array($ticketData['prices'])) {
                    foreach ($ticketData['prices'] as $priceData) {
                        $ticket->prices()->create([
                            'product_id'    => $product->id,
                            'option_id'     => $option->id,
                            'name'          => $priceData['name'],
                            'selling_price' => $priceData['selling_price'],
                            'net_price'     => $priceData['net_price'],
                        ]);
                    }
                }
            }
        }
    }

    private function _updateOptions(Request $request, Product $product)
    {
        $currentOptionIds = collect($request->boats)->pluck('id')->filter();
        if ($currentOptionIds->isNotEmpty()) {
            $product->options()->whereNotIn('id', $currentOptionIds)->delete();
        } else {
            $product->options()->delete();
        }

        foreach ($request->boats as $boatData) {
            $currentBoat = null;

            if ($boatData['id'] && is_numeric($boatData['id'])) {
                $currentBoat = $product->options()->where('id', $boatData['id'])->first();

                if ($currentBoat) {
                    $currentBoat->update([
                        'boat_id'       => $boatData['boat']['id'],
                        'start_time'    => $boatData['start_time'],
                        'end_time'      => $boatData['end_time'],
                        'start_date'    => $boatData['start_date'],
                        'end_date'      => $boatData['end_date'],
                        'closing_type'  => isset($boatData['closing_type']) ? $boatData['closing_type'] : null,
                        'closing_dates' => isset($boatData['closing_type']) && $boatData['closing_type'] != 'closing_day' ? $boatData['closing_dates'] ?? [] : [],
                        'closing_days'  => isset($boatData['closing_type']) && $boatData['closing_type'] == 'closing_day' ? $boatData['closing_days'] ?? [] : [],
                    ]);

                    // handel additional options
                    $this->_updateAdditionalOptions($boatData, $currentBoat, $product);

                    // handle update tickets
                    $this->_updateTickets($boatData, $currentBoat, $product);
                }
            } else {
                $this->_createProductOption($product, $boatData);
            }
        }
    }

    private function _updateAdditionalOptions($boatData, $option, $product)
    {
        $currentAdditionalOptionIds = isset($boatData['additional_options']) ? collect($boatData['additional_options'])->pluck('id')->filter() : collect();
        if ($currentAdditionalOptionIds->isNotEmpty()) {
            $option->productAdditionalOptions()->whereNotIn('id', $currentAdditionalOptionIds)->delete();
        } else {
            $option->productAdditionalOptions()->delete();
        }

        if(!isset($boatData['additional_options'])) {
            return;
        }
        
        foreach ($boatData['additional_options'] as $additionalOptionData) {
            $currentAdditionalOption = null;

            if ($additionalOptionData['id'] && is_numeric($additionalOptionData['id'])) {
                $currentAdditionalOption = $option->productAdditionalOptions()->where('id', $additionalOptionData['id'])->first();

                if ($currentAdditionalOption) {
                    $currentAdditionalOption->update([
                        'additional_option_id' => isset($additionalOptionData['option']['id']) ? $additionalOptionData['option']['id'] : null,
                        'selling_price'        => $additionalOptionData['selling_price'],
                        'net_price'            => $additionalOptionData['net_price'],
                    ]);
                }
            } else {
                $option->productAdditionalOptions()->create([
                    'product_id'           => $product->id,
                    'additional_option_id' => isset($additionalOptionData['option']['id']) ? $additionalOptionData['option']['id'] : null,
                    'selling_price'        => $additionalOptionData['selling_price'],
                    'net_price'            => $additionalOptionData['net_price'],
                ]);
            }
        }
    }

    public function _updateTickets($boatData, $option, $product)
    {
        $currentTicketIds = collect($boatData['tickets'])->pluck('id')->filter();
        if($currentTicketIds->isNotEmpty()) {
            $option->tickets()->whereNotIn('id', $currentTicketIds)->delete();
        } else {
            $option->tickets()->delete();
        }

        foreach ($boatData['tickets'] as $ticketData) {
            $currentTicket = null;

            if ($ticketData['id'] && is_numeric($ticketData['id'])) {
                $currentTicket = $option->tickets()->where('id', $ticketData['id'])->first();

                if ($currentTicket) {
                    $currentTicket->update([
                        'name'              => $ticketData['name'],
                        'short_description' => $ticketData['short_description'] ?? null,
                    ]);

                    $currentPriceIds = collect($ticketData['prices'])->pluck('id')->filter();
                    if($currentPriceIds->isNotEmpty()) {
                        $currentTicket->prices()->whereNotIn('id', $currentPriceIds)->delete();
                    } else {
                        $currentTicket->prices()->delete();
                    }

                    // handle update prices
                    foreach ($ticketData['prices'] as $priceData) {
                        $currentPrice = null;

                        if ($priceData['id'] && is_numeric($priceData['id'])) {
                            $currentPrice = $currentTicket->prices()->where('id', $priceData['id'])->first();

                            if ($currentPrice) {
                                $currentPrice->update([
                                    'name'          => $priceData['name'],
                                    'selling_price' => $priceData['selling_price'],
                                    'net_price'     => $priceData['net_price'],
                                ]);
                            }
                        } else {
                            $currentTicket->prices()->create([
                                'product_id'    => $product->id,
                                'option_id'     => $option->id,
                                'name'          => $priceData['name'],
                                'selling_price' => $priceData['selling_price'],
                                'net_price'     => $priceData['net_price'],
                            ]);
                        }
                    }
                }
            } else {
                // create new ticket
                $ticket = $option->tickets()->create([
                    'product_id'        => $product->id,
                    'name'              => $ticketData['name'],
                    'short_description' => $ticketData['short_description'] ?? null,
                ]);

                // handle prices
                if (isset($ticketData['prices']) && is_array($ticketData['prices'])) {
                    foreach ($ticketData['prices'] as $priceData) {
                        $ticket->prices()->create([
                            'product_id'    => $product->id,
                            'option_id'     => $option->id,
                            'name'          => $priceData['name'],
                            'selling_price' => $priceData['selling_price'],
                            'net_price'     => $priceData['net_price'],
                        ]);
                    }
                }
            }
        }
    }
}