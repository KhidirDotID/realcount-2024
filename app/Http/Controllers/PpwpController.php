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
        $data->persen_01 = $data->suara_total ? $data->chart_01 / $data->suara_total * 100 : 0;
        $data->persen_02 = $data->suara_total ? $data->chart_02 / $data->suara_total * 100 : 0;
        $data->persen_03 = $data->suara_total ? $data->chart_03 / $data->suara_total * 100 : 0;
        $data->persen_tps = $data->progress_total ? $data->progress_proses / $data->progress_total * 100 : 0;

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
                'chart_01'        => $suara_provinsi->chart->{100025},
                'chart_02'        => $suara_provinsi->chart->{100026},
                'chart_03'        => $suara_provinsi->chart->{100027},
                'progress_proses' => $suara_provinsi->progres->progres,
                'progress_total'  => $suara_provinsi->progres->total,
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
                    'id'              => $value->kode,
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
                    $table = $suara_kabupaten->table->{$value->id};
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
        $data->persen_01 = $data->suara_total ? $provinsi->chart_01 / $data->suara_total * 100 : 0;
        $data->persen_02 = $data->suara_total ? $provinsi->chart_02 / $data->suara_total * 100 : 0;
        $data->persen_03 = $data->suara_total ? $provinsi->chart_03 / $data->suara_total * 100 : 0;
        $data->persen_tps = $provinsi->progress_total ? $provinsi->progress_proses / $provinsi->progress_total * 100 : 0;

        $provinsi->url = url('ppwp/hitung_suara/' . $provinsi->id);
        $next_url = $provinsi->url;

        return view('ppwp.vote', compact('ppwp', 'data', 'data2', 'provinsi', 'next_url'));
    }

    public function kabupaten(Request $request, Provinsi $provinsi, Kabupaten $kabupaten): View
    {
        $now = Carbon::now();

        $ppwp = Ppwp::get();

        if ($now->greaterThan($kabupaten->updated_at->addMinutes(1))) {
            $suara_kabupaten = hitung_suara_kabupaten($provinsi->id, $kabupaten->id);

            $kabupaten->update([
                'chart_01'        => $suara_kabupaten->chart->{100025},
                'chart_02'        => $suara_kabupaten->chart->{100026},
                'chart_03'        => $suara_kabupaten->chart->{100027},
                'progress_proses' => $suara_kabupaten->progres->progres,
                'progress_total'  => $suara_kabupaten->progres->total,
                'updated_at'      => $suara_kabupaten->ts
            ]);
        }

        $data2 = Kecamatan::where('kabupaten_id', $kabupaten->id)->get();
        if (!$data2->count()) {
            $kecamatan = get_wilayah_kecamatan($provinsi->id, $kabupaten->id);
            $suara_kecamatan = hitung_suara_kecamatan($provinsi->id, $kabupaten->id);

            foreach ($kecamatan as $value) {
                $table = $suara_kecamatan->table->{$value->kode};
                $data2[] = Kecamatan::create([
                    'id'              => $value->kode,
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
                $suara_kecamatan = hitung_suara_kecamatan($provinsi->id, $kabupaten->id);

                foreach ($data2 as $value) {
                    $table = $suara_kecamatan->table->{$value->id};
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
        $data->persen_01 = $data->suara_total ? $kabupaten->chart_01 / $data->suara_total * 100 : 0;
        $data->persen_02 = $data->suara_total ? $kabupaten->chart_02 / $data->suara_total * 100 : 0;
        $data->persen_03 = $data->suara_total ? $kabupaten->chart_03 / $data->suara_total * 100 : 0;
        $data->persen_tps = $kabupaten->progress_total ? $kabupaten->progress_proses / $kabupaten->progress_total * 100 : 0;

        $provinsi->url = url('ppwp/hitung_suara/' . $provinsi->id);
        $kabupaten->url = $provinsi->url . '/' . $kabupaten->id;
        $next_url = $kabupaten->url;

        return view('ppwp.vote', compact('ppwp', 'data', 'data2', 'provinsi', 'kabupaten', 'next_url'));
    }

    public function kecamatan(Request $request, Provinsi $provinsi, Kabupaten $kabupaten, Kecamatan $kecamatan): View
    {
        $now = Carbon::now();

        $ppwp = Ppwp::get();

        if ($now->greaterThan($kecamatan->updated_at->addMinutes(1))) {
            $suara_kecamatan = hitung_suara_kecamatan($provinsi->id, $kabupaten->id, $kecamatan->id);

            $kecamatan->update([
                'chart_01'        => $suara_kecamatan->chart->{100025},
                'chart_02'        => $suara_kecamatan->chart->{100026},
                'chart_03'        => $suara_kecamatan->chart->{100027},
                'progress_proses' => $suara_kecamatan->progres->progres,
                'progress_total'  => $suara_kecamatan->progres->total,
                'updated_at'      => $suara_kecamatan->ts
            ]);
        }

        $data2 = Kelurahan::where('kecamatan_id', $kecamatan->id)->get();
        if (!$data2->count()) {
            $kelurahan = get_wilayah_kelurahan($provinsi->id, $kabupaten->id, $kecamatan->id);
            $suara_kelurahan = hitung_suara_kelurahan($provinsi->id, $kabupaten->id, $kecamatan->id);

            foreach ($kelurahan as $value) {
                $table = $suara_kelurahan->table->{$value->kode};
                $data2[] = Kelurahan::create([
                    'id'              => $value->kode,
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
                $suara_kelurahan = hitung_suara_kelurahan($provinsi->id, $kabupaten->id, $kecamatan->id);

                foreach ($data2 as $value) {
                    $table = $suara_kelurahan->table->{$value->id};
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
        $data->persen_01 = $data->suara_total ? $kecamatan->chart_01 / $data->suara_total * 100 : 0;
        $data->persen_02 = $data->suara_total ? $kecamatan->chart_02 / $data->suara_total * 100 : 0;
        $data->persen_03 = $data->suara_total ? $kecamatan->chart_03 / $data->suara_total * 100 : 0;
        $data->persen_tps = $kecamatan->progress_total ? $kecamatan->progress_proses / $kecamatan->progress_total * 100 : 0;

        $provinsi->url = url('ppwp/hitung_suara/' . $provinsi->id);
        $kabupaten->url = $provinsi->url . '/' . $kabupaten->id;
        $kecamatan->url = $kabupaten->url . '/' . $kecamatan->id;
        $next_url = $kecamatan->url;

        return view('ppwp.vote', compact('ppwp', 'data', 'data2', 'provinsi', 'kabupaten', 'kecamatan', 'next_url'));
    }

    public function kelurahan(Request $request, Provinsi $provinsi, Kabupaten $kabupaten, Kecamatan $kecamatan, Kelurahan $kelurahan): View
    {
        $now = Carbon::now();

        $ppwp = Ppwp::get();

        if ($now->greaterThan($kelurahan->updated_at->addMinutes(1))) {
            $suara_kelurahan = hitung_suara_kelurahan($provinsi->id, $kabupaten->id, $kecamatan->id, $kelurahan->id);

            $kelurahan->update([
                'chart_01'        => $suara_kelurahan->chart->{100025},
                'chart_02'        => $suara_kelurahan->chart->{100026},
                'chart_03'        => $suara_kelurahan->chart->{100027},
                'progress_proses' => $suara_kelurahan->progres->progres,
                'progress_total'  => $suara_kelurahan->progres->total,
                'updated_at'      => $suara_kelurahan->ts
            ]);
        }

        $data2 = Tps::where('kelurahan_id', $kelurahan->id)->get();
        if (!$data2->count()) {
            $tps = get_wilayah_tps($provinsi->id, $kabupaten->id, $kecamatan->id, $kelurahan->id);
            $suara_tps = hitung_suara_tps($provinsi->id, $kabupaten->id, $kecamatan->id, $kelurahan->id);

            foreach ($tps as $value) {
                $table = $suara_tps->table->{$value->kode};
                $data2[] = Tps::create([
                    'id'              => $value->kode,
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
                $suara_tps = hitung_suara_tps($provinsi->id, $kabupaten->id, $kecamatan->id, $kelurahan->id);

                foreach ($data2 as $value) {
                    $table = $suara_tps->table->{$value->id};
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
        $data->persen_01 = $data->suara_total ? $kelurahan->chart_01 / $data->suara_total * 100 : 0;
        $data->persen_02 = $data->suara_total ? $kelurahan->chart_02 / $data->suara_total * 100 : 0;
        $data->persen_03 = $data->suara_total ? $kelurahan->chart_03 / $data->suara_total * 100 : 0;
        $data->persen_tps = $kelurahan->progress_total ? $kelurahan->progress_proses / $kelurahan->progress_total * 100 : 0;

        $provinsi->url = url('ppwp/hitung_suara/' . $provinsi->id);
        $kabupaten->url = $provinsi->url . '/' . $kabupaten->id;
        $kecamatan->url = $kabupaten->url . '/' . $kecamatan->id;
        $kelurahan->url = $kecamatan->url . '/' . $kelurahan->id;
        $next_url = $kelurahan->url;

        return view('ppwp.vote', compact('ppwp', 'data', 'data2', 'provinsi', 'kabupaten', 'kecamatan', 'kelurahan', 'next_url'));
    }

    public function tps(Request $request, Tps $tps): View
    {
        return view('ppwp.vote');
    }

    public function credits(Request $request): View
    {
        return view('dashboard');
    }
}
