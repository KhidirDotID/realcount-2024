<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ppwp extends Model
{
    protected $table = 'ppwp';

    protected $fillable = [
        'nama',
        'warna',
        'nomor_urut'
    ];

    public $timestamps = false;
}
