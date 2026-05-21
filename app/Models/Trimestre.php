<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trimestre extends Model
{
    protected $primaryKey = 'idTrimes';
    protected $guarded = [];

    public function annee()
    {
        return $this->belongsTo(AnneeAcademique::class, 'idAcad', 'idAnnee');
    }
}
