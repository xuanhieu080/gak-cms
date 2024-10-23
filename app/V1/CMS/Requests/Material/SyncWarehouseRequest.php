<?php

namespace App\V1\CMS\Requests\Material;

use App\V1\CMS\Requests\ValidatorBase;

class SyncWarehouseRequest extends ValidatorBase
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'items'                => 'required|array',
            'items.*'              => 'required|array',
            'items.*.warehouse_id' => 'required|exists:warehouses,id',
            'items.*.qty'          => 'required|numeric|between:0,99999999999',
        ];
    }
}