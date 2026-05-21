<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salle extends Model
{
    protected $primaryKey = 'idSalle';
    protected $guarded = [];

    public function classe()
    {
        return $this->belongsTo(Classe::class, 'idClasse', 'idClasse');
    }
}
