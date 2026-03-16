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
            'id'             => $this->id,
            'country'        => $this->country,
            'code'           => $this->code,
            'companyName'    => $this->company->name ?? 'No Company Assigned',
            'totalEmployees' => $this->total_staff ?? 0,
            'companyId'      => $this->company_id,
            'isActive'       => $this->estado,
        ];
    }
}
