<?php

namespace App\V1\CMS\Requests\Users;

use App\V1\CMS\Requests\ValidatorBase;

class UpdateAvatarRequest extends ValidatorBase
{
    public function rules()
    {
        return [
            'avatar' => 'required|image',
        ];
    }
}
