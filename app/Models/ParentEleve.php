<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentEleve extends Model
{
    protected $primaryKey = 'idParent';
    protected $guarded = [];
    protected $table = 'parent_eleves';

    public function personne()
    {
        return $this->belongsTo(Personne::class, 'idPers', 'idPers');
    }

    public function eleve()
    {
        return $this->belongsTo(Eleve::class, 'matricule', 'matricule');
    }
}
