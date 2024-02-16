@extends('layouts.app')

@section('title', 'Info Publik Pemilu 2024')

@section('content')
    <section class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-primary text-center">
                    <h4 class="card-title text-white">HASIL HITUNG SUARA PEMILU PRESIDEN & WAKIL PRESIDEN RI 2024</h4>
                </div>
                <div class="card-body">
                    <h5 class="text-center">
                        @if (Request::segment(1))
                            WILAYAH PEMILIHAN
                            {{-- @if (Request::segment(5))
                                <a href="">KEC. $data2['nama_kecamatan']</a> -
                            @endif
                            @if (Request::segment(4))
                                <a href="">strpos($data2['nama_kabupaten'], 'KOTA ') === false ? 'KAB. ' . $data2['nama_kabupaten'] : $data2['nama_kabupaten']</a> -
                            @endif --}}
                            <a href="{{ $prov_url }}">PROV. $data2['nama_provinsi']</a>
                        @else
                            TINGKAT NASIONAL
                        @endif
                    </h5>
                </div>
            </div>
        </div>
    </section>
@endsection
