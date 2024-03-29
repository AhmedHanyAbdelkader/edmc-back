<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model{

    protected $table = 'sector';
    public $timestamps = false;
    protected $primaryKey = 'sector_id';
    public $incrementing = false;

    protected $fillable = [
        'sector_id',
        'sector_name'
    ];
}
