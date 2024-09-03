<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Parcour extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'nom',
        'formation_id'
    ];

    public function formation(): BelongsTo
    {
        return $this->belongsTo(Formation::class);
    }

    public function classes(): HasMany
    {
        return $this->hasMany(Classe::class);
    }
}
