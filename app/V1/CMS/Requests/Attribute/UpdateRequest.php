<?php

namespace App\V1\CMS\Requests\Users;

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
            'name'      => [
                'required',
                'string',
                'max:255',
                Rule::unique('attributes', 'name')->ignore($this->route('id'))
            ],
            'group_id' => 'required|email|exists:attribute_groups,id',
        ];
    }

}
