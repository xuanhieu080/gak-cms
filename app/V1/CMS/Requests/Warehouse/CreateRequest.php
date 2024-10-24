<?php

namespace App\V1\CMS\Requests\Warehouse;

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
            'name'       => 'required|string|max:255|unique:warehouses,name',
            'code'       => 'required|string|max:20|unique:warehouses,code',
            'address'    => 'nullable|string|max:400',
            'phone'      => 'nullable|string|max:255',
            'email'      => 'nullable|string|email|max:255',
            'tax_code'   => 'nullable|string|min:5|max:50',
            'manager_id' => 'required|exists:users,id',
        ];
    }
}
