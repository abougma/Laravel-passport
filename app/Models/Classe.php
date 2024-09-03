<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'nom',
        'parcour_id'
    ];

    public function parcours(): BelongsTo
    {
        return $this->belongsTo(Parcours::class);
    }
}
