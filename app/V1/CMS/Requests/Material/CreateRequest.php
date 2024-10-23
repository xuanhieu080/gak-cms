<?php

namespace App\V1\CMS\Requests\Material;

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
            'name'         => 'required|string|max:255|unique:materials,name',
            'code'         => 'required|string|max:20|unique:materials,code'
        ];
    }
}
