<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Personne extends Model
{
    protected $primaryKey = 'idPers';
    protected $guarded = [];
    protected $hidden = ['password'];

    public function residents()
    {
        return $this->hasMany(Resident::class, 'idPers', 'idPers');
    }

    public function enseignant()
    {
        return $this->hasOne(Enseignant::class, 'idPers', 'idPers');
    }
}
