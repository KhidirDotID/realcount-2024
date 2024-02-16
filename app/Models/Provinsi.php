<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    protected $table = 'provinsi';

    protected $fillable = [
        'id',
        'nama',
        'chart_01',
        'chart_02',
        'chart_03',
        'progress_proses',
        'progress_total',
        'updated_at'
    ];
}
