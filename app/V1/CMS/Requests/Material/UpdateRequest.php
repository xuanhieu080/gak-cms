<?php

namespace App\V1\CMS\Requests\Material;

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
                Rule::unique('materials', 'name')->ignore($this->route('id'))
            ],
            'code'       => [
                'required',
                'string',
                'max:20',
                Rule::unique('materials', 'code')->ignore($this->route('id'))
            ],
            'warehouse_id' => 'required|exists:warehouses,id',
        ];
    }

}
