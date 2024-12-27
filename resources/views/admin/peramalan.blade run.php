@extends('template.layout')

@section('content')
<div class="container">
    <h1>Peramalan Penjualan (Holt-Winters)</h1>
    
    <!-- Form Peramalan -->
    <form action="{{ route('peramalan.forecast') }}" method="POST">
        @csrf
        
        <div class="row">
            <div class="col-md-4">
                <label for="alpha">Alpha</i></label>
                <div class="input-group mb-3">
                    <span class="input-group-text">α</span>
                    <input type="number" step="0.01" name="alpha" class="form-control" 
                            min="0" max="1" placeholder="---" required>
                </div>
            </div>
            <div class="col-md-4">
                <label for="beta">Beta</i></label>
                <div class="input-group mb-3">
                    <span class="input-group-text">β</span>
                    <input type="number" step="0.01" name="beta" class="form-control" 
                            min="0" max="1" placeholder="---" required>
                </div>
            </div>
            <div class="col-md-4">
                <label for="gamma">Gamma</i></label>
                <div class="input-group mb-3">
                    <span class="input-group-text">β</span>
                    <input type="number" step="0.01" name="gamma" class="form-control" 
                            min="0" max="1" placeholder="---" required>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="input-group col">
                <label class="input-group-text" for="periode_awal"><i class="fa fa-calendar-alt"></i></label>
                <select class="form-select" name="periode_awal" aria-label="Filter select">
                    <option selected disabled>- pilih -</option>
                    @if (!empty($availableDates))
                        @foreach($availableDates as $date)
                            <option value="{{ $date }}">{{ \Carbon\Carbon::parse($date)->translatedFormat('F Y') }}</option>
                        @endforeach 
                    @endif
                </select>
            </div>
            <div class="input-group col">
                <label class="input-group-text" for="periode_akhir"><i class="fa fa-calendar-alt"></i></label>
                <select class="form-select" name="periode_akhir" aria-label="Filter select">
                    <option selected disabled>- pilih -</option>
                    @if (!empty($availableDates))
                        @foreach($availableDates as $date)
                            <option value="{{ $date }}">{{ \Carbon\Carbon::parse($date)->translatedFormat('F Y') }}</option>
                        @endforeach 
                    @endif
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Lakukan Peramalan</button>
    </form>

    <!-- Tabel Riwayat Peramalan -->
    <h2 class="mt-5">Riwayat Peramalan</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Periode</th>
                <th>Penjualan</th>
                <th>Level</th>
                <th>Trend</th>
                <th>Seasonal</th>
                <th>Forecast</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($forecastData))
                @foreach($forecastData as $row)
                    <tr style="text-align: right;">
                        <td>{{ $loop->index + 1 }}</td>
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
    
    <div>
        @if (!empty($forecastData))
            <h4>MAD : {{ number_format($mad, 2) }}</h4>
            <h4>MSE : {{ number_format($mse, 2) }}</h4>
            <h4>MAPE : {{ number_format($mape, 2) }}</h4>
        @endif
    </div>

    <div class="card">
        <div class="card-header">
            <h4>Visualisasi Forecast</h4>
            <p>Paving block holland 6 cm</p>
        </div>
        <div class="card-body px-5 pb-3">
            <div id="chart"></div>
        </div>
    </div>
</div>
@endsection

@if (!empty($forecastData))
    @push('script')
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
                        type: 'line'
                    },
                series: [{
                        name: 'Penjualan',
                        data: jumlahPenjualan
                    }, {
                        name: 'Forecast',
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
        </script>

        <script src="assets/extensions/dayjs/dayjs.min.js"></script>
        <script src="assets/extensions/apexcharts/apexcharts.min.js"></script>
    @endpush
@endif