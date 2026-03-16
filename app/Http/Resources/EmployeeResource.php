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
            'id'        => $this->id,
            'fullName'  => $this->name,
            'birthday'  => $this->birthday?->format('Y-m-d'),
            'isActive'  => $this->estado,

            'assignment' => $this->when($this->assignment, [
                'position'   => $this->assignment?->position?->name ?? 'No Position',
                'department' => $this->assignment?->department?->name ?? 'General',
            ]),

            'branch' => $this->when($this->branch, [
                'code'    => $this->branch?->code,
                'country' => $this->branch?->country,
                'company' => $this->branch?->company?->name ?? 'No Company',
            ]),
        ];
    }
}
