<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name'     => $this->Nombre,
            'birthday' => $this->Cumple ? $this->Cumple->format('Y-m-d') : null,
            'company'  => $this->branch->company->name ?? 'N/A',
            'country'  => $this->branch->country->name ?? 'N/A',
            'branch_code' => $this->Empresa,
        ];
    }
}
