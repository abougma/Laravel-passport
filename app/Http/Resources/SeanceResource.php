<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SeanceResource extends JsonResource
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
            'intitule' => $this->intitule,
            'matiere_id' => $this->matiere_id,
            'date_debut' => $this->dabte_debut,
            'date_fin' => $this->date_fin,
            'duration' => $this->duration,
            'seance_id_externe' => $this->seance_id_externe,
            'source_name' => $this->source_name,
            'source_id' => $this->source_id
            //'apprenants' => ApprenantResource::collection($this->apprenants),
            //'enseignant' => EnseignantResource::collection($this->enseignant),
        ];
    }
}
