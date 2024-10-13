<?php

namespace App\V1\CMS\Requests\Units;

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
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('units', 'name')->ignore($this->route('id'))
            ],
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('units', 'code')->ignore($this->route('id'))
            ],

            'is_active' => 'required|in:1,0,true,false',
        ];
    }

}
