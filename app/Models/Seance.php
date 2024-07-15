<?php

namespace App\Models;

<<<<<<< HEAD
use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;
=======
>>>>>>> 491d98493b85d953d9f9ccb5fab06146e34ef305
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seance extends Model
{
<<<<<<< HEAD
    use EagerLoadPivotTrait;

=======
>>>>>>> 491d98493b85d953d9f9ccb5fab06146e34ef305
    use HasFactory;

    protected $fillable = [
        'intitule',
<<<<<<< HEAD
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

=======
        'dabte_debut',
        'date_fin'
    ];
>>>>>>> 491d98493b85d953d9f9ccb5fab06146e34ef305
}
