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

    protected $casts = [
        'nama'       => 'string',
        'warna'      => 'string',
        'nomor_urut' => 'string'
    ];

    public $timestamps = false;
}
