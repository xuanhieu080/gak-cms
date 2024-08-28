<?php

namespace App\V1\CMS\Requests\Warehouse;

use App\Supports\Support;
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
            'name'       => [
                'required',
                'string',
                'max:255',
                Rule::unique('warehouses', 'name')->ignore($this->route('id'))
            ],
            'code'       => [
                'required',
                'string',
                'max:20',
                Rule::unique('warehouses', 'code')->ignore($this->route('id'))
            ],
            'address'    => 'nullable|string|max:400',
            'phone'      => 'nullable|string|max:255',
            'email'      => 'nullable|string|email|max:255',
            'manager_id' => 'required|exists:users,id',
        ];
    }

}
