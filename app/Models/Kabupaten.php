<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
    protected $table = 'kabupaten';

    protected $fillable = [
        'id',
        'provinsi_id',
        'nama',
        'chart_01',
        'chart_02',
        'chart_03',
        'progress_proses',
        'progress_total',
        'updated_at'
    ];
}
