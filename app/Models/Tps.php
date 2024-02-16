<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tps extends Model
{
    protected $table = 'tps';

    protected $fillable = [
        'id',
        'kode',
        'kelurahan_id',
        'nama',
        'chart_01',
        'chart_02',
        'chart_03',
        'suara_sah',
        'suara_tidak_sah',
        'suara_total',
        'pemilih_dpt_laki',
        'pemilih_dpt_perempuan',
        'pemilih_dpt_jumlah',
        'pengguna_dpt_laki',
        'pengguna_dpt_perempuan',
        'pengguna_dpt_jumlah',
        'pengguna_dptb_laki',
        'pengguna_dptb_perempuan',
        'pengguna_dptb_jumlah',
        'pengguna_dpk_laki',
        'pengguna_dpk_perempuan',
        'pengguna_dpk_jumlah',
        'pengguna_total_laki',
        'pengguna_total_perempuan',
        'pengguna_total_jumlah',
        'images_x1',
        'images_x2',
        'images_x3',
        'updated_at'
    ];
}
