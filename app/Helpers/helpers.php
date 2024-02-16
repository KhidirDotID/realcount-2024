<?php

use App\Models\Kabupaten;
use App\Models\Nasional;
use App\Models\Provinsi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

if (!function_exists('get_data_ppwp')) {
    /**
     * Get Data Pemilihan Presiden dan Wakil Presiden (PPWP)
     *
     * @source https://pemilu2024.kpu.go.id
     */
    function get_data_ppwp(): object
    {
        $data = new stdClass();
        $response = Http::get('https://sirekap-obj-data.kpu.go.id/pemilu/ppwp.json');

        if ($response->successful()) {
            $data = $response->object();
        }

        return $data;
    }
}

if (!function_exists('hitung_suara_nasional')) {
    /**
     * Hitung Suara Nasional
     *
     * @source https://pemilu2024.kpu.go.id
     */
    function hitung_suara_nasional(): object
    {
        $data = new stdClass();
        $response = Http::get('https://sirekap-obj-data.kpu.go.id/pemilu/hhcw/ppwp.json');

        if ($response->successful()) {
            $data = $response->object();
        }

        return $data;
    }
}

if (!function_exists('get_wilayah_provinsi')) {
    /**
     * Get Wilayah Provinsi
     *
     * @source https://pemilu2024.kpu.go.id
     */
    function get_wilayah_provinsi(): array
    {
        $data = [];
        $response = Http::get('https://sirekap-obj-data.kpu.go.id/wilayah/pemilu/ppwp/0.json');

        if ($response->successful()) {
            $data = $response->object();
        }

        return $data;
    }
}

if (!function_exists('hitung_suara_provinsi')) {
    /**
     * Hitung Suara Provinsi
     *
     * @source https://pemilu2024.kpu.go.id
     */
    function hitung_suara_provinsi($id = null): object
    {
        $data = new stdClass();
        $url = 'https://sirekap-obj-data.kpu.go.id/pemilu/hhcw/ppwp';
        $url .= $id ? '/' . $id : '';
        $url .= '.json';
        $response = Http::get($url);

        if ($response->successful()) {
            $data = $response->object();
        }

        return $data;
    }
}

if (!function_exists('get_wilayah_kabupaten')) {
    /**
     * Get Wilayah Kabupaten/Kota
     *
     * @source https://pemilu2024.kpu.go.id
     */
    function get_wilayah_kabupaten($provinsi_id): array
    {
        $data = [];
        $response = Http::get('https://sirekap-obj-data.kpu.go.id/wilayah/pemilu/ppwp/' . $provinsi_id . '.json');

        if ($response->successful()) {
            $data = $response->object();
        }

        return $data;
    }
}

if (!function_exists('hitung_suara_kabupaten')) {
    /**
     * Hitung Suara Kabupaten/Kota
     *
     * @source https://pemilu2024.kpu.go.id
     */
    function hitung_suara_kabupaten($provinsi_id, $id = null): object
    {
        $data = new stdClass();
        $url = 'https://sirekap-obj-data.kpu.go.id/pemilu/hhcw/ppwp/' . $provinsi_id;
        $url .= $id ? '/' . $id : '';
        $url .= '.json';
        $response = Http::get($url);

        if ($response->successful()) {
            $data = $response->object();
        }

        return $data;
    }
}

if (!function_exists('get_wilayah_kecamatan')) {
    /**
     * Get Wilayah Kecamatan
     *
     * @source https://pemilu2024.kpu.go.id
     */
    function get_wilayah_kecamatan($provinsi_id, $kabupaten_id): array
    {
        $data = [];
        $response = Http::get('https://sirekap-obj-data.kpu.go.id/wilayah/pemilu/ppwp/' . $provinsi_id . '/' . $kabupaten_id . '.json');

        if ($response->successful()) {
            $data = $response->object();
        }

        return $data;
    }
}

if (!function_exists('hitung_suara_kecamatan')) {
    /**
     * Hitung Suara Kecamatan
     *
     * @source https://pemilu2024.kpu.go.id
     */
    function hitung_suara_kecamatan($provinsi_id, $kabupaten_id, $id = null): object
    {
        $data = new stdClass();
        $url = 'https://sirekap-obj-data.kpu.go.id/pemilu/hhcw/ppwp/' . $provinsi_id . '/' . $kabupaten_id;
        $url .= $id ? '/' . $id : '';
        $url .= '.json';
        $response = Http::get($url);

        if ($response->successful()) {
            $data = $response->object();
        }

        return $data;
    }
}

if (!function_exists('get_wilayah_kelurahan')) {
    /**
     * Get Wilayah Kelurahan
     *
     * @source https://pemilu2024.kpu.go.id
     */
    function get_wilayah_kelurahan($provinsi_id, $kabupaten_id, $kecamatan_id): array
    {
        $data = [];
        $response = Http::get('https://sirekap-obj-data.kpu.go.id/wilayah/pemilu/ppwp/' . $provinsi_id . '/' . $kabupaten_id . '/' . $kecamatan_id . '.json');

        if ($response->successful()) {
            $data = $response->object();
        }

        return $data;
    }
}

if (!function_exists('hitung_suara_kelurahan')) {
    /**
     * Hitung Suara Kelurahan
     *
     * @source https://pemilu2024.kpu.go.id
     */
    function hitung_suara_kelurahan($provinsi_id, $kabupaten_id, $kecamatan_id, $id = null): object
    {
        $data = new stdClass();
        $url = 'https://sirekap-obj-data.kpu.go.id/pemilu/hhcw/ppwp/' . $provinsi_id . '/' . $kabupaten_id . '/' . $kecamatan_id;
        $url .= $id ? '/' . $id : '';
        $url .= '.json';
        $response = Http::get($url);

        if ($response->successful()) {
            $data = $response->object();
        }

        return $data;
    }
}

if (!function_exists('get_wilayah_tps')) {
    /**
     * Get Wilayah TPS
     *
     * @source https://pemilu2024.kpu.go.id
     */
    function get_wilayah_tps($provinsi_id, $kabupaten_id, $kecamatan_id, $kelurahan_id): array
    {
        $data = [];
        $response = Http::get('https://sirekap-obj-data.kpu.go.id/wilayah/pemilu/ppwp/' . $provinsi_id . '/' . $kabupaten_id . '/' . $kecamatan_id . '/' . $kelurahan_id . '.json');

        if ($response->successful()) {
            $data = $response->object();
        }

        return $data;
    }
}

if (!function_exists('hitung_suara_tps')) {
    /**
     * Hitung Suara TPS
     *
     * @source https://pemilu2024.kpu.go.id
     */
    function hitung_suara_tps($provinsi_id, $kabupaten_id, $kecamatan_id, $kelurahan_id, $id = null): object
    {
        $data = new stdClass();
        $url = 'https://sirekap-obj-data.kpu.go.id/pemilu/hhcw/ppwp/' . $provinsi_id . '/' . $kabupaten_id . '/' . $kecamatan_id . '/' . $kelurahan_id;
        $url .= $id ? '/' . $id : '';
        $url .= '.json';
        $response = Http::get($url);

        if ($response->successful()) {
            $data = $response->object();
        }

        return $data;
    }
}

if (!function_exists('hitung_persentase')) {
    /**
     * Hitung Persentase Perolehan Suara
     */
    function hitung_persentase($suara, $suara_total): int|float
    {
        return $suara_total ? ($suara / $suara_total) * 100 : 0;
    }
}
