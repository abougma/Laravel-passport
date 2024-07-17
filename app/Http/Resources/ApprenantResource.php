<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApprenantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'ine' => $this->ine,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'source_name' => $this->source_name,
            'source_id' => $this->source_id
        ];
    }
}
