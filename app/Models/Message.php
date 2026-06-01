<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $primaryKey = 'idMessages';
    protected $guarded    = [];

    protected $casts = [
        'valider' => 'boolean',
    ];

    public const TYPES = [
        1 => ['label' => 'Information', 'color' => 'sky'],
        2 => ['label' => 'Urgent',      'color' => 'rose'],
        3 => ['label' => 'Convocation', 'color' => 'amber'],
    ];

    public function expediteur()
    {
        return $this->belongsTo(Personne::class, 'idExp_Pers', 'idPers');
    }

    // idParent contient l'idPers du parent destinataire
    public function destinataire()
    {
        return $this->belongsTo(Personne::class, 'idParent', 'idPers');
    }

    public function scopeNonLus($query)
    {
        return $query->where('valider', false);
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type_message]['label'] ?? 'Message';
    }

    public function getTypeColorAttribute(): string
    {
        return self::TYPES[$this->type_message]['color'] ?? 'gray';
    }
}
