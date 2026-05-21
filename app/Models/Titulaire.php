<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Titulaire extends Model
{
    protected $primaryKey = 'idTitu';
    protected $guarded = [];

    public function classe()
    {
        return $this->belongsTo(Classe::class, 'idClasse', 'idClasse');
    }

    public function salle()
    {
        return $this->belongsTo(Salle::class, 'idSalle', 'idSalle');
    }

    public function personne()
    {
        return $this->belongsTo(Personne::class, 'idPers', 'idPers');
    }
}
