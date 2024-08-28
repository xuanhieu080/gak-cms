<?php

namespace App\V1\CMS\Requests\AttributeGroup;

use App\Supports\Support;
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
            'name'     => 'required|string|max:255|unique:attribute_groups,name',
        ];
    }
}
