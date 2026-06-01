<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Livre extends Model
{
    protected $primaryKey = 'idLivre';
    protected $guarded = [];

    protected $casts = [
        'anneeEdition' => 'integer',
        'quantiteTotal' => 'integer',
        'quantiteDisponible' => 'integer',
    ];

    public function emprunts()
    {
        return $this->hasMany(Emprunt::class, 'idLivre', 'idLivre');
    }
}
