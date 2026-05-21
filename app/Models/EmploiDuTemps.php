<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmploiDuTemps extends Model
{
    protected $primaryKey = 'idTemps';
    protected $guarded = [];
    protected $table = 'emploi_du_temps';

    public function classe()
    {
        return $this->belongsTo(Classe::class, 'idClasse', 'idClasse');
    }

    public function cours()
    {
        return $this->belongsTo(Cours::class, 'idCours', 'idCours');
    }
}
