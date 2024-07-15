<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeanceEnseignant extends Model
{
    use HasFactory;

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

