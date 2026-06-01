<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emprunt extends Model
{
    protected $primaryKey = 'idEmprunt';
    protected $guarded = [];

    protected $casts = [
        'dateEmprunt' => 'date',
        'dateRetourPrevue' => 'date',
        'dateRetourReelle' => 'date',
    ];

    public function livre()
    {
        return $this->belongsTo(Livre::class, 'idLivre', 'idLivre');
    }

    public function eleve()
    {
        return $this->belongsTo(Eleve::class, 'matricule', 'matricule');
    }

    // Prêt en cours dont l'échéance est dépassée.
    public function getEnRetardAttribute(): bool
    {
        return $this->statut === 'en_cours'
            && $this->dateRetourPrevue
            && $this->dateRetourPrevue->lt(today());
    }
}
