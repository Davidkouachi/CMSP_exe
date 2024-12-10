<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class programmemedecin extends Model
{
    use HasFactory;

    protected $fillable = [
        'periode',
        'heure_debut',
        'heure_fin',
        'statut',
        'codemedecin',
        'jour_id',
    ];
}
