<?php

namespace App\V1\CMS\Requests\Products;

use App\V1\CMS\Requests\ValidatorBase;

class CreateRequest extends ValidatorBase
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'        => 'required|unique:products,name|string|max:255',
            'image'       => 'nullable|image|max:3145728|mimes:jpg,jpeg,png,bmp,gif,svg,webp,mp4,ogx,oga,ogv,ogg,webm',
            'price'       => 'required|numeric|between:0,99999999999',
            'price_sale'  => 'nullable|numeric|between:0,99999999999|lt:price',
            'qty'         => 'required|numeric|between:0,99999999999',
            'sku'         => 'required|string|unique:products,sku',
            'unit_id'     => 'required|exists:units,id',
            'description' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'is_active'   => 'required|in:1,0,true,false',
        ];
    }
}
