@extends('template.layout')
@section('title', 'Profil')

@section('content')
<header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
        <i class="bi bi-justify fs-3"></i>
    </a>
</header>

<div class="page-heading mb-4">
    <div class="page-title">

        <div class="row mb-4">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Menu Profil</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Profil</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-body mt-3">
                        <div class="d-flex justify-content-center align-items-center flex-column">
                            <div class="avatar avatar-2xl">
                                <img src="./assets/compiled/jpg/4.jpg" alt="Avatar">
                            </div>

                            <h3 class="mt-3">{{ Auth::user()->name }}</h3>
                            <p class="text-small">{{ Auth::user()->jabatan }}</p>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-8">
                <div class="card">

                    <div class="card-body">
                        <div class="card-title">
                            <div class="row">
                                <div class="col-6">
                                    <h5>Data Personal Pengguna</h5>
                                    <p>Karyawan PT ASD</p>
                                </div>
                                <div class="col-6">
                                    <div class="row justify-content-around">
                                        <!-- Edit Personal Data Modal Trigger -->
                                        <button class="btn btn-sm btn-primary col-5" data-bs-toggle="modal" data-bs-target="#editPersonalDataModal{{ Auth::user()->id }}">
                                            <i class="fa fa-id-card"></i><span> Ubah Data</span>
                                        </button>
                                        <!-- Edit Password Modal Trigger -->
                                        <button class="btn btn-sm btn-warning col-5" data-bs-toggle="modal" data-bs-target="#editPasswordModal">
                                            <i class="fa fa-shield-alt"></i><span> Ubah Password</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-6">
                                <div class="form-group has-icon-left">
                                    <label for="name">Nama Lengkap</label>
                                    <div class="position-relative">
                                        <input type="text" name="name" class="form-control"
                                            placeholder="---" value="{{ Auth::user()->name }}" disabled>
                                        <div class="form-control-icon">
                                            <i class="bi bi-person"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group has-icon-left">
                                    <label for="role" class="form-label">Hak Akses</label>
                                    <div class="position-relative">
                                        <input type="text" role="role" class="form-control" disabled
                                            @if (Auth::user()->role == 1)
                                        value="Admin"
                                        @elseif (Auth::user()->role == 2)
                                        value="Manager"
                                        @else
                                        value="Staff"
                                        @endif
                                        >
                                        <div class="form-control-icon">
                                            <i class="fa fa-code-branch"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group has-icon-left">
                                    <label for="email">Email</label>
                                    <div class="position-relative">
                                        <input type="email" name="email" class="form-control" value="{{ Auth::user()->email }}" disabled>
                                        <div class="form-control-icon">
                                            <i class="bi bi-envelope"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group has-icon-left">
                                    <label for="jabatan" class="form-label">Jabatan</label>
                                    <div class="position-relative">
                                        <input type="text" name="jabatan" class="form-control" value="{{ Auth::user()->jabatan }}" disabled>
                                        <div class="form-control-icon">
                                            <i class="fa fa-user-tie"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

                </div>

            </div>
        </div>

        <!-- Edit personal Data Modal -->
        <div class="modal fade text-left" id="editPersonalDataModal{{ Auth::user()->id }}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title white" id="myModalLabel110">Ubah Data Pribadi</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"
                            aria-label="Close">
                            <i data-feather="x"></i>
                        </button>
                    </div>
        
                    <form action="{{ route('profil.update', Auth::user()->id) }}" method="POST">
                        @csrf
                        @method('PUT')
        
                        <div class="modal-body">
                            <input type="hidden" name="updated_by" value="{{ Auth::user()->id }}">
                            <div class="form-group">
                                <div class="form-group has-icon-left">
                                    <label for="name">Nama Lengkap</label>
                                    <div class="position-relative">
                                        <input type="text" name="name" class="form-control"
                                            placeholder="---" value="{{ Auth::user()->name }}" required>
                                        <div class="form-control-icon">
                                            <i class="bi bi-person"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group has-icon-left">
                                    <label for="email">Email</label>
                                    <div class="position-relative">
                                        <input type="email" name="email" class="form-control"
                                            placeholder="---" value="{{ Auth::user()->email }}" required>
                                        <div class="form-control-icon">
                                            <i class="bi bi-envelope"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group has-icon-left">
                                    <label for="jabatan">Jabatan</label>
                                    <div class="position-relative">
                                        <input type="text" name="jabatan" class="form-control"
                                            placeholder="---" value="{{ Auth::user()->jabatan }}" required>
                                        <div class="form-control-icon">
                                            <i class="fa fa-user-tie"></i>
                                        </div>
                                    </div>
                                </div>
        
                            </div>
        
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
        
                    </form>
                </div>
        
            </div>
        </div>
        
    </section>

    <!-- Edit Password Modal -->
    <!-- Modal Ubah Password -->
<div class="modal fade" id="editPasswordModal" tabindex="-1" aria-labelledby="ubahPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ubahPasswordModalLabel">Ubah Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('profil.update', Auth::user()->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label for="current_password">Password Lama</label>
                        </div>
                        <div class="col-md-8 input-group mb-3">
                            <input type="password" id="current_password" name="current_password" class="form-control"
                                placeholder="---">
                            <span class="input-group-text bg-transparent"><i class="fa fa-eye-slash"
                                    id="toggle-pw" style="cursor: pointer;"></i></span>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label for="new_password">Password Baru</label>
                        </div>
                        <div class="col-md-8 input-group mb-3">
                            <input type="password" id="new_password" name="new_password" class="form-control"
                                placeholder="---">
                            <span class="input-group-text bg-transparent"><i class="fa fa-eye-slash"
                                    id="toggle-pw2" style="cursor: pointer;"></i></span>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label for="confirm_new_password">Konfirmasi Password Baru</label>
                        </div>
                        <div class="col-md-6 input-group mb-3">
                            <input type="password" id="confirm_new_password" name="confirm_new_password" class="form-control"
                                placeholder="---">
                            <span class="input-group-text bg-transparent"><i class="fa fa-eye-slash"
                                    id="toggle-pw3" style="cursor: pointer;"></i></span>
                        </div>
                    </div>
                        
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Simpan</button>
                </div>
            </form>
            
        </div>
    </div>
</div>

</div>
@endsection

@push('script')
<script>
    let pw = document.getElementById("current_password");
    let pw2 = document.getElementById("new_password");
    let pw3 = document.getElementById("confirm_new_password");
    let eye = document.getElementById("toggle-pw");
    let eye2 = document.getElementById("toggle-pw2");
    let eye3 = document.getElementById("toggle-pw3");

    eye.onclick = function() {
        if (pw.type == "password") {
            pw.type = "text";
            eye.className = "fa fa-eye";
        } else {
            pw.type = "password";
            eye.className = "fa fa-eye-slash";
        }
    }
    eye2.onclick = function() {
        if (pw2.type == "password") {
            pw2.type = "text";
            eye2.className = "fa fa-eye";
        } else {
            pw2.type = "password";
            eye2.className = "fa fa-eye-slash";
        }
    }
    eye3.onclick = function() {
        if (pw3.type == "password") {
            pw3.type = "text";
            eye3.className = "fa fa-eye";
        } else {
            pw3.type = "password";
            eye3.className = "fa fa-eye-slash";
        }
    }
</script>
@endpush
