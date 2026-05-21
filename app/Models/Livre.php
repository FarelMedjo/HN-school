<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Livre extends Model
{
    protected $primaryKey = 'idLivre';
    protected $guarded = [];

    public function specialite()
    {
        return $this->belongsTo(Specialite::class, 'idSpecialite', 'idSpecialite');
    }
}
