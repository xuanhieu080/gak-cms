<?php

namespace App\V1\CMS\Requests\Products;

use App\V1\CMS\Requests\ValidatorBase;

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
            'name'             => 'required|string|max:255|unique:products,name',
            'code'             => 'required|string|max:255|unique:products,name',
            'image'            => 'required|image|max:3145728|mimes:jpg,jpeg,png,bmp,gif,svg,webp,mp4,ogx,oga,ogv,ogg,webm',
            'description'      => 'required|string',
            'category_id'      => 'required|exists:categories,id',
            'price'            => 'nullable|numeric|min:0|max:999999999999',
            'discount'         => [
                'nullable',
                'max:1000000000000',
                'min:0',
                'numeric',
                function ($attribute, $value, $fail) {
                    if ($value > $this->price) {
                        return $fail('Tiền giảm giá không được vượt quá giá tiền gốc');
                    }
                }
            ],
        ];
    }

}
