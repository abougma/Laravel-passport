<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Antenne extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'nom',
        'etablissement_id'
    ];
    public function etablissement(): HasOne
    {
        return $this->hasOne(Etablissement::class, 'id', 'etablissement_id');
    }
    public function formations(): HasMany
    {
        return $this->hasMany(Formation::class, 'antenne_id','id');
    }

    public function salles(): HasMany
    {
        return $this->hasMany(Salle::class, 'antenne_id', 'id');
    }

    public function apprenants(): HasMany
    {
        return $this->hasMany(Apprenant::class, 'antenne_id', 'id');
    }
}
