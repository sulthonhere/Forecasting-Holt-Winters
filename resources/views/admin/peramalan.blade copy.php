@extends('template.layout')
@section('title', 'Peramalan')

@push('style')
<link rel="stylesheet" href="assets/extensions/simple-datatables/style.css">
<link rel="stylesheet" href="./assets/compiled/css/table-datatable.css">

<style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    td {
        text-align: right;
    }
</style>
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
                    <h5>Tabel Daftar Peramalan - Paving Block Holland 6 cm</h5>
                    <p>Riwayat peramalan dapat dilihat</p>
                </div>

                <div class="modal-success me-1 mb-1 d-inline-block">
                    <!-- Create Modal Trigger -->
                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#success">
                        <i data-feather="edit"></i><span> Tambah</span>
                    </button>

                    <!--Create Modal -->
                    <div class="modal fade text-left" id="success" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel110" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
                            role="document">

                            <div class="modal-content">
                                <div class="modal-header bg-success">
                                    <h5 class="modal-title white" id="myModalLabel110">Tambah Data Penjualan</h5>
                                    <button type="button" class="close" data-bs-dismiss="modal"
                                        aria-label="Close">
                                        <i data-feather="x"></i>
                                    </button>
                                </div>

                                <form action="{{ route('penjualan.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <input type="hidden" name="created_by" value="{{ Auth::user()->id }}">
                                        <div class="form-group has-icon-left">
                                            <label for="waktu_penjualan">Waktu Penjualan</label>
                                            <div class="position-relative">
                                                <input type="month" name="waktu_penjualan" class="form-control" required>
                                                <div class="form-control-icon">
                                                    <i class="fa fa-calendar-alt"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group has-icon-left">
                                            <label for="jumlah">Jumlah Penjualan</label>
                                            <div class="position-relative">
                                                <input type="number" class="form-control" name="jumlah" placeholder="- jumlah -" min="1" required>
                                                <div class="form-control-icon">
                                                    <i class="fa fa-cart-plus"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-success">Tambah</button>
                                    </div>
                                </form>

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
                            <th class="text-center">Tanggal</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>
    </section>

</div>
@if (session('message'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session("message") }}',
                showConfirmButton: true,
            });
        });
    </script>
@endif
@endsection

@push('script')

<script src="assets/extensions/simple-datatables/umd/simple-datatables.js"></script>
<script src="assets/static/js/pages/simple-datatables.js"></script>
@endpush