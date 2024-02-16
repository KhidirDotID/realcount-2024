<?php

namespace App\Http\Controllers;

use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Nasional;
use App\Models\Ppwp;
use App\Models\Provinsi;
use App\Models\Tps;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PpwpController extends Controller
{
    public function nasional(Request $request): View
    {
        $now = Carbon::now();

        $ppwp = Ppwp::get();
        if (!$ppwp->count()) {
            $data = get_data_ppwp();

            foreach ($data as $value) {
                $ppwp[] = Ppwp::create([
                    'nama'       => $value->nama,
                    'warna'      => $value->warna,
                    'nomor_urut' => $value->nomor_urut
                ]);
            }
        }

        $data = Nasional::first();
        if (!$data) {
            $nasional = hitung_suara_nasional();

            $data = Nasional::create([
                'chart_01'        => $nasional->chart->{100025},
                'chart_02'        => $nasional->chart->{100026},
                'chart_03'        => $nasional->chart->{100027},
                'progress_proses' => $nasional->progres->progres,
                'progress_total'  => $nasional->progres->total,
                'updated_at'      => $nasional->ts
            ]);
        } else {
            if ($now->greaterThan($data->updated_at->addMinutes(1))) {
                $nasional = hitung_suara_nasional();

                $data->update([
                    'chart_01'        => $nasional->chart->{100025},
                    'chart_02'        => $nasional->chart->{100026},
                    'chart_03'        => $nasional->chart->{100027},
                    'progress_proses' => $nasional->progres->progres,
                    'progress_total'  => $nasional->progres->total,
                    'updated_at'      => $nasional->ts
                ]);
            }
        }

        $data2 = Provinsi::get();
        if (!$data2->count()) {
            $provinsi = get_wilayah_provinsi();
            $suara_provinsi = hitung_suara_provinsi();

            foreach ($provinsi as $value) {
                $table = $suara_provinsi->table->{$value->kode};
                $data2[] = Provinsi::create([
                    'id'              => $value->kode,
                    'nama'            => $value->nama,
                    'chart_01'        => $table->{100025} ?? 0,
                    'chart_02'        => $table->{100026} ?? 0,
                    'chart_03'        => $table->{100027} ?? 0,
                    'progress_proses' => 0,
                    'progress_total'  => 0,
                    'updated_at'      => $suara_provinsi->ts
                ]);
            }
        } else {
            if ($now->greaterThan($data2[0]->updated_at->addMinutes(1))) {
                $suara_provinsi = hitung_suara_provinsi();

                foreach ($data2 as $value) {
                    $table = $suara_provinsi->table->{$value->id};
                    $value->update([
                        'chart_01'   => $table->{100025} ?? 0,
                        'chart_02'   => $table->{100026} ?? 0,
                        'chart_03'   => $table->{100027} ?? 0,
                        'updated_at' => $suara_provinsi->ts
                    ]);
                }
            }
        }

        $data->suara_total = $data->chart_01 + $data->chart_02 + $data->chart_03;
        $data->persen_01 = hitung_persentase($data->chart_01, $data->suara_total);
        $data->persen_02 = hitung_persentase($data->chart_02, $data->suara_total);
        $data->persen_03 = hitung_persentase($data->chart_03, $data->suara_total);
        $data->persen_tps = hitung_persentase($data->progress_proses, $data->progress_total);

        $next_url = url('ppwp/hitung_suara');

        return view('ppwp.vote', compact('ppwp', 'data', 'data2', 'next_url'));
    }

    public function provinsi(Request $request, Provinsi $provinsi): View
    {
        $now = Carbon::now();

        $ppwp = Ppwp::get();

        if ($now->greaterThan($provinsi->updated_at->addMinutes(1))) {
            $suara_provinsi = hitung_suara_provinsi($provinsi->id);

            $provinsi->update([
                'chart_01'        => $suara_provinsi->chart?->{100025},
                'chart_02'        => $suara_provinsi->chart?->{100026},
                'chart_03'        => $suara_provinsi->chart?->{100027},
                'progress_proses' => $suara_provinsi->progres->progres ?? 0,
                'progress_total'  => $suara_provinsi->progres->total ?? 0,
                'updated_at'      => $suara_provinsi->ts
            ]);
        }

        $data2 = Kabupaten::where('provinsi_id', $provinsi->id)->get();
        if (!$data2->count()) {
            $kabupaten = get_wilayah_kabupaten($provinsi->id);
            $suara_kabupaten = hitung_suara_kabupaten($provinsi->id);

            foreach ($kabupaten as $value) {
                $table = $suara_kabupaten->table->{$value->kode};
                $data2[] = Kabupaten::create([
                    'id'              => is_numeric($value->kode) ? $value->kode : $value->id,
                    'kode'            => $value->kode,
                    'provinsi_id'     => $provinsi->id,
                    'nama'            => $value->nama,
                    'chart_01'        => $table->{100025} ?? 0,
                    'chart_02'        => $table->{100026} ?? 0,
                    'chart_03'        => $table->{100027} ?? 0,
                    'progress_proses' => 0,
                    'progress_total'  => 0,
                    'updated_at'      => $suara_kabupaten->ts
                ]);
            }
        } else {
            if ($now->greaterThan($data2[0]->updated_at->addMinutes(1))) {
                $suara_kabupaten = hitung_suara_kabupaten($provinsi->id);

                foreach ($data2 as $value) {
                    $table = $suara_kabupaten->table->{$value->kode};
                    $value->update([
                        'chart_01'   => $table->{100025} ?? 0,
                        'chart_02'   => $table->{100026} ?? 0,
                        'chart_03'   => $table->{100027} ?? 0,
                        'updated_at' => $suara_kabupaten->ts
                    ]);
                }
            }
        }

        $data = $provinsi;
        $data->suara_total = $provinsi->chart_01 + $provinsi->chart_02 + $provinsi->chart_03;
        $data->persen_01 = hitung_persentase($provinsi->chart_01, $data->suara_total);
        $data->persen_02 = hitung_persentase($provinsi->chart_02, $data->suara_total);
        $data->persen_03 = hitung_persentase($provinsi->chart_03, $data->suara_total);
        $data->persen_tps = hitung_persentase($provinsi->progress_proses, $provinsi->progress_total);

        $provinsi->url = url('ppwp/hitung_suara/' . $provinsi->id);

        return view('ppwp.vote', compact('ppwp', 'data', 'data2', 'provinsi'));
    }

    public function kabupaten(Request $request, Provinsi $provinsi, Kabupaten $kabupaten): View
    {
        $now = Carbon::now();

        $ppwp = Ppwp::get();

        if ($now->greaterThan($kabupaten->updated_at->addMinutes(1))) {
            $suara_kabupaten = hitung_suara_kabupaten($provinsi->id, $kabupaten->kode);

            $kabupaten->update([
                'chart_01'        => $suara_kabupaten->chart?->{100025},
                'chart_02'        => $suara_kabupaten->chart?->{100026},
                'chart_03'        => $suara_kabupaten->chart?->{100027},
                'progress_proses' => $suara_kabupaten->progres->progres ?? 0,
                'progress_total'  => $suara_kabupaten->progres->total ?? 0,
                'updated_at'      => $suara_kabupaten->ts
            ]);
        }

        $data2 = Kecamatan::where('kabupaten_id', $kabupaten->id)->get();
        if (!$data2->count()) {
            $kecamatan = get_wilayah_kecamatan($provinsi->id, $kabupaten->kode);
            $suara_kecamatan = hitung_suara_kecamatan($provinsi->id, $kabupaten->kode);

            foreach ($kecamatan as $value) {
                $table = $suara_kecamatan->table->{$value->kode};
                $data2[] = Kecamatan::create([
                    'id'              => is_numeric($value->kode) ? $value->kode : $value->id,
                    'kode'            => $value->kode,
                    'kabupaten_id'    => $kabupaten->id,
                    'nama'            => $value->nama,
                    'chart_01'        => $table->{100025} ?? 0,
                    'chart_02'        => $table->{100026} ?? 0,
                    'chart_03'        => $table->{100027} ?? 0,
                    'progress_proses' => 0,
                    'progress_total'  => 0,
                    'updated_at'      => $suara_kecamatan->ts
                ]);
            }
        } else {
            if ($now->greaterThan($data2[0]->updated_at->addMinutes(1))) {
                $suara_kecamatan = hitung_suara_kecamatan($provinsi->id, $kabupaten->kode);

                foreach ($data2 as $value) {
                    $table = $suara_kecamatan->table->{$value->kode};
                    $value->update([
                        'chart_01'   => $table->{100025} ?? 0,
                        'chart_02'   => $table->{100026} ?? 0,
                        'chart_03'   => $table->{100027} ?? 0,
                        'updated_at' => $suara_kecamatan->ts
                    ]);
                }
            }
        }

        $data = $kabupaten;
        $data->suara_total = $kabupaten->chart_01 + $kabupaten->chart_02 + $kabupaten->chart_03;
        $data->persen_01 = hitung_persentase($kabupaten->chart_01, $data->suara_total);
        $data->persen_02 = hitung_persentase($kabupaten->chart_02, $data->suara_total);
        $data->persen_03 = hitung_persentase($kabupaten->chart_03, $data->suara_total);
        $data->persen_tps = hitung_persentase($kabupaten->progress_proses, $kabupaten->progress_total);

        $provinsi->url = url('ppwp/hitung_suara/' . $provinsi->id);
        $kabupaten->url = $provinsi->url . '/' . $kabupaten->id;

        return view('ppwp.vote', compact('ppwp', 'data', 'data2', 'provinsi', 'kabupaten'));
    }

    public function kecamatan(Request $request, Provinsi $provinsi, Kabupaten $kabupaten, Kecamatan $kecamatan): View
    {
        $now = Carbon::now();

        $ppwp = Ppwp::get();

        if ($now->greaterThan($kecamatan->updated_at->addMinutes(1))) {
            $suara_kecamatan = hitung_suara_kecamatan($provinsi->id, $kabupaten->kode, $kecamatan->kode);

            $kecamatan->update([
                'chart_01'        => $suara_kecamatan->chart?->{100025},
                'chart_02'        => $suara_kecamatan->chart?->{100026},
                'chart_03'        => $suara_kecamatan->chart?->{100027},
                'progress_proses' => $suara_kecamatan->progres->progres ?? 0,
                'progress_total'  => $suara_kecamatan->progres->total ?? 0,
                'updated_at'      => $suara_kecamatan->ts
            ]);
        }

        $data2 = Kelurahan::where('kecamatan_id', $kecamatan->id)->get();
        if (!$data2->count()) {
            $kelurahan = get_wilayah_kelurahan($provinsi->id, $kabupaten->kode, $kecamatan->kode);
            $suara_kelurahan = hitung_suara_kelurahan($provinsi->id, $kabupaten->kode, $kecamatan->kode);

            foreach ($kelurahan as $value) {
                $table = $suara_kelurahan->table->{$value->kode};
                $data2[] = Kelurahan::create([
                    'id'              => is_numeric($value->kode) ? $value->kode : $value->id,
                    'kode'            => $value->kode,
                    'kecamatan_id'    => $kecamatan->id,
                    'nama'            => $value->nama,
                    'chart_01'        => $table->{100025} ?? 0,
                    'chart_02'        => $table->{100026} ?? 0,
                    'chart_03'        => $table->{100027} ?? 0,
                    'progress_proses' => 0,
                    'progress_total'  => 0,
                    'updated_at'      => $suara_kelurahan->ts
                ]);
            }
        } else {
            if ($now->greaterThan($data2[0]->updated_at->addMinutes(1))) {
                $suara_kelurahan = hitung_suara_kelurahan($provinsi->id, $kabupaten->kode, $kecamatan->kode);

                foreach ($data2 as $value) {
                    $table = $suara_kelurahan->table->{$value->kode};
                    $value->update([
                        'chart_01'   => $table->{100025} ?? 0,
                        'chart_02'   => $table->{100026} ?? 0,
                        'chart_03'   => $table->{100027} ?? 0,
                        'updated_at' => $suara_kelurahan->ts
                    ]);
                }
            }
        }

        $data = $kecamatan;
        $data->suara_total = $kecamatan->chart_01 + $kecamatan->chart_02 + $kecamatan->chart_03;
        $data->persen_01 = hitung_persentase($kecamatan->chart_01, $data->suara_total);
        $data->persen_02 = hitung_persentase($kecamatan->chart_02, $data->suara_total);
        $data->persen_03 = hitung_persentase($kecamatan->chart_03, $data->suara_total);
        $data->persen_tps = hitung_persentase($kecamatan->progress_proses, $kecamatan->progress_total);

        $provinsi->url = url('ppwp/hitung_suara/' . $provinsi->id);
        $kabupaten->url = $provinsi->url . '/' . $kabupaten->id;
        $kecamatan->url = $kabupaten->url . '/' . $kecamatan->id;

        return view('ppwp.vote', compact('ppwp', 'data', 'data2', 'provinsi', 'kabupaten', 'kecamatan'));
    }

    public function kelurahan(Request $request, Provinsi $provinsi, Kabupaten $kabupaten, Kecamatan $kecamatan, Kelurahan $kelurahan): View
    {
        $now = Carbon::now();

        $ppwp = Ppwp::get();

        if ($now->greaterThan($kelurahan->updated_at->addMinutes(1))) {
            $suara_kelurahan = hitung_suara_kelurahan($provinsi->id, $kabupaten->kode, $kecamatan->kode, $kelurahan->kode);

            $kelurahan->update([
                'chart_01'        => $suara_kelurahan->chart?->{100025},
                'chart_02'        => $suara_kelurahan->chart?->{100026},
                'chart_03'        => $suara_kelurahan->chart?->{100027},
                'progress_proses' => $suara_kelurahan->progres->progres ?? 0,
                'progress_total'  => $suara_kelurahan->progres->total ?? 0,
                'updated_at'      => $suara_kelurahan->ts
            ]);
        }

        $data2 = Tps::where('kelurahan_id', $kelurahan->id)->get();
        if (!$data2->count()) {
            $tps = get_wilayah_tps($provinsi->id, $kabupaten->kode, $kecamatan->kode, $kelurahan->kode);
            $suara_tps = hitung_suara_tps($provinsi->id, $kabupaten->kode, $kecamatan->kode, $kelurahan->kode);

            foreach ($tps as $value) {
                $table = $suara_tps->table->{$value->kode};
                $data2[] = Tps::create([
                    'id'              => is_numeric($value->kode) ? $value->kode : $value->id,
                    'kode'            => $value->kode,
                    'kelurahan_id'    => $kelurahan->id,
                    'nama'            => $value->nama,
                    'chart_01'        => $table->{100025} ?? 0,
                    'chart_02'        => $table->{100026} ?? 0,
                    'chart_03'        => $table->{100027} ?? 0,
                    'progress_proses' => 0,
                    'progress_total'  => 0,
                    'updated_at'      => $suara_tps->ts
                ]);
            }
        } else {
            if ($now->greaterThan($data2[0]->updated_at->addMinutes(1))) {
                $suara_tps = hitung_suara_tps($provinsi->id, $kabupaten->kode, $kecamatan->kode, $kelurahan->kode);

                foreach ($data2 as $value) {
                    $table = $suara_tps->table->{$value->kode};
                    $value->update([
                        'chart_01'   => $table->{100025} ?? 0,
                        'chart_02'   => $table->{100026} ?? 0,
                        'chart_03'   => $table->{100027} ?? 0,
                        'updated_at' => $suara_tps->ts
                    ]);
                }
            }
        }

        $data = $kelurahan;
        $data->suara_total = $kelurahan->chart_01 + $kelurahan->chart_02 + $kelurahan->chart_03;
        $data->persen_01 = hitung_persentase($kelurahan->chart_01, $data->suara_total);
        $data->persen_02 = hitung_persentase($kelurahan->chart_02, $data->suara_total);
        $data->persen_03 = hitung_persentase($kelurahan->chart_03, $data->suara_total);
        $data->persen_tps = hitung_persentase($kelurahan->progress_proses, $kelurahan->progress_total);

        $provinsi->url = url('ppwp/hitung_suara/' . $provinsi->id);
        $kabupaten->url = $provinsi->url . '/' . $kabupaten->id;
        $kecamatan->url = $kabupaten->url . '/' . $kecamatan->id;
        $kelurahan->url = $kecamatan->url . '/' . $kelurahan->id;

        return view('ppwp.vote', compact('ppwp', 'data', 'data2', 'provinsi', 'kabupaten', 'kecamatan', 'kelurahan'));
    }

    public function tps(Request $request, Provinsi $provinsi, Kabupaten $kabupaten, Kecamatan $kecamatan, Kelurahan $kelurahan, Tps $tps): View
    {
        $now = Carbon::now();

        $ppwp = Ppwp::get();

        if ($now->greaterThan($tps->updated_at->addMinutes(1))) {
            $suara_tps = hitung_suara_tps($provinsi->id, $kabupaten->kode, $kecamatan->kode, $kelurahan->kode, $tps->kode);

            $tps->update([
                'chart_01'                 => $suara_tps->chart?->{100025},
                'chart_02'                 => $suara_tps->chart?->{100026},
                'chart_03'                 => $suara_tps->chart?->{100027},
                'suara_sah'                => $suara_tps->administrasi?->suara_sah,
                'suara_tidak_sah'          => $suara_tps->administrasi?->suara_tidak_sah,
                'suara_total'              => $suara_tps->administrasi?->suara_total,
                'pemilih_dpt_laki'         => $suara_tps->administrasi?->pemilih_dpt_l,
                'pemilih_dpt_perempuan'    => $suara_tps->administrasi?->pemilih_dpt_p,
                'pemilih_dpt_jumlah'       => $suara_tps->administrasi?->pemilih_dpt_j,
                'pengguna_dpt_laki'        => $suara_tps->administrasi?->pengguna_dpt_l,
                'pengguna_dpt_perempuan'   => $suara_tps->administrasi?->pengguna_dpt_p,
                'pengguna_dpt_jumlah'      => $suara_tps->administrasi?->pengguna_dpt_j,
                'pengguna_dptb_laki'       => $suara_tps->administrasi?->pengguna_dptb_l,
                'pengguna_dptb_perempuan'  => $suara_tps->administrasi?->pengguna_dptb_p,
                'pengguna_dptb_jumlah'     => $suara_tps->administrasi?->pengguna_dptb_j,
                'pengguna_dpk_laki'        => $suara_tps->administrasi?->pengguna_non_dpt_l,
                'pengguna_dpk_perempuan'   => $suara_tps->administrasi?->pengguna_non_dpt_p,
                'pengguna_dpk_jumlah'      => $suara_tps->administrasi?->pengguna_non_dpt_j,
                'pengguna_total_laki'      => $suara_tps->administrasi?->pengguna_total_l,
                'pengguna_total_perempuan' => $suara_tps->administrasi?->pengguna_total_p,
                'pengguna_total_jumlah'    => $suara_tps->administrasi?->pengguna_total_j,
                'images_x1'                => isset($suara_tps->images) ? $suara_tps->images[0] : null,
                'images_x2'                => isset($suara_tps->images) ? $suara_tps->images[1] : null,
                'images_x3'                => isset($suara_tps->images) ? $suara_tps->images[2] : null,
                'updated_at'               => $suara_tps->ts
            ]);
        }

        $data = $tps;

        $provinsi->url = url('ppwp/hitung_suara/' . $provinsi->id);
        $kabupaten->url = $provinsi->url . '/' . $kabupaten->id;
        $kecamatan->url = $kabupaten->url . '/' . $kecamatan->id;
        $kelurahan->url = $kecamatan->url . '/' . $kelurahan->id;
        $tps->url = $kelurahan->url . '/' . $tps->id;

        return view('ppwp.vote', compact('ppwp', 'data', 'provinsi', 'kabupaten', 'kecamatan', 'kelurahan', 'tps'));
    }

    public function credits(Request $request): View
    {
        return view('dashboard');
    }
}
