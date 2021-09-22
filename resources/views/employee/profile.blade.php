@extends('layouts.employee.app')

@section('content')
    <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
    </header>

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <div class="row">
                        <div class="col">
                            <h3>Profil Pegawai</h3>
                        </div>
                    </div>
                    <p class="text-subtitle text-muted">Profil Diri & Data Pegawai</p>
                </div>
            </div>
        </div>

        <!-- Basic Horizontal form layout section start -->
        <section id="basic-horizontal-layouts">
            <div class="row match-height">
                <div class="col-md-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Profil Diri</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <form class="form form-horizontal">
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Nama Lengkap</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="text" id="name" class="form-control"
                                                    name="name" value="{{ $user->name }}" disabled>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Jenis Kelamin</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                @if ($user->gender == 'male')
                                                    <input type="text" id="gender" class="form-control" name="gender" value="Laki-laki" disabled>
                                                @elseif ($user->gender == 'female')
                                                    <input type="text" id="gender" class="form-control" name="gender" value="Perempuan" disabled>
                                                @else
                                                    <input type="text" id="gender" class="form-control" name="gender" value="-" disabled>
                                                @endif
                                            </div>
                                            <div class="col-md-4">
                                                <label>Email</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="email" id="email" class="form-control"
                                                    name="email" value="{{ $user->email }}" disabled>
                                            </div>
                                            <div class="col-md-4">
                                                <label>No. KTP</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="text" id="ktp" class="form-control"
                                                    name="ktp" value="{{ $user->ktp }}" disabled>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Tanggal Lahir</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="text" id="birth" class="form-control"
                                                    name="birth" value="{{ $user->birth }}" disabled>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Alamat</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="text" id="address" class="form-control"
                                                    name="address" value="{{ $user->address }}" disabled>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Pendidikan Terakhir</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="text" id="last_education" class="form-control"
                                                    name="last_education" value="{{ $user->last_education }}" disabled>
                                            </div>
                                            <div class="col-md-4">
                                                <label>No. HP</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="text" id="phone" class="form-control"
                                                    name="phone" value="{{ $user->phone }}" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="card">
                        <div class="card-body py-4 px-5">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-xl">
                                    @if ($user->gender == "male")
                                        <i class="fas fa-grin-alt" style="width:50px; height:50px;"></i>
                                    @elseif ($user->gender == "female")
                                        <i class="fas fa-smile-beam" style="width:50px; height:50px;"></i>
                                    @else
                                        <i class="fas fa-laugh" style="width:50px; height:50px;"></i>
                                    @endif
                                </div>
                                <div class="ms-3 name">
                                    <h5 class="font-bold">{{ $user->name }}</h5>
                                    <!--<h6 class="text-muted mb-0">{{ $user->email }}</h6>-->
                                    <button class="btn btn-primary dropdown-toggle me-1" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"aria-haspopup="true" aria-expanded="false">{{ $user->email }}</button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="/logout" style="width:50px">Log Out</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Data Pegawai</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <form class="form form-horizontal" method="POST" action="{{ route('employee-profile-post') }}">
                                    @csrf
                                    @method('POST')
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>NPP</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                @if ($already_employee == "TRUE")
                                                    <input type="text" id="npp" class="form-control" name="npp" value="{{ $employee->npp }}" disabled>
                                                @elseif ($already_employee == "FALSE")
                                                    <input type="text" id="npp" class="form-control" name="npp" required>
                                                @endif
                                            </div>
                                            <div class="col-md-4">
                                                <label>Jabatan</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                @if ($already_employee == "TRUE")
                                                    <input type="text" id="position" class="form-control" name="position" value="{{ $employee->position }}" disabled>
                                                @elseif ($already_employee == "FALSE")
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="position" id="position" value="Manager" required>
                                                        <label class="form-check-label" for="position">Manager</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="position" id="position" value="Anggota">
                                                        <label class="form-check-label" for="position">Anggota</label>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-4">
                                                <label>Divisi</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                @if ($already_employee == "TRUE")
                                                    <input type="text" id="division" class="form-control" name="division" value="{{ $employee->division }}" disabled>
                                                @elseif ($already_employee == "FALSE")
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="division" id="division" value="IT" required>
                                                        <label class="form-check-label" for="division">IT</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="division" id="division" value="Keuangan">
                                                        <label class="form-check-label" for="division">Keuangan</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="division" id="division" value="Produksi">
                                                        <label class="form-check-label" for="division">Produksi</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="division" id="division" value="Teknis">
                                                        <label class="form-check-label" for="division">Teknis</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="division" id="division" value="Marketing">
                                                        <label class="form-check-label" for="division">Marketing</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="division" id="division" value="HRD">
                                                        <label class="form-check-label" for="division">Human Resources</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="division" id="division" value="Umum">
                                                        <label class="form-check-label" for="division">Umum</label>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-4">
                                                <label>Tahun Bergabung</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                @if ($already_employee == "TRUE")
                                                    <input type="text" id="joined" class="form-control" name="joined" value="{{ $employee->joined }}" disabled>
                                                @elseif ($already_employee == "FALSE")
                                                    <input type="text" id="joined" class="form-control" name="joined" required>
                                                @endif
                                            </div>
                                            <div class="col-sm-12 d-flex justify-content-end">
                                                @if ($already_employee == "TRUE")
                                                    {{-- <a href="{{ route('employee-profile-edit') }}" class="btn btn-light-secondary me-1 mb-1">Reset</a> --}}
                                                @elseif($already_employee == "FALSE")
                                                    <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                                @endif
                                            </div>
                                            @if ($errors->any())
                                                <div class="alert alert-danger mt-4">
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>

@include('employee.alerts')

@endsection