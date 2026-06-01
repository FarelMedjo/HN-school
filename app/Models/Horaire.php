<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horaire extends Model
{
    protected $primaryKey = 'idHoraire';
    protected $guarded = [];
    protected $table = 'horaires';
}
