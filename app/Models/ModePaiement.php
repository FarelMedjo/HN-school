<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModePaiement extends Model
{
    protected $primaryKey = 'idMode';
    protected $guarded = [];
    protected $table = 'mode_paiements';
}
