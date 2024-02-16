@extends('layouts.app')

@section('title', 'Info Publik Pemilu 2024')

@push('plugin-styles')
    <link rel="stylesheet" href="{{ asset('assets/extensions/datatables-net-bs5/dataTables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/datatables-net/responsive/responsive.bootstrap5.css') }}">
@endpush

@section('content')
    <section class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-primary text-center">
                    <h4 class="card-title text-white">HASIL HITUNG SUARA PEMILU PRESIDEN & WAKIL PRESIDEN RI 2024</h4>
                </div>
                <div class="card-body">
                    <h5 class="text-center">
                        @if (Request::segment(3))
                            WILAYAH PEMILIHAN
                            @if (Request::segment(5))
                                <a href="{{ $kecamatan->url }}">KEC. {{ $kecamatan->nama }}</a> -
                            @endif
                            @if (Request::segment(4))
                                <a href="{{ $kabupaten->url }}">{{ strpos($kabupaten->nama, 'KOTA ') === false ? 'KAB. ' . $kabupaten->nama : $kabupaten->nama }}</a> -
                            @endif
                            <a href="{{ $provinsi->url }}">PROV. {{ $provinsi->nama }}</a>
                        @else
                            TINGKAT NASIONAL
                        @endif
                    </h5>

                    <div class="text-center mb-2">
                        <div class="badge bg-primary rounded-pill">
                            <span class="d-block d-md-inline">Update: {{ $data->updated_at->format('d M Y H:i:s') }}</span>
                            Progress: {{ number_format($data->progress_proses, 0, ',', '.') }} dari {{ number_format($data->progress_total, 0, ',', '.') }} TPS ({{ number_format($data->persen_tps, 2, '.', ',') }}%)
                        </div>
                    </div>

                    @if ($data->progress_proses)
                        {{-- <div class="d-flex justify-content-center align-items-center" style="height: 400px;">
                            <div id="chart" style="min-width: 310px; max-width: 600px; margin: 0 auto"></div>
                        </div> --}}
                        <div id="chart" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                    @else
                        <div class="alert alert-warning text-center">Data belum tersedia</div>
                    @endif

                    <div class="text-center my-2">
                        <div class="badge bg-primary rounded-pill">
                            <span class="d-block d-md-inline">Update: {{ $data->updated_at->format('d M Y H:i:s') }}</span>
                            Progress: {{ number_format($data->progress_proses, 0, ',', '.') }} dari {{ number_format($data->progress_total, 0, ',', '.') }} TPS ({{ number_format($data->persen_tps, 2, '.', ',') }}%)
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="datatable" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">ID</th>
                                    <th width="20%">WILAYAH</th>
                                    <th width="16%">(01) ANIES - MUHAIMIN</th>
                                    <th width="16%">(02) PRABOWO - GIBRAN</th>
                                    <th width="16%">(03) GANJAR - MAHFUD</th>
                                    <th width="17%">Progress</th>
                                    <th width="10%">Update</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data2 as $value)
                                    @php
                                        $value->suara_total = $value->chart_01 + $value->chart_02;
                                        $value->persen_01 = $value->suara_total ? ($value->chart_01 / $value->suara_total) * 100 : 0;
                                        $value->persen_02 = $value->suara_total ? ($value->chart_02 / $value->suara_total) * 100 : 0;
                                        $value->persen_tps = $value->progress_total ? ($value->progress_proses / $value->progress_total) * 100 : 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $value->id }}</td>
                                        <td>
                                            <a href="{{ $next_url . '/' . $value->id }}">{{ $value->nama }}</a>
                                        </td>
                                        <td>
                                            {{ number_format($value->chart_01, 0, ',', '.') }}<br>
                                            ({{ number_format($value->persen_01, 0, ',', '.') }}%)
                                        </td>
                                        <td>
                                            {{ number_format($value->chart_02, 0, ',', '.') }}<br>
                                            ({{ number_format($value->persen_02, 0, ',', '.') }}%)
                                        </td>
                                        <td>
                                            {{ number_format($value->chart_03, 0, ',', '.') }}<br>
                                            ({{ number_format($value->persen_03, 0, ',', '.') }}%)
                                        </td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: {{ $value->persen_tps }}%">{{ number_format($value->persen_tps, 0, '.', ',') }}%</div>
                                            </div>
                                            {{ number_format($value->progress_proses, 0, ',', '.') }} dari {{ number_format($value->progress_total, 0, ',', '.') }} TPS
                                        </td>
                                        <td>{{ $value->updated_at->format('d/m/Y H:i:s') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>-</th>
                                    <th>JUMLAH</th>
                                    <th>
                                        {{ number_format($data->chart_01, 0, ',', '.') }}<br>
                                        ({{ number_format($data->persen_01, 2, '.', ',') }}%)
                                    </th>
                                    <th>
                                        {{ number_format($data->chart_02, 0, ',', '.') }}<br>
                                        ({{ number_format($data->persen_02, 2, '.', ',') }}%)
                                    </th>
                                    <th>
                                        {{ number_format($data->chart_03, 0, ',', '.') }}<br>
                                        ({{ number_format($data->persen_03, 2, '.', ',') }}%)
                                    </th>
                                    <th>Progress</th>
                                    <th>Update</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="text-center mt-2">
                        <div class="badge bg-primary rounded-pill">
                            <span class="d-block d-md-inline">Update: {{ $data->updated_at->format('d M Y H:i:s') }}</span>
                            Progress: {{ number_format($data->progress_proses, 0, ',', '.') }} dari {{ number_format($data->progress_total, 0, ',', '.') }} TPS ({{ number_format($data->persen_tps, 2, '.', ',') }}%)
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/extensions/highcharts/highcharts.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables-net/responsive/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables-net/responsive/responsive.bootstrap5.min.js') }}"></script>
@endpush

@push('script')
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
@endpush
