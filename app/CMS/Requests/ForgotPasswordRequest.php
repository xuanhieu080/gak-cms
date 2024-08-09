<?php

namespace App\CMS\Requests;

class ForgotPasswordRequest extends ValidatorBase
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'    => 'required|email|exists:users,email|max:255',
        ];
    }
}