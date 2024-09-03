<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Formation extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'nom',
        'antenne_id'
    ];

    public function antenne(): BelongsTo
    {
        return $this->belongsTo(Antenne::class);
    }

    public function parcours(): HasMany
    {
        return $this->hasMany(Parcour::class);
    }
}
