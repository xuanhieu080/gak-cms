<?php

namespace App\V1\CMS\Requests\Customers;

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
        $rules = [
            'name'      => 'required|string|max:255',
            'image'     => 'nullable|image|max:3145728|mimes:jpg,jpeg,png,bmp,gif,svg,webp,mp4,ogx,oga,ogv,ogg,webm',
            'email'     => 'nullable|max:255|email',
            'note'      => 'nullable|max:500',
            'address'   => 'nullable|max:500',
            'discount'  => 'nullable|numeric|between:0,100',
            'is_active' => 'required|in:1,0,true,false',
            'phone'     => ['required', 'regex:/^(?:\+84|0)(3[2-9]|5[2|6|8|9]|7[0|6-9]|8[1-6]|9[0-9])[0-9]{7}$/'],
        ];

        return $rules;
    }

}
