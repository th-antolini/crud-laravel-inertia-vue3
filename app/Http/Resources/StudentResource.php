<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $date = new Carbon("1990-01-01 00:00:00");
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'class' => ClassesResource::make($this->whenLoaded('class')),
            'section' => SectionResource::make($this->whenLoaded('section')),
            'created_at' => $this->created_at ? $this->created_at->toFormattedDateString() : $date->toFormattedDateString(),
        ];
    }
}
