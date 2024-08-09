<?php

namespace App\CMS\Resources;

use App\Supports\CMS;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'code'              => $this->code,
            'name'              => $this->name,
            'profile_photo_url' => CMS::getFile($this->profile_photo_path),
            'verify_code'       => $this->verify_code,
            'expired_code'      => $this->expired_code,
            'email'             => $this->email,
            'phone'             => $this->phone,
            'is_active'         => $this->is_active ? true : false,
            'is_super'          => $this->is_super,
        ];
    }
}
