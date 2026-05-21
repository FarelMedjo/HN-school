<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cours extends Model
{
    protected $primaryKey = 'idCours';
    protected $guarded = [];
    protected $table = 'cours';

    public function classe()
    {
        return $this->belongsTo(Classe::class, 'idClasse', 'idClasse');
    }

    public function enseignant()
    {
        return $this->belongsTo(Personne::class, 'idPers', 'idPers');
    }
}
