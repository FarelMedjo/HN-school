<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enseignant extends Model
{
    protected $primaryKey = 'idEnseignant';
    protected $guarded = [];

    public function personne()
    {
        return $this->belongsTo(Personne::class, 'idPers', 'idPers');
    }

    public function cours()
    {
        return $this->belongsTo(Cours::class, 'idCours', 'idCours');
    }
}
