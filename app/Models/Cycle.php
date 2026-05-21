<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cycle extends Model
{
    protected $primaryKey = 'idCycle';
    protected $guarded = [];

    public function classes()
    {
        return $this->hasMany(Classe::class, 'idCycle', 'idCycle');
    }

    public function scolarites()
    {
        return $this->hasMany(Scolarite::class, 'idCycle', 'idCycle');
    }
}
