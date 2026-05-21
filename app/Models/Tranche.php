<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tranche extends Model
{
    protected $primaryKey = 'idTranche';
    protected $guarded = [];

    public function scolarite()
    {
        return $this->belongsTo(Scolarite::class, 'idScolarite', 'idScolarite');
    }
}
