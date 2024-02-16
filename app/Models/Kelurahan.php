<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelurahan extends Model
{
    protected $table = 'kelurahan';

    protected $fillable = [
        'id',
        'kecamatan_id',
        'nama',
        'chart_01',
        'chart_02',
        'chart_03',
        'progress_proses',
        'progress_total',
        'updated_at'
    ];
}
