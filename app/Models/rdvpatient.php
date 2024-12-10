<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rdvpatient extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'motif',
        'statut',
        'patient_id',
        'codemedecin',
        'tel',
    ];
}
