<?php

namespace App\Models;

use App\Http\Controllers\Api\SeanceController;
use App\Models\Pivot\SeanceApprenantPivot;
use App\Models\Pivot\SeanceEnseignantPivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apprenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'ine',
        'apprenant_id_externe',
        'prenom',
        'email',
        'source_name',
        'source_id',
        'user_id'
    ];

    public function seances()
    {
        return $this->belongsToMany(Seance::class, 'apprenant_seance', 'apprenant_seance.apprenant_id', 'apprenant_seance.seance_id');
    }

    public function emargements()
    {
       return $this->morphMany(Emargement::class, 'objet');
    }

}
