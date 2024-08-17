<?php

namespace App\V1\CMS\Requests\Users;

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
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
//            'roles' => 'required|array|exists:roles,name',
            'avatar' => $this->request->has('avatar') ? 'nullable' : 'image',
            'password' => 'required|min:6',
        ];
    }
}
