<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Eleve extends Model
{
    protected $primaryKey = 'matricule';
    protected $guarded = [];

    public function villeNaissance()
    {
        return $this->belongsTo(VilleNaissance::class, 'idVilleNaissance', 'idVille');
    }

    public function frequentations()
    {
        return $this->hasMany(Frequente::class, 'matricule', 'matricule');
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'matricule', 'matricule');
    }
}
