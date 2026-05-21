<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Epreuve extends Model
{
    protected $primaryKey = 'idEpreuve';
    protected $guarded = [];

    public function nature()
    {
        return $this->belongsTo(NatureEpreuve::class, 'idNature', 'idNature');
    }
}
