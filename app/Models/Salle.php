<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Salle extends Model
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

    public function seances(): HasMany
    {
        return $this->hasMany(Seance::class);
    }
}
