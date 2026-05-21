<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $primaryKey = 'idEval';
    protected $guarded = [];

    public function eleve()
    {
        return $this->belongsTo(Eleve::class, 'matricule', 'matricule');
    }

    public function epreuve()
    {
        return $this->belongsTo(Epreuve::class, 'idEpreuve', 'idEpreuve');
    }

    public function cours()
    {
        return $this->belongsTo(Cours::class, 'idCours', 'idCours');
    }

    public function session()
    {
        return $this->belongsTo(SessionExamen::class, 'idSession', 'idSession');
    }
}
