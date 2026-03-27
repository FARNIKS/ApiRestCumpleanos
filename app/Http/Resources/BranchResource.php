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
            // El identificador real es el código (CEON, ATI, etc.)
            'code'        => $this->code,
            'companyName' => $this->company->name ?? 'N/A',
            'countryName' => $this->country->name ?? 'N/A',
            'isActive'    => (bool) $this->estado,

        ];
    }
}
