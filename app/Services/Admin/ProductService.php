<?php
namespace App\Services\Admin;

use App\Models\Product;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use Illuminate\Http\Request;

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
}