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

    protected $casts = [
        'id'                       => 'integer',
        'kode'                     => 'string',
        'kelurahan_id'             => 'integer',
        'nama'                     => 'string',
        'chart_01'                 => 'integer',
        'chart_02'                 => 'integer',
        'chart_03'                 => 'integer',
        'suara_sah'                => 'integer',
        'suara_tidak_sah'          => 'integer',
        'suara_total'              => 'integer',
        'pemilih_dpt_laki'         => 'integer',
        'pemilih_dpt_perempuan'    => 'integer',
        'pemilih_dpt_jumlah'       => 'integer',
        'pengguna_dpt_laki'        => 'integer',
        'pengguna_dpt_perempuan'   => 'integer',
        'pengguna_dpt_jumlah'      => 'integer',
        'pengguna_dptb_laki'       => 'integer',
        'pengguna_dptb_perempuan'  => 'integer',
        'pengguna_dptb_jumlah'     => 'integer',
        'pengguna_dpk_laki'        => 'integer',
        'pengguna_dpk_perempuan'   => 'integer',
        'pengguna_dpk_jumlah'      => 'integer',
        'pengguna_total_laki'      => 'integer',
        'pengguna_total_perempuan' => 'integer',
        'pengguna_total_jumlah'    => 'integer',
        'images_x1'                => 'string',
        'images_x2'                => 'string',
        'images_x3'                => 'string'
    ];
}
