@extends('template.layout')
@section('title', 'Penjualan')

@push('style')
<link rel="stylesheet" href="assets/extensions/simple-datatables/style.css">
<link rel="stylesheet" href="./assets/compiled/css/table-datatable.css">

<style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
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
                <h3>Menu Pengguna</h3>
            </div>

            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Pengguna</li>
                    </ol>
                </nav>
            </div>

        </div>
    </div>


    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="card-title mt-2">
                    <h5>Tabel Daftar Pengguna - Sistem Informasi</h5>
                    <p>Manajemen pengguna sistem informasi</p>
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
                                    <h5 class="modal-title white" id="myModalLabel110">Tambah Data Pengguna</h5>
                                    <button type="button" class="close" data-bs-dismiss="modal"
                                        aria-label="Close">
                                        <i data-feather="x"></i>
                                    </button>
                                </div>

                                <form action="{{ route('pengguna.store') }}" method="POST">
                                    @csrf

                                    <div class="modal-body">
                                        <input type="hidden" name="created_by" value="{{ Auth::user()->id}}">
                                        <input type="hidden" name="password" value="baru123">
                                        <div class="form-group has-icon-left">
                                            <label for="name">Nama Lengkap</label>
                                            <div class="position-relative">
                                                <input type="text" name="name" class="form-control" 
                                                    placeholder="---" required>
                                                <div class="form-control-icon">
                                                    <i class="bi bi-person"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group has-icon-left">
                                            <label for="email">Email</label>
                                            <div class="position-relative">
                                                <input type="email" name="email" class="form-control" 
                                                    placeholder="---" required>
                                                <div class="form-control-icon">
                                                    <i class="bi bi-envelope"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group has-icon-left">
                                            <label for="jabatan">Jabatan</label>
                                            <div class="position-relative">
                                                <input type="text" name="jabatan" class="form-control" 
                                                    placeholder="---" required>
                                                <div class="form-control-icon">
                                                    <i class="fa fa-user-tie"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="role">Hak Akses</label>
                                            <select class="form-select" name="role">
                                                <option selected disabled>-- pilih --</option>
                                                <option value="2">Manager</option>
                                                <option value="3">Staff</option>
                                            </select>
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
                            <th class="text-center">Nama Lengkap</th>
                            <th class="text-center">Jabatan</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Hak Akses</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody style="vertical-align: middle">
                        @foreach ($pengguna as $row)
                        <tr>
                            <th class="text-center">{{ $loop->index + 1 }}</th>
                            <td>{{ $row->name }}</td>
                            <td>{{ $row->jabatan }}</td>
                            <td>{{ $row->email }}</td>
                            <td class="text-center">
                                @if ($row->role == 2)
                                <span class="badge bg-primary">Manager</span>
                                @elseif ($row->role == 3)
                                <span class="badge bg-success">Staff</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <!-- Edit Modal Trigger -->
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $row->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <a href="{{ route('pengguna.destroy', $row->id) }}" class="btn btn-sm btn-danger" data-confirm-delete="true">
                                    <i class="fa fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade text-left" id="editModal{{ $row->id }}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">

                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h5 class="modal-title white" id="myModalLabel110">Edit Data Penjualan</h5>
                                        <button type="button" class="close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <i data-feather="x"></i>
                                        </button>
                                    </div>

                                    <form action="{{ route('pengguna.update', $row->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="modal-body">
                                            <input type="hidden" name="updated_by" value="{{ Auth::user()->id }}">
                                            <div class="form-group has-icon-left">
                                                <label for="name">Nama Lengkap</label>
                                                <div class="position-relative">
                                                    <input type="text" name="name" class="form-control" 
                                                        placeholder="---" value="{{ $row->name }}" required>
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-person"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group has-icon-left">
                                                <label for="email">Email</label>
                                                <div class="position-relative">
                                                    <input type="email" name="email" class="form-control" 
                                                        placeholder="---" value="{{ $row->email }}" required>
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-envelope"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group has-icon-left">
                                                <label for="jabatan">Jabatan</label>
                                                <div class="position-relative">
                                                    <input type="text" name="jabatan" class="form-control" 
                                                        placeholder="---" value="{{ $row->jabatan }}" required>
                                                    <div class="form-control-icon">
                                                        <i class="fa fa-user-tie"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="role">Hak Akses</label>
                                                <select class="form-select" name="role">
                                                    <option selected disabled>-- pilih --</option>
                                                    <option value="2" {{ $row->role == 2 ? 'selected' : '' }}>Manager</option>
                                                    <option value="3" {{ $row->role == 3 ? 'selected' : '' }}>Staff</option>
                                                </select>
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

                        @endforeach

                    </tbody>
                </table>
            </div>

        </div>
    </section>

</div>
@endsection

@push('script')

<script src="assets/extensions/simple-datatables/umd/simple-datatables.js"></script>
<script src="assets/static/js/pages/simple-datatables.js"></script>
@endpush