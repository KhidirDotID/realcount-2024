@extends('layouts.app')

@section('title', 'Info Publik Pemilu 2024')

@push('plugin-styles')
    @if (!Request::segment(7))
        <link rel="stylesheet" href="{{ asset('assets/extensions/datatables-net-bs5/dataTables.bootstrap5.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/extensions/datatables-net/responsive/responsive.bootstrap5.css') }}">
    @endif
@endpush

@section('content')
    <div class="page-heading d-flex align-items-center">
        @if (Request::segment(3))
            @php
                $prev_url = url('ppwp/hitung_suara');
                $prev_url .= Request::segment(4) ? '/' . Request::segment(3) : '';
                $prev_url .= Request::segment(5) ? '/' . Request::segment(4) : '';
                $prev_url .= Request::segment(6) ? '/' . Request::segment(5) : '';
                $prev_url .= Request::segment(7) ? '/' . Request::segment(6) : '';
            @endphp
            <a href="{{ $prev_url }}" class="btn btn-outline-secondary me-3"><i class="fas fa-angle-left"></i></a>
        @endif
        <h3 class="mb-0">Info Publik Pemilu 2024</h3>
    </div>
    <div class="page-content">
        <section class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header bg-primary text-center">
                        <h4 class="card-title text-white">HASIL HITUNG SUARA PEMILU PRESIDEN & WAKIL PRESIDEN RI 2024</h4>
                    </div>
                    <div class="card-body">
                        <h5 class="text-center">
                            @if (Request::segment(3))
                                WILAYAH PEMILIHAN
                                @if (Request::segment(7))
                                    <a href="{{ $tps->url }}">{{ $tps->nama }}</a> -
                                @endif
                                @if (Request::segment(6))
                                    <a href="{{ $kelurahan->url }}">KEL. {{ $kelurahan->nama }}</a> -
                                @endif
                                @if (Request::segment(5))
                                    <a href="{{ $kecamatan->url }}">KEC. {{ $kecamatan->nama }}</a> -
                                @endif
                                @if (Request::segment(4))
                                    @if ($provinsi->id == 99)
                                        <a href="{{ $kabupaten->url }}">{{ $kabupaten->nama }}</a> -
                                    @else
                                        <a href="{{ $kabupaten->url }}">{{ strpos($kabupaten->nama, 'KOTA ') === false ? 'KAB. ' . $kabupaten->nama : $kabupaten->nama }}</a> -
                                    @endif
                                @endif
                                @if ($provinsi->id == 99)
                                    <a href="{{ $provinsi->url }}">{{ $provinsi->nama }}</a>
                                @else
                                    <a href="{{ $provinsi->url }}">PROV. {{ $provinsi->nama }}</a>
                                @endif
                            @else
                                TINGKAT NASIONAL
                            @endif
                        </h5>

                        @if (Request::segment(7))
                            <div class="card border">
                                <div class="card-body">
                                    <p class="fw-bold">DATA PEMILIH DAN PENGGUNAAN HAK PILIH</p>
                                    <table class="table table-striped" width="100%">
                                        <thead>
                                            <tr class="bg-dark">
                                                <th class="text-light" width="75%">URAIAN</th>
                                                <th class="text-light" width="25%">JUMLAH (L+P)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>PEMILIH TERDAFTAR (DPT)</td>
                                                <td>{{ $data->pemilih_dpt_jumlah }}</td>
                                            </tr>
                                            <tr>
                                                <td>PENGGUNA HAK PILIH</td>
                                                <td>{{ $data->pengguna_total_jumlah }}</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    @php
                                        $data->tertinggi_01 = $data->chart_01 > $data->chart_02 && $data->chart_01 > $data->chart_03 ? 'bg-success text-white' : '';
                                        $data->tertinggi_02 = $data->chart_02 > $data->chart_01 && $data->chart_02 > $data->chart_03 ? 'bg-success text-white' : '';
                                        $data->tertinggi_03 = $data->chart_03 > $data->chart_01 && $data->chart_03 > $data->chart_02 ? 'bg-success text-white' : '';
                                    @endphp
                                    <p class="fw-bold">PEROLEHAN SUARA</p>
                                    <table class="table table-striped" width="100%">
                                        <thead>
                                            <tr class="bg-dark">
                                                <th class="text-light" colspan="2">URAIAN</th>
                                                <th class="text-light">SUARA SAH</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td width="5%">1</td>
                                                <td width="70%">{{ $ppwp[0]['nama'] }}</td>
                                                <td class="{{ $data->tertinggi_01 }}" width="25%">{{ $data->chart_01 }}</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>{{ $ppwp[1]['nama'] }}</td>
                                                <td class="{{ $data->tertinggi_02 }}">{{ $data->chart_02 }}</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>{{ $ppwp[2]['nama'] }}</td>
                                                <td class="{{ $data->tertinggi_03 }}">{{ $data->chart_03 }}</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <p class="fw-bold">JUMLAH SUARA SAH DAN TIDAK SAH</p>
                                    <table class="table table-striped" width="100%">
                                        <thead>
                                            <tr class="bg-dark">
                                                <th class="text-light" colspan="2">URAIAN</th>
                                                <th class="text-light">JUMLAH</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td width="5%">A</td>
                                                <td width="70%">JUMLAH SELURUH SUARA SAH</td>
                                                <td width="25%">{{ $data->suara_sah }}</td>
                                            </tr>
                                            <tr>
                                                <td>B</td>
                                                <td>JUMLAH SUARA TIDAK SAH</td>
                                                <td>{{ $data->suara_tidak_sah }}</td>
                                            </tr>
                                            <tr>
                                                <td>C</td>
                                                <td>JUMLAH SELURUH SUARA SAH DAN SUARA TIDAK SAH</td>
                                                <td>{{ $data->suara_total }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="text-end mb-3">
                                <button type="button" class="btn btn-dark" data-bs-toggle="collapse" data-bs-target="#collapseC1">Form Pindai C1</button>
                            </div>
                            <div class="collapse" id="collapseC1">
                                <div class="card border">
                                    <div class="card-header bg-primary text-center">
                                        <h4 class="card-title text-white">HASIL PINDAI DOKUMEN</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <a href="{{ $data->images_x1 }}" data-fslightbox><img class="img-fluid" src="{{ $data->images_x1 }}"></a>
                                            </div>
                                            <div class="col-md-4">
                                                <a href="{{ $data->images_x2 }}" data-fslightbox><img class="img-fluid" src="{{ $data->images_x2 }}"></a>
                                            </div>
                                            <div class="col-md-4">
                                                <a href="{{ $data->images_x3 }}" data-fslightbox><img class="img-fluid" src="{{ $data->images_x3 }}"></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center mb-2">
                                <div class="badge bg-primary rounded-pill">
                                    <span class="d-block d-md-inline">Update: {{ $data->updated_at->format('d M Y H:i:s') }}</span>
                                    Progress: {{ number_format($data->progress_proses, 0, ',', '.') }} dari {{ number_format($data->progress_total, 0, ',', '.') }} TPS ({{ number_format($data->persen_tps, 2, ',', '.') }}%)
                                </div>
                            </div>

                            @if ($data->progress_proses)
                                <div id="chart" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                            @else
                                <div class="alert alert-warning text-center">Data belum tersedia</div>
                            @endif

                            <div class="text-center my-2">
                                <div class="badge bg-primary rounded-pill">
                                    <span class="d-block d-md-inline">Update: {{ $data->updated_at->format('d M Y H:i:s') }}</span>
                                    Progress: {{ number_format($data->progress_proses, 0, ',', '.') }} dari {{ number_format($data->progress_total, 0, ',', '.') }} TPS ({{ number_format($data->persen_tps, 2, ',', '.') }}%)
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="datatable" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="5%">ID</th>
                                            <th width="21%">WILAYAH</th>
                                            <th width="16%">(01) ANIES - MUHAIMIN</th>
                                            <th width="16%">(02) PRABOWO - GIBRAN</th>
                                            <th width="16%">(03) GANJAR - MAHFUD</th>
                                            @if (Request::segment(6))
                                                <th width="13%">SUARA SAH</th>
                                                <th width="13%">PEMILIH</th>
                                            @else
                                                <th width="16%">Progress</th>
                                                <th width="10%">Update</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $data->tertinggi_01_count = 0;
                                            $data->tertinggi_02_count = 0;
                                            $data->tertinggi_03_count = 0;
                                            $data->lebih_dari_20_01_count = 0;
                                            $data->lebih_dari_20_02_count = 0;
                                            $data->lebih_dari_20_03_count = 0;
                                        @endphp
                                        @foreach ($data2 as $value)
                                            @php
                                                if (Request::segment(6)) {
                                                    $value->chart_total = $value->chart_01 + $value->chart_02 + $value->chart_03;
                                                    $value->persen_01 = hitung_persentase($value->chart_01, $value->chart_total);
                                                    $value->persen_02 = hitung_persentase($value->chart_02, $value->chart_total);
                                                    $value->persen_03 = hitung_persentase($value->chart_03, $value->chart_total);
                                                    $value->persen_suara = hitung_persentase($value->suara_sah, $value->suara_total);
                                                    $value->persen_dpt = hitung_persentase($value->pengguna_total_jumlah, $value->pemilih_dpt_jumlah);
                                                } else {
                                                    $value->suara_total = $value->chart_01 + $value->chart_02 + $value->chart_03;
                                                    $value->persen_01 = hitung_persentase($value->chart_01, $value->suara_total);
                                                    $value->persen_02 = hitung_persentase($value->chart_02, $value->suara_total);
                                                    $value->persen_03 = hitung_persentase($value->chart_03, $value->suara_total);
                                                    $value->persen_tps = hitung_persentase($value->progress_proses, $value->progress_total);
                                                }

                                                $value->tertinggi_01 = '';
                                                $value->tertinggi_02 = '';
                                                $value->tertinggi_03 = '';

                                                if ($value->chart_01 > $value->chart_02 && $value->chart_01 > $value->chart_03) {
                                                    $value->tertinggi_01 = 'bg-success text-white';
                                                    $data->tertinggi_01_count++;
                                                } elseif ($value->chart_02 > $value->chart_01 && $value->chart_02 > $value->chart_03) {
                                                    $value->tertinggi_02 = 'bg-success text-white';
                                                    $data->tertinggi_02_count++;
                                                } elseif ($value->chart_03 > $value->chart_01 && $value->chart_03 > $value->chart_02) {
                                                    $value->tertinggi_03 = 'bg-success text-white';
                                                    $data->tertinggi_03_count++;
                                                }

                                                if ($value->persen_01 > 20) {
                                                    $data->lebih_dari_20_01_count++;
                                                }
                                                if ($value->persen_02 > 20) {
                                                    $data->lebih_dari_20_02_count++;
                                                }
                                                if ($value->persen_03 > 20) {
                                                    $data->lebih_dari_20_03_count++;
                                                }

                                                $next_url = url('ppwp/hitung_suara');
                                                $next_url .= Request::segment(3) ? '/' . Request::segment(3) : '';
                                                $next_url .= Request::segment(4) ? '/' . Request::segment(4) : '';
                                                $next_url .= Request::segment(5) ? '/' . Request::segment(5) : '';
                                                $next_url .= Request::segment(6) ? '/' . Request::segment(6) : '';
                                            @endphp
                                            <tr>
                                                @if (Request::segment(3) == 99 && !Request::segment(4))
                                                    <td>{{ $value->kode }}</td>
                                                @else
                                                    <td>{{ $value->id }}</td>
                                                @endif
                                                <td>
                                                    @if (Request::segment(3) == 99 && Request::segment(4))
                                                        {{ $value->nama }}
                                                    @else
                                                        <a href="{{ $next_url . '/' . $value->id }}">{{ $value->nama }}</a>
                                                    @endif
                                                </td>
                                                <td class="{{ $value->tertinggi_01 }}">
                                                    {{ number_format($value->chart_01, 0, ',', '.') }}<br>
                                                    ({{ number_format($value->persen_01, 2, ',', '.') }}%)
                                                </td>
                                                <td class="{{ $value->tertinggi_02 }}">
                                                    {{ number_format($value->chart_02, 0, ',', '.') }}<br>
                                                    ({{ number_format($value->persen_02, 2, ',', '.') }}%)
                                                </td>
                                                <td class="{{ $value->tertinggi_03 }}">
                                                    {{ number_format($value->chart_03, 0, ',', '.') }}<br>
                                                    ({{ number_format($value->persen_03, 2, ',', '.') }}%)
                                                </td>
                                                @if (Request::segment(6))
                                                    <td>
                                                        <div class="progress">
                                                            <div class="progress-bar progress-bar-striped" style="width: {{ $value->persen_suara }}%">{{ number_format($value->persen_suara, 0, ',', '.') }}%</div>
                                                        </div>
                                                        {{ number_format($value->suara_sah, 0, ',', '.') }} dari {{ number_format($value->suara_total, 0, ',', '.') }}
                                                    </td>
                                                    <td>
                                                        <div class="progress">
                                                            <div class="progress-bar progress-bar-striped" style="width: {{ $value->persen_dpt }}%">{{ number_format($value->persen_dpt, 0, ',', '.') }}%</div>
                                                        </div>
                                                        {{ number_format($value->pengguna_total_jumlah, 0, ',', '.') }} dari {{ number_format($value->pemilih_dpt_jumlah, 0, ',', '.') }}
                                                    </td>
                                                @else
                                                    <td>
                                                        <div class="progress">
                                                            <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: {{ $value->persen_tps }}%">{{ number_format($value->persen_tps, 0, ',', '.') }}%</div>
                                                        </div>
                                                        {{ number_format($value->progress_proses, 0, ',', '.') }} dari {{ number_format($value->progress_total, 0, ',', '.') }} TPS
                                                    </td>
                                                    <td>{{ $value->updated_at->format('d/m/Y H:i:s') }}</td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>-</th>
                                            <th>JUMLAH</th>
                                            <th>
                                                {{ number_format($data->chart_01, 0, ',', '.') }} <br>
                                                ({{ number_format($data->persen_01, 2, ',', '.') }}%)
                                                @if (!Request::segment(3))
                                                    <br> Unggul di {{ $data->tertinggi_01_count }} Provinsi
                                                    <br> >20% {{ $data->lebih_dari_20_01_count }} Provinsi
                                                @endif
                                            </th>
                                            <th>
                                                {{ number_format($data->chart_02, 0, ',', '.') }} <br>
                                                ({{ number_format($data->persen_02, 2, ',', '.') }}%)
                                                @if (!Request::segment(3))
                                                    <br> Unggul di {{ $data->tertinggi_02_count }} Provinsi
                                                    <br> >20% {{ $data->lebih_dari_20_02_count }} Provinsi
                                                @endif
                                            </th>
                                            <th>
                                                {{ number_format($data->chart_03, 0, ',', '.') }} <br>
                                                ({{ number_format($data->persen_03, 2, ',', '.') }}%)
                                                @if (!Request::segment(3))
                                                    <br> Unggul di {{ $data->tertinggi_03_count }} Provinsi
                                                    <br> >20% {{ $data->lebih_dari_20_03_count }} Provinsi
                                                @endif
                                            </th>
                                            @if (Request::segment(6))
                                                <th>SUARA SAH</th>
                                                <th>PEMILIH</th>
                                            @else
                                                <th>Progress</th>
                                                <th>Update</th>
                                            @endif
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="text-center mt-2">
                                <div class="badge bg-primary rounded-pill">
                                    <span class="d-block d-md-inline">Update: {{ $data->updated_at->format('d M Y H:i:s') }}</span>
                                    Progress: {{ number_format($data->progress_proses, 0, ',', '.') }} dari {{ number_format($data->progress_total, 0, ',', '.') }} TPS ({{ number_format($data->persen_tps, 2, ',', '.') }}%)
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('plugin-scripts')
    @if (Request::segment(7))
        <script src="{{ asset('assets/extensions/fslightbox/fslightbox.js') }}"></script>
    @else
        <script src="{{ asset('assets/extensions/highcharts/highcharts.js') }}"></script>
        <script src="{{ asset('assets/extensions/datatables-net/jquery.dataTables.js') }}"></script>
        <script src="{{ asset('assets/extensions/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
        <script src="{{ asset('assets/extensions/datatables-net/responsive/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('assets/extensions/datatables-net/responsive/responsive.bootstrap5.min.js') }}"></script>
    @endif
@endpush

@push('script')
    @if (!Request::segment(7))
        <script>
            Highcharts.chart('chart', {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: ''
                },
                subtitle: {
                    text: 'Source: pemilu2024.kpu.go.id'
                },
                tooltip: {
                    pointFormat: '{series.name}<b>{point.percentage:.2f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name} ({point.percentage:.2f}%)</b><br>Perolehan Suara: {point.vote}',
                            style: {
                                color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                            }
                        }
                    }
                },
                series: [{
                    name: '',
                    colorByPoint: true,
                    data: [{
                        name: "(01) ANIES - MUHAIMIN",
                        color: "{{ $ppwp[0]->warna }}",
                        vote: "{{ number_format($data->chart_01, 0, ',', '.') }}",
                        y: {{ $data->persen_01 }}
                    }, {
                        name: "(02) PRABOWO - GIBRAN",
                        color: "{{ $ppwp[1]->warna }}",
                        vote: "{{ number_format($data->chart_02, 0, ',', '.') }}",
                        y: {{ $data->persen_02 }}
                    }, {
                        name: "(03) GANJAR - MAHFUD",
                        color: "{{ $ppwp[2]->warna }}",
                        vote: "{{ number_format($data->chart_03, 0, ',', '.') }}",
                        y: {{ $data->persen_03 }}
                    }]
                }]
            });

            $("#datatable").DataTable({
                "responsive": false
            });
        </script>
    @endif
@endpush
