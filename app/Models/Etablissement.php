<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Etablissement extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'nom',
    ];

    public function user() : MorphMany
    {
        return $this->morphMany(User::class, 'objet', 'user_permisson');
    }

    public function antennes(): HasMany
    {
        return $this->hasMany(Antenne::class);
    }
}
