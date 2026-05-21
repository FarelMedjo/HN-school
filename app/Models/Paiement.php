<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $primaryKey = 'idPaie';
    protected $guarded = [];

    protected $casts = [
        'datePaie' => 'date',
    ];

    public function eleve()
    {
        return $this->belongsTo(Eleve::class, 'matricule', 'matricule');
    }

    public function mode()
    {
        return $this->belongsTo(ModePaiement::class, 'idMode', 'idMode');
    }

    public function annee()
    {
        return $this->belongsTo(AnneeAcademique::class, 'idAca', 'idAnnee');
    }
}
