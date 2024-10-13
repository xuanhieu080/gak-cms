<?php

namespace App\V1\CMS\Requests\Units;

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
            'name'      => 'required|string|max:255|unique:units,name',
            'code'      => 'required|string|max:255|unique:units,code',
            'is_active' => 'required|in:1,0,true,false',
        ];
    }
}
