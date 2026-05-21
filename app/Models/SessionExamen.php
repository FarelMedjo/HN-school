<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionExamen extends Model
{
    protected $primaryKey = 'idSession';
    protected $guarded = [];
    protected $table = 'session_examens';

    public function trimestre()
    {
        return $this->belongsTo(Trimestre::class, 'idTrimestre', 'idTrimes');
    }
}
