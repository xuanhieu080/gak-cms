<?php

namespace App\V1\CMS\Requests\Products;

use App\V1\CMS\Requests\ValidatorBase;
use Illuminate\Validation\Rule;

class UpdateRequest extends ValidatorBase
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'        => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'name')->ignore($this->route('id'))
            ],
            'sku'        => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'sku')->ignore($this->route('id'))
            ],
            'image'       => 'nullable|image|max:3145728|mimes:jpg,jpeg,png,bmp,gif,svg,webp,mp4,ogx,oga,ogv,ogg,webm',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0|max:999999999999',
            'price_sale'  => 'nullable|numeric|between:0,99999999999|lt:price',
            'unit_id'     => 'required|exists:units,id',
            'is_active'   => 'required|in:1,0,true,false',
        ];
    }

}
