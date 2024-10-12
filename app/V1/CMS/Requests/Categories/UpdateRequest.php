<?php

namespace App\V1\CMS\Requests\Categories;

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
        $rules = [
            'name'        => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($this->route('id'))
            ],
            'description' => 'nullable|max:255',
            'parent_id'   => 'nullable|exists:categories,id',
            'image'       => 'nullable|image|max:3145728|mimes:jpg,jpeg,png,bmp,gif,svg,webp,mp4,ogx,oga,ogv,ogg,webm',
            'is_active'   => 'required|boolean',
        ];

        if (filter_var($this->input('remove_image'), FILTER_VALIDATE_BOOLEAN)) {
            $rules['image'] = 'required|image|max:3145728|mimes:jpg,jpeg,png,bmp,gif,svg,webp,mp4,ogx,oga,ogv,ogg,webm';
        }

        return $rules;
    }

}
