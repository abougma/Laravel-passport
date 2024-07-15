<?php

namespace App\Http\Resources;

use App\Models\Enseignant;
use Illuminate\Http\Resources\Json\JsonResource;

class EmargementResource extends JsonResource
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
            'seance_id' => $this->seance_id,
            'objet_type' => $this->objet_type,
            'objet_id' => $this->objet_id
        ];
    }
}
