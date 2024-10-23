<?php

namespace App\V1\CMS\Requests\Products;

use App\V1\CMS\Requests\ValidatorBase;

class SyncAttributeRequest extends ValidatorBase
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'groups'   => 'nullable|array',
            'groups.*' => 'nullable|exists:attribute_groups,id'
        ];
    }
}
