<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rapport extends Model
{
    protected $primaryKey = 'idRap';
    protected $guarded = [];

    public function eleve()
    {
        return $this->belongsTo(Eleve::class, 'matricule', 'matricule');
    }

    public function annee()
    {
        return $this->belongsTo(AnneeAcademique::class, 'idAca', 'idAnnee');
    }
}
