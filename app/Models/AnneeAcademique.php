<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnneeAcademique extends Model
{
    protected $primaryKey = 'idAnnee';
    protected $guarded = [];

    public function trimestres()
    {
        return $this->hasMany(Trimestre::class, 'idAcad', 'idAnnee');
    }
}
