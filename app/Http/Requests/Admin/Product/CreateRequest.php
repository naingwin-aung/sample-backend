<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules() : array
    {
        return [
            'name'                                     => 'required|string|max:255',
            'on_board_piers'                           => 'required',
            'description'                              => 'required|string',
            'images'                                   => 'required|array',
            'images.*'                                 => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'boats'                                    => 'required|array',
            'boats.*.boat'                             => 'required',
            'boats.*.boat.id'                          => 'required|integer',
            'boats.*.start_time'                       => 'required',
            'boats.*.end_time'                         => 'required',
            'boats.*.start_date'                       => 'required|date',
            'boats.*.end_date'                         => 'required|date|after_or_equal:boats.*.start_date',
            'boats.*.closing_type'                     => 'required',
            'boats.*.closing_dates'                    => 'nullable|array',
            'boats.*.closing_days'                     => 'nullable|array',
            'boats.*.tickets'                          => 'required|array',
            'boats.*.tickets.*.name'                   => 'required|string|max:255',
            'boats.*.tickets.*.short_description'      => 'nullable|string',
            'boats.*.tickets.*.prices'                 => 'required|array',
            'boats.*.tickets.*.prices.*.name'          => 'required|string|max:255',
            'boats.*.tickets.*.prices.*.selling_price' => 'required|numeric',
            'boats.*.tickets.*.prices.*.net_price'     => 'required|numeric',
        ];
    }
}
