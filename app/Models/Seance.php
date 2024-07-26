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
        'source_name',
        'source_id',
        'code'
    ];

    protected $casts = [
        'dabte_debut' => "datetime:Y-m-d",
        'date_fin' => 'datetime: Y-m-d'
    ];
    public function enseignant()
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
