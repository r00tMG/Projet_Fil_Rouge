<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Admin\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnnonceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'kilos_disponibles' => $this->resource->kilos_disponibles,
            'date_depart' => $this->resource->date_depart,
            'date_arrivee' => $this->resource->date_arrivee,
            'description' => $this->resource->description,
            'origin' => $this->resource->origin,
            'destination' => $this->resource->destination,
            'user' => new UserResource(
                $this->resource->user
            )
        ];
    }
}
