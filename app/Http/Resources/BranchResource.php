<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'code'        => $this->code,
            'company_name'     => $this->company_name, // Directo de la tabla branches
            'country_name' => $this->country->name ?? 'N/A',
            'isActive'    => (bool) $this->estado,
        ];
    }
}
