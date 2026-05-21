<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    protected $primaryKey = 'idResi';
    protected $guarded = [];

    public function personne()
    {
        return $this->belongsTo(Personne::class, 'idPers', 'idPers');
    }

    public function quartier()
    {
        return $this->belongsTo(Quartier::class, 'idQuartier', 'idQuartier');
    }
}
