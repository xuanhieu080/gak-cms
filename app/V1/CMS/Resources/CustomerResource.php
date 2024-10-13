<?php

namespace App\V1\CMS\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 * @package App\Http\Resources
 */
class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            "id"        => $this->id,
            "name"      => $this->name,
            "image"     => $this->getFirstMediaUrl(),
            "code"      => $this->code,
            "is_active" => $this->is_active,
            "email"     => $this->email,
            "note"      => $this->note,
            "address"   => $this->address,
            "phone"     => $this->phone,
            "discount"  => $this->discount,
        ];
    }
}
