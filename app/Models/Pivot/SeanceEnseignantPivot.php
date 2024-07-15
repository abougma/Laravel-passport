<?php

namespace App\Models\Pivot;

use App\Models\Emargement;
use App\Models\Enseignant;
use App\Models\Seance;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SeanceEnseignantPivot extends Pivot
{

    public function seance()
    {
        return $this->belongsTo(Seance::class);
    }

    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class);
    }

    public function emargement()
    {
        return $this->belongsTo(Emargement::class);
    }
}
