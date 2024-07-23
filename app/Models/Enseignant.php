<?php

namespace App\Models;

use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;
use App\Models\Pivot\SeanceEnseignantPivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enseignant extends Model
{
    use HasFactory;
    use EagerLoadPivotTrait;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'source_name',
        'source_id',
        'user_id'
    ];

   /* public function seances()
    {
        return $this->belongsToMany(Seance::class, 'enseignant_seance', 'enseignant_id', 'seance_id');
    }
    */
    public function seances()
    {
        return $this->belongsToMany(Seance::class, 'enseignant_seance')
            ->using(SeanceEnseignantPivot::class)
            ->withPivot('emargement_id');

    }


    public function emargements()
    {
        return $this->morphTo(Emargement::class, 'objet');
    }

}
