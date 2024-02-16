<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelurahan extends Model
{
    protected $table = 'kelurahan';

    protected $fillable = [
        'id',
        'kode',
        'kecamatan_id',
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
        'kecamatan_id'    => 'integer',
        'nama'            => 'string',
        'chart_01'        => 'integer',
        'chart_02'        => 'integer',
        'chart_03'        => 'integer',
        'progress_proses' => 'integer',
        'progress_total'  => 'integer'
    ];
}
