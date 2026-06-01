<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appreciation extends Model
{
    protected $primaryKey = 'idAppreciation';
    protected $guarded = [];

    public function eleve()
    {
        return $this->belongsTo(Eleve::class, 'matricule', 'matricule');
    }

    public function trimestre()
    {
        return $this->belongsTo(Trimestre::class, 'idTrimes', 'idTrimes');
    }

    public function auteur()
    {
        return $this->belongsTo(User::class, 'idAuteur');
    }
}
