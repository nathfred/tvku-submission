@extends('layouts.super.app')

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
                            <h3>Buat User</h3>
                        </div>
                    </div>
                    <p class="text-subtitle text-muted">Buat User Baru</p>
                </div>
            </div>
        </div>

        <!-- Basic Horizontal form layout section start -->
        <section id="basic-horizontal-layouts">
            <div class="row match-height">
                <div class="col-md-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Data User</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <form class="form form-horizontal" method="POST" action="{{ route('super-save-user') }}">
                                    @csrf
                                    @method('POST')
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Nama Lengkap</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="text" id="name" class="form-control"
                                                    name="name" value="Andreas Yulianto" required >
                                            </div>
                                            <div class="col-md-4">
                                                <label>Role</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="text" id="role" class="form-control"
                                                    name="role" placeholder="employee / admin-hrd / admin-divisi / super" value="employee" required >
                                            </div>
                                            <div class="col-md-4">
                                                <label>Jenis Kelamin</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                {{-- @if ($user->gender == 'male')
                                                    <input type="text" id="gender" class="form-control" name="gender" value="Laki-laki" >
                                                @elseif ($user->gender == 'female')
                                                    <input type="text" id="gender" class="form-control" name="gender" value="Perempuan" >
                                                @else
                                                    <input type="text" id="gender" class="form-control" name="gender" value="-" >
                                                @endif --}}
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="gender" id="gender" value="male" required>
                                                    <label class="form-check-label" for="gender">Laki-laki</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="gender" id="gender" value="female" required>
                                                    <label class="form-check-label" for="gender">Perempuan</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Email</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="email" id="email" class="form-control"
                                                    name="email" value="sample2@gmail.com" required >
                                            </div>
                                            <div class="col-md-4">
                                                <label>No. KTP</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="text" id="ktp" class="form-control"
                                                    name="ktp" value="1234567890123456" required >
                                            </div>
                                            <div class="col-md-4">
                                                <label>Tanggal Lahir</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="date" id="birth" class="form-control"
                                                    name="birth" required >
                                            </div>
                                            <div class="col-md-4">
                                                <label>Alamat</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="text" id="address" class="form-control"
                                                    name="address" value="Nakula 3" required >
                                            </div>
                                            <div class="col-md-4">
                                                <label>Pendidikan Terakhir</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="text" id="last_education" class="form-control"
                                                    name="last_education" value="UDNIS" required >
                                            </div>
                                            <div class="col-md-4">
                                                <label>No. HP</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="text" id="phone" class="form-control"
                                                    name="phone" value="08184586511" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Password</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="password" id="password" class="form-control"
                                                    name="password" value="123123" required >
                                            </div>
                                            <div class="col-md-4">
                                                <label>Password Confirm</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="password" id="password_confirmation" class="form-control"
                                                    name="password_confirmation" value="123123" required >
                                            </div>
                                            <div class="col-sm-12 d-flex justify-content-end">
                                                <button type="submit" class="btn btn-primary me-1 mb-1">Confirm</button>
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
                                    <img src="{{ asset('images/faces/male') }}.png">
                                </div>
                                <div class="ms-3 name">
                                    <h5 class="font-bold">User Baru</h5>
                                    <button class="btn btn-primary" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"aria-haspopup="true" aria-expanded="false">Buat User Baru</button>
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
                                <form class="form form-horizontal" method="POST" action="{{ route('super-save-employee') }}">
                                    @csrf
                                    @method('POST')
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>User ID</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="text" id="user_id" class="form-control"
                                                    name="user_id" placeholder="sesuaikan dari id user nya (relasi)" required >
                                            </div>
                                            <div class="col-md-4">
                                                <label>NPP</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="text" id="npp" class="form-control" name="npp" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Jabatan</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="position" id="position" value="Manager" required >
                                                    <label class="form-check-label" for="position">Manager</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="position" id="position" value="Kepala" >
                                                    <label class="form-check-label" for="position">Kepala</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="position" id="position" value="Staff" >
                                                    <label class="form-check-label" for="position">Staff</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Divisi</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="division" id="division" value="IT" required >
                                                    <label class="form-check-label" for="division">IT</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="division" id="division" value="Keuangan" >
                                                    <label class="form-check-label" for="division">Keuangan</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="division" id="division" value="Produksi" >
                                                    <label class="form-check-label" for="division">Produksi</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="division" id="division" value="Teknikal Support" >
                                                    <label class="form-check-label" for="division">Teknik</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="division" id="division" value="Marketing" >
                                                    <label class="form-check-label" for="division">Marketing</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="division" id="division" value="Human Resources" >
                                                    <label class="form-check-label" for="division">Human Resources</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="division" id="division" value="News" >
                                                    <label class="form-check-label" for="division">News</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="division" id="division" value="Umum" >
                                                    <label class="form-check-label" for="division">Umum</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Tahun Bergabung</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                    <input type="text" id="joined" class="form-control" name="joined" required>
                                            </div>
                                            <div class="col-sm-12 d-flex justify-content-end">
                                                <button type="submit" class="btn btn-primary me-1 mb-1">Confirm</button>
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

@include('super.alerts')

@endsection