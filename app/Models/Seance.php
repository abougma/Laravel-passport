<?php

namespace App\Models;

use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seance extends Model
{
    use EagerLoadPivotTrait;

    use HasFactory;

    protected $fillable = [
        'intitule',
        'matiere_id',
        'dabte_debut',
        'date_fin',
        'duration',
        'seance_id_externe',
        'source_name',
        'source_id'
    ];

    public function enseignants()
    {
        return $this->belongsToMany(Enseignant::class);
    }

    public function apprenants()
    {
        return $this->belongsToMany(Apprenant::class);
    }

    public function emargement()
    {
        return $this->hasMany(Emargement::class);
    }

}
