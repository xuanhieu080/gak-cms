<?php

namespace App\V1\CMS\Requests\Users;

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
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->route('user')->id)
            ],
            //            'roles' => 'required|array|exists:roles,name',
            'avatar'     => 'nullable|image',
            'password'   => 'nullable|min:6'
        ];
    }

}
