<?php

namespace App\CMS\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileUpdateRequest extends ValidatorBase
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'    => [
                'required',
                'max:255',
                'email',
                Rule::unique('users')->ignore(Auth::id()),
            ],
            'phone'    => [
                'nullable',
                'regex:/^([0-9\s\-\+\(\)]*)$/',
                'min:10',
                'max:20',
                Rule::unique('users')->ignore(Auth::id()),
            ],
            'password' => [
                'nullable',
                'max:50',
                Password::min(8)
            ],
            'name'     => 'required|max:100',
            'image'    => ['nullable', 'image', 'max:5120'],
        ];
    }
}
