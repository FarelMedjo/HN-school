<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    protected $primaryKey = 'idClasse';
    protected $guarded = [];

    public function cycle()
    {
        return $this->belongsTo(Cycle::class, 'idCycle', 'idCycle');
    }

    public function cours()
    {
        return $this->hasMany(Cours::class, 'idClasse', 'idClasse');
    }

    public function salles()
    {
        return $this->hasMany(Salle::class, 'idClasse', 'idClasse');
    }
}
