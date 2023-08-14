<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Importance extends Model
{
    protected $table = 'importance';
    protected $primaryKey = 'importance_id';
    public $timestamps = false;

    protected $fillable = [
       'importance_status',
    ];
}
