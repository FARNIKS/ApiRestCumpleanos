<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BirthdayConfigResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'banner_url'   => $this->banner_url,
            'intro_text'   => $this->intro_text,
            'main_body'    => $this->main_body,
            'closing_text' => $this->closing_text,
            'sign_off'     => $this->sign_off,
            'updated_at'   => $this->updated_at->format('d/m/Y H:i'),
        ];
    }
}
