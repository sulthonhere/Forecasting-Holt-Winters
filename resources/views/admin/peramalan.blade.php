@extends('template.layout')
@section('title', 'Peramalan')

@push('style')
<link rel="stylesheet" href="assets/extensions/simple-datatables/style.css">
<link rel="stylesheet" href="./assets/compiled/css/table-datatable.css">
@endpush

@section('content')
<header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
        <i class="bi bi-justify fs-3"></i>
    </a>
</header>

<div class="page-heading">
    <div class="page-title">

        <div class="row mb-4">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Menu Peramalan</h3>
            </div>

            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Peramalan</li>
                    </ol>
                </nav>
            </div>

        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="card-title mt-2">
                    <h5>Tabel proses dan nilai Peramalan - Paving Block Holland 6 cm</h5>
                    <p>Proses perhitungan peramalan</p>
                </div>

                <div class="modal-success">
                    <!-- Create Modal Trigger -->
                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#success"
                        @if (!empty($forecastData) || empty($status_data_penjualan))
                            disabled
                        @endif
                    >
                        <i data-feather="edit"></i><span> Ramalkan</span>
                    </button>

                    <!--Create Modal -->
                    <div class="modal modal-lg fade text-left" id="success" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel110" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable"
                            role="document">

                            <div class="modal-content">
                                <div class="modal-header bg-success">
                                    <h5 class="modal-title modal-xl white" id="myModalLabel110">Pengaturan Peramalan</h5>
                                    <button type="button" class="close" data-bs-dismiss="modal"
                                        aria-label="Close">
                                        <i data-feather="x"></i>
                                    </button>
                                </div>

                                <div class="card-body">
                                    <form action="{{ route('peramalan.forecast') }}" method="POST">
                                        @csrf
                                        
                                        <div class="form-check mb-4">
                                            <div class="checkbox">
                                                <label for="checkbox1">Peramalan otomatis</label>
                                                <input type="checkbox" id="toggle-checkbox" class="form-check-input">
                                            </div>
                                        </div>

                                        <div id="toggle-input">
                                            
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="alpha">Alpha</i></label>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text">α</span>
                                                        <input type="number" step="0.01" name="alpha" class="form-control" 
                                                                min="0" max="1" placeholder="---" id="input-number" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="beta">Beta</i></label>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text">β</span>
                                                        <input type="number" step="0.01" name="beta" class="form-control" 
                                                                min="0" max="1" placeholder="---" id="input-number" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="gamma">Gamma</i></label>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text">γ</span>
                                                        <input type="number" step="0.01" name="gamma" class="form-control" 
                                                                min="0" max="1" placeholder="---" id="input-number" required>
                                                    </div>
                                                </div>
    
                                            </div>

                                        </div>

                                        <div class="row mb-5">
                                            <div class="col-md-4">
                                                <label for="">Periode Awal</label>
                                                <div class="input-group">
                                                    <label class="input-group-text" for="periode_awal"><i class="fa fa-calendar-alt"></i></label>
                                                    <select class="form-select" name="periode_awal" id="mySelect">
                                                        <option value="" selected disabled>- pilih -</option>
                                                        @if (!empty($periodePenjualanAwal))
                                                            @foreach($periodePenjualanAwal as $date)
                                                                <option value="{{ $date }}">{{ \Carbon\Carbon::parse($date)->translatedFormat('F Y') }}</option>
                                                            @endforeach 
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="">Periode Akhir</label>
                                                <div class="input-group">
                                                    <label class="input-group-text" for="periode_akhir"><i class="fa fa-calendar-alt"></i></label>
                                                    <select class="form-select" name="periode_akhir" id="mySelect">
                                                        <option value="" selected disabled>- pilih -</option>
                                                        @if (!empty($periodePenjualanAkhir))
                                                            @foreach($periodePenjualanAkhir as $date)
                                                                <option value="{{ $date }}">{{ \Carbon\Carbon::parse($date)->translatedFormat('F Y') }}</option>
                                                            @endforeach 
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="">Panjang Periode Peramalan</label>
                                                <div class="input-group">
                                                    <label class="input-group-text" for="bulan_peramalan"><i class="fa fa-chart-bar"></i></label>
                                                    <select class="form-select" name="bulan_peramalan" id="mySelect">
                                                        <option value="" selected disabled>- pilih -</option>
                                                        <option value="1">1 Bulan</option>
                                                        <option value="2">2 Bulan</option>
                                                        <option value="3">3 Bulan</option>
                                                        <option value="4">4 Bulan</option>
                                                        <option value="5">5 Bulan</option>
                                                        <option value="6">6 Bulan</option>
                                                        <option value="7">7 Bulan</option>
                                                        <option value="8">8 Bulan</option>
                                                        <option value="9">9 Bulan</option>
                                                        <option value="10">10 Bulan</option>
                                                        <option value="11">11 Bulan</option>
                                                        <option value="12">12 Bulan</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-success">Simpan</button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card-body">
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Periode</th>
                            <th class="text-center">Penjualan</th>
                            <th class="text-center">Level</th>
                            <th class="text-center">Trend</th>
                            <th class="text-center">Seasonal</th>
                            <th class="text-center">Forecast</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($forecastData))
                            @foreach($forecastData as $row)
                                <tr style="text-align: right;">
                                    <td class="text-center">{{ $loop->index + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($row['periode'])->translatedFormat('F Y') }}</td>
                                    <td>{{ $row['penjualan'] !== null ? $row['penjualan'] : '-' }}</td>
                                    <td>{{ $row['level'] !== null ? number_format($row['level'], 2) : '-' }}</td>
                                    <td>{{ $row['trend'] !== null ? number_format($row['trend'], 2) : '-' }}</td>
                                    <td>{{ $row['seasonal'] !== null ? number_format($row['seasonal'], 2) : '-' }}</td>
                                    <td>{{ $row['forecast'] !== null ? number_format($row['forecast'], 2) : '-' }}</td>
                                </tr>
                            @endforeach        
                        @endif 
                    </tbody>
                </table>
            </div>

        </div>
    </section>

    <div class="card">
        <div class="card-header">
            <h4>Visualisasi Forecast</h4>
            <p>Parameter dan rincian peramalan</p>

            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="input-group mb-3">
                        <span class="input-group-text">Periode Awal</span>
                        <input type="text" class="form-control" disabled style="text-align: right;"
                            @if (!empty($forecastData)) value="{{ $periodeAwalData }}" @endif
                        >
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text">Periode Akhir</span>
                        <input type="text" class="form-control" disabled style="text-align: right;"
                            @if (!empty($forecastData)) value="{{ $periodeAkhirData }}" @endif
                        >
                    </div>

                    <div class="input-group mb-3">
                        <a href="{{ route('peramalan.index') }}" class="btn btn-primary 
                            @if (empty($forecastData))
                                disabled
                            @endif
                        ">
                            <i class="fa fa-undo-alt"></i><span> Reset Forecast Data</span>
                        </a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="input-group mb-3">
                        <span class="input-group-text">α</span>
                        <input type="number" class="form-control"  style="text-align: right;" disabled
                            @if (!empty($forecastData)) value="{{ $alpha }}" @endif
                        >
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text">β</span>
                        <input type="number" class="form-control"  style="text-align: right;" disabled
                            @if (!empty($forecastData)) value="{{ $beta }}" @endif
                        >
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text">γ</span>
                        <input type="number" class="form-control"  style="text-align: right;" disabled
                            @if (!empty($forecastData)) value="{{ $gamma }}" @endif
                        >
                    </div>
                    
                </div>

                <div class="col-md-4">
                    <div class="input-group mb-3">
                        <span class="input-group-text">MAD</span>
                        <input type="text" class="form-control"  style="text-align: right;" disabled
                            @if (!empty($forecastData)) value="{{ $madData }}" @endif
                        >
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text">MSE .</span>
                        <input type="text" class="form-control"  style="text-align: right;" disabled
                            @if (!empty($forecastData)) value="{{ $mseData }}" @endif
                        >
                    </div>
                    
                    <div class="input-group mb-3">
                        <span class="input-group-text">MAPE</span>
                        <input type="text" class="form-control"  style="text-align: right;" disabled
                            @if (!empty($forecastData)) value="{{ $mapeData }} %" @endif
                        >
                    </div>
                </div>

            </div>
 
        </div>
        <div class="card-body px-5 pb-3">
            <div id="chart"></div>
        </div>
    </div>
</div>
@endsection

@push('script')
    @if (!empty($forecastData))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var data = @json($forecastData); // Assuming $forecastData is passed from controller

                    // Mengambil array waktu_penjualan dan jumlah dari data penjualan
                    // var waktuPenjualan = data.map(function(item) {
                    //     return item.periode;
                    // });
                    // var waktuPenjualan = data.map(item => {
                    //     var date = new Date(item.periode);
                    //     return ('0' + (date.getMonth() + 1)).slice(-2) + '-' + date.getFullYear();
                    // });

                    var options = { month: 'long', year: 'numeric' }; // Format: "January 2023"
                    var waktuPenjualan = data.map(item => {
                        var date = new Date(item.periode);
                        return date.toLocaleDateString('en-US', options); // Menghasilkan format "Month Year"
                    });

                    var jumlahPenjualan = data.map(function(item) {
                        return item.penjualan;
                    });

                    var forecastPenjualan = data.map(function(item) {
                        return item.forecast;
                    });

                    var options = {
                        chart: {
                            type: 'line',
                            height: 500
                        },
                    series: [{
                            name: 'Penjualan',
                            data: jumlahPenjualan
                        }, {
                            name: 'Peramalan',
                            data: forecastPenjualan,
                        }],
                        xaxis: {
                            categories: waktuPenjualan,
                            labels: {
                                rotate: -60,
                                style: {
                                    fontSize: '8px'
                                }
                            }
                        },
                        yaxis: {
                            labels: {
                                formatter: function(value) {
                                    return value.toLocaleString(); // Menambahkan pemisah ribuan
                                }
                            }
                        },
                        colors: ['#0000FF', '#FF0000'],
                    };

                    var chart = new ApexCharts(document.querySelector("#chart"), options);
                    chart.render();
                });

                document.getElementById('chart').style.maxHeight = '100px'; // Atur max-height sesuai kebutuhan
            </script>

            <script src="assets/extensions/dayjs/dayjs.min.js"></script>
            <script src="assets/extensions/apexcharts/apexcharts.min.js"></script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Masukkan pengaturan peramalan dengan benar',
                showConfirmButton: true
            });
        </script>
        
    @endif

    @if (empty($status_data_penjualan))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Peramalan Tidak Dapat Dilakukan',
                text: 'Data periode penjualan minimal 3 tahun',
                showConfirmButton: true
            });
        </script>
    @endif

    <script>
        // Di dalam script Anda
        document.getElementById('toggle-checkbox').addEventListener('change', function() {
            var inputNumberGroup = document.getElementById('toggle-input');
            if (this.checked) {
                inputNumberGroup.style.display = 'none';
            } else {
                inputNumberGroup.style.display = 'block';
            }
        });

        // Di dalam script Anda
        document.getElementById('toggle-checkbox').addEventListener('change', function() {
            var inputNumberGroup = document.getElementById('toggle-input');
            var specificInputs = inputNumberGroup.querySelectorAll('#input-number');
            if (this.checked) {
                inputNumberGroup.style.display = 'none';
                specificInputs.forEach(function(inputNumber) {
                    inputNumber.value = null;
                    inputNumber.required = false;
                });
            } else {
                inputNumberGroup.style.display = 'block';
                // specificInputs.forEach(function(inputNumber) {
                //     inputNumber.required = true;
                // });
            }
        });
    </script>

<script src="assets/extensions/parsleyjs/parsley.min.js"></script>
<script src="assets/static/js/pages/parsley.js"></script>

<script src="assets/extensions/simple-datatables/umd/simple-datatables.js"></script>
<script src="assets/static/js/pages/simple-datatables.js"></script>
@endpush