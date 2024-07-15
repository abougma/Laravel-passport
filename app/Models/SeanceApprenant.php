<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeanceApprenant extends Model
{
    use HasFactory;

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
