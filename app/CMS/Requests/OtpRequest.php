<?php

namespace App\CMS\Requests;

use App\Models\User;

class OtpRequest extends ValidatorBase
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'    => 'required|email|exists:users,email',
            'otp'    => [
                'required',
                function ($attribute, $value, $fail) {
                    $user = User::whereEmail($this->input('email'))->first();
                    $startDate = strtotime($user->expired_code);
                    $endDate = time();
                    if (empty($user) ) {
                        return $fail('Dữ liệu không khớp');
                    } elseif ($user->verify_code != $value) {
                        return $fail('OTP không đúng');
                    } elseif ($startDate < $endDate) {
                        return $fail('OTP đã hết hạn');
                    }
                }
            ],
        ];
    }
}