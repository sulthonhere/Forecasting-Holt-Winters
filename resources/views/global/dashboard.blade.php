@extends('template.layout')
@section('title', 'Dashboard')

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
                <h3>Menu Dashboard</h3>
            </div>

            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
            </div>

        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="row">

                    <div class="col-6 col-lg-6 col-md-6">
                        <div class="card">

                            <div class="card-body px-4 py-4-5">
                                <div class="d-flex align-items-center px-5">
                                    <div class="avatar avatar-xl">
                                        <img src="./assets/compiled/jpg/4.jpg" alt="Face 1">
                                    </div>
                                    <div class="ms-3">
                                        <h5 class="font-bold">
                                            @if (Auth::user()->role == 1)
                                                Admin
                                            @elseif (Auth::user()->role == 2)
                                                Manager                                                
                                            @else
                                                Staff
                                            @endif
                                             - {{ Auth::user()->name }}
                                        </h5>
                                        <h6 class="text-muted mb-0">{{ Auth::user()->email }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">

                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon purple mb-2">
                                            <i class="iconly-boldShow"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Jumlah Manager</h6>
                                        <h6 class="font-extrabold mb-0">{{ $jumlahManager}}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">

                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon blue mb-2">
                                            <i class="iconly-boldProfile"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Jumlah Staff</h6>
                                        <h6 class="font-extrabold mb-0">{{ $jumlahStaff}}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4>Visualisasi Data Penjualan</h4>
                <p>Paving block holland 6 cm</p>
            </div>
            <div class="card-body">
                <div id="chart"></div>
            </div>
            
    </section>
</div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var data = @json($penjualan);

            var jumlahPenjualan = data.map(function(item) {
                return item.jumlah;
            });
            
            var options = { month: 'long', year: 'numeric' }; // Format: "January 2023"
            var waktuPenjualan = data.map(item => {
                var date = new Date(item.waktu_penjualan);
                return date.toLocaleDateString('en-US', options); // Menghasilkan format "Month Year"
            });

            // Fungsi untuk menghitung regresi linier (trendline)
            function calculateLinearTrendline(data) {
                var n = data.length;
                var sumX = 0,
                    sumY = 0,
                    sumXY = 0,
                    sumXX = 0;

                for (var i = 0; i < n; i++) {
                    sumX += i;
                    sumY += Number(data[i]);
                    sumXY += i * Number(data[i]);
                    sumXX += i * i;
                }

                var slope = (n * sumXY - sumX * sumY) / (n * sumXX - sumX * sumX);
                var intercept = (sumY - slope * sumX) / n;

                var trendline = [];
                for (var i = 0; i < n; i++) {
                    trendline.push(slope * i + intercept);
                }

                return trendline;
            }

            var trendLineData = calculateLinearTrendline(jumlahPenjualan);
            var roundedTrendLineData = trendLineData.map(function(value) {
                return Math.round(value);
            });

            var options = {
                chart: {
                    type: 'line',
                    height: 500,
                },
                series: [
                    {
                        name: 'Penjualan',
                        data: jumlahPenjualan
                    }, 
                    {
                        name: 'Trendline',
                        data: roundedTrendLineData
                    }
                ],
                xaxis: {
                    categories: waktuPenjualan,
                    labels: {
                        rotate: -60,
                        style: {
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function(value) {
                            return value.toLocaleString(); 
                        }
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        });
    </script>

    <script src="assets/extensions/dayjs/dayjs.min.js"></script>
    <script src="assets/extensions/apexcharts/apexcharts.min.js"></script>
@endpush