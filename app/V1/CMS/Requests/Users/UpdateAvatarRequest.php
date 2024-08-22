<?php

namespace App\V1\CMS\Requests\Users;

use App\Supports\Support;
use App\V1\CMS\Requests\ValidatorBase;
use Illuminate\Validation\Rule;

class UpdateAvatarRequest extends ValidatorBase
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'image'     => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,bmp,gif,svg,webp,mp4,ogx,oga,ogv,ogg,webm',
                'max:10000',
                'mimetypes:' . Support::allImageMimeTypeString()],
        ];
    }

}
