<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nasional extends Model
{
    protected $table = 'nasional';

    protected $fillable = [
        'chart_01',
        'chart_02',
        'chart_03',
        'progress_proses',
        'progress_total',
        'updated_at'
    ];
}
