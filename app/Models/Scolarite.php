<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scolarite extends Model
{
    protected $primaryKey = 'idScolarite';
    protected $guarded = [];

    public function cycle()
    {
        return $this->belongsTo(Cycle::class, 'idCycle', 'idCycle');
    }

    public function tranches()
    {
        return $this->hasMany(Tranche::class, 'idScolarite', 'idScolarite');
    }
}
