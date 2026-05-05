<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'Nombre'  => $this->Nombre,
            'company' => $this->branch?->company_name ?? 'Sin Empresa',
            'country' => $this->branch?->country?->name ?? 'Sin País',
            'Empresa' => $this->Empresa,
            'Cumple'  => $this->Cumple?->format('Y-m-d'),
        ];
    }
}
