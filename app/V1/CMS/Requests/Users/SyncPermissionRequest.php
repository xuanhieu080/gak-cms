<?php

namespace App\V1\CMS\Requests\Users;

use App\V1\CMS\Requests\ValidatorBase;

class SyncPermissionRequest extends ValidatorBase
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'permissions'   => 'nullable|array',
            'permissions.*' => 'required|exists:permissions,name'
        ];
    }
}
