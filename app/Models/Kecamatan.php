<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table = 'kecamatan';

    protected $fillable = [
        'id',
        'kode',
        'kabupaten_id',
        'nama',
        'chart_01',
        'chart_02',
        'chart_03',
        'progress_proses',
        'progress_total',
        'updated_at'
    ];

    protected $casts = [
        'id'              => 'integer',
        'kode'            => 'string',
        'kabupaten_id'    => 'integer',
        'nama'            => 'string',
        'chart_01'        => 'integer',
        'chart_02'        => 'integer',
        'chart_03'        => 'integer',
        'progress_proses' => 'integer',
        'progress_total'  => 'integer'
    ];
}
