<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SeanceDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'intitule' => $this->intitule,
            'matiere_id' => $this->matiere_id,
            'date_debut' => $this->date_debut,
            'date_fin' => $this->date_fin,
            'apprenants' => ApprenantResource::collection($this->apprenants),
            'enseignant' => EnseignantResource::collection($this->enseignant),

        ];
    }
}
