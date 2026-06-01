<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Convocation extends Model
{
    protected $primaryKey = 'idConvocation';
    protected $guarded = [];

    protected $casts = [
        'dateRdv' => 'datetime',
    ];

    public function eleve()
    {
        return $this->belongsTo(Eleve::class, 'matricule', 'matricule');
    }

    public function auteur()
    {
        return $this->belongsTo(User::class, 'idAuteur');
    }
}
