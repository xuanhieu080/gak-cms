<?php

namespace App\V1\CMS\Requests\Categories;

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
            'name'        => 'required|string|max:255|unique:categories,name',
            'image'       => 'required|image|max:3145728|mimes:jpg,jpeg,png,bmp,gif,svg,webp,mp4,ogx,oga,ogv,ogg,webm',
            'parent_id'   => 'nullable|exists:categories,id',
            'description' => 'nullable|max:255',
            'is_active'   => 'required|in:1,0,true,false',
        ];
    }
}
