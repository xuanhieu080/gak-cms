<?php

namespace App\V1\CMS\Resources\Warehouses;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 * @package App\Http\Resources
 */
class WarehouseShortResource extends JsonResource
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
            "id"              => $this->id,
            "name"            => $this->name,
            "code"            => $this->code,
            "address"         => $this->address,
            "phone"           => $this->phone,
            "email"           => $this->email,
            "tax_code"        => $this->tax_code,
            "manager_id"      => $this->manager_id,
        ];
    }
}
