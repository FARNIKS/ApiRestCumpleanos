<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentResource extends JsonResource
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
            'departmentName' => $this->department?->name ?? 'N/A',
            'positionName'   => $this->position?->name ?? 'N/A',
            // CORRECCIÓN: Nombres en singular
            'departmentId'   => $this->department_id,
            'positionId'     => $this->position_id,
            'isActive'       => $this->estado,
        ];
    }
}
