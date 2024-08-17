<?php

namespace App\V1\CMS\Requests;

class UploadImageRequest extends ValidatorBase
{
    public function rules()
    {
        return [
            'image' => 'required|image|max:3145728|mimes:jpg,jpeg,png,bmp,gif,svg,webp,mp4,ogx,oga,ogv,ogg,webm',
        ];
    }
}
