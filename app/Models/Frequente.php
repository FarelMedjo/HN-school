<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Frequente extends Model
{
    protected $primaryKey = 'idFrequente';
    protected $guarded = [];

    public function eleve()
    {
        return $this->belongsTo(Eleve::class, 'matricule', 'matricule');
    }

    public function salle()
    {
        return $this->belongsTo(Salle::class, 'idSalle', 'idSalle');
    }

    public function annee()
    {
        return $this->belongsTo(AnneeAcademique::class, 'idAcademi', 'idAnnee');
    }
}
