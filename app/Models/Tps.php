<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tps extends Model
{
    protected $table = 'tps';

    protected $fillable = [
        'id',
        'kelurahan_id',
        'nama',
        'chart_01',
        'chart_02',
        'chart_03',
        'suara_sah',
        'suara_total',
        'pengguna_j',
        'pemilih_j',
        'images_x1',
        'images_x2',
        'updated_at'
    ];
}
