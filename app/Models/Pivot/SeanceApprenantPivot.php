<?php

namespace App\Models\Pivot;

use App\Models\Apprenant;
use App\Models\Emargement;
use App\Models\Seance;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SeanceApprenantPivot extends Pivot
{
    public function seance()
    {
        return $this->belongsTo(Seance::class);
    }

    public function apprenant()
    {
        return $this->belongsTo(Apprenant::class);
    }

    public function emargement()
    {
        return $this->belongsTo(Emargement::class);
    }
}
