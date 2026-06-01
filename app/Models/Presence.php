<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    protected $primaryKey = 'idPresence';
    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
    ];

    public const STATUTS = [
        'present' => 'Présent',
        'absent' => 'Absent',
        'retard' => 'Retard',
        'justifie' => 'Absence justifiée',
    ];

    public function eleve()
    {
        return $this->belongsTo(Eleve::class, 'matricule', 'matricule');
    }

    public function cours()
    {
        return $this->belongsTo(Cours::class, 'idCours', 'idCours');
    }

    public function salle()
    {
        return $this->belongsTo(Salle::class, 'idSalle', 'idSalle');
    }
}
