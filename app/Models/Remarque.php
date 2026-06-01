<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Remarque extends Model
{
    protected $primaryKey = 'idRemarque';
    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
    ];

    public const CATEGORIES = [
        'comportement' => ['label' => 'Comportement', 'color' => 'rose'],
        'travail'      => ['label' => 'Travail',      'color' => 'amber'],
        'assiduite'    => ['label' => 'Assiduité',    'color' => 'sky'],
        'felicitation' => ['label' => 'Félicitation', 'color' => 'emerald'],
        'autre'        => ['label' => 'Autre',        'color' => 'gray'],
    ];

    public function eleve()
    {
        return $this->belongsTo(Eleve::class, 'matricule', 'matricule');
    }

    public function auteur()
    {
        return $this->belongsTo(User::class, 'idAuteur');
    }

    public function getCategorieLabelAttribute(): string
    {
        return self::CATEGORIES[$this->categorie]['label'] ?? ucfirst($this->categorie);
    }

    public function getCategorieColorAttribute(): string
    {
        return self::CATEGORIES[$this->categorie]['color'] ?? 'gray';
    }
}
