<?php

namespace App\V1\CMS\Requests\Products\Variants;

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
            'image'      => 'nullable|image|max:3145728|mimes:jpg,jpeg,png,bmp,gif,svg,webp,mp4,ogx,oga,ogv,ogg,webm',
            'price'      => 'required|numeric|between:0,99999999999',
            'price_sale' => 'nullable|numeric|between:0,99999999999|lt:price',
            'sku'        => [
                'required',
                'string',
                Rule::unique('variants', 'sku')->ignore($this->route('id'))
            ],
            'id'         => 'required|string|unique:products,sku',
        ];
    }
}
