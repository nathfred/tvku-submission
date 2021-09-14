@extends('layouts.employee.app')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Employee Profile</h3>
                <p class="text-subtitle text-muted">Data Profil Pegawai</p>
            </div>
        </div>
    </div>

    <!-- Basic Horizontal form layout section start -->
    <section id="basic-horizontal-layouts">
        <div class="row match-height">
            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Data Diri</h4>
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
                                            <input type="text" id="first-name" class="form-control"
                                                name="fname" value="{{ $employee->name }}" disabled>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Email</label>
                                        </div>
                                        <div class="col-md-8 form-group">
                                            <input type="email" id="email-id" class="form-control"
                                                name="email-id" value="{{ $employee->email }}" disabled>
                                        </div>
                                        <div class="col-md-4">
                                            <label>No. KTP</label>
                                        </div>
                                        <div class="col-md-8 form-group">
                                            <input type="ktp" id="first-name" class="form-control"
                                                name="ktp" value="{{ $employee->ktp }}" disabled>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Tanggal Lahir</label>
                                        </div>
                                        <div class="col-md-8 form-group">
                                            <input type="text" id="birth" class="form-control"
                                                name="birth" value="{{ $employee->birth }}" disabled>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Alamat</label>
                                        </div>
                                        <div class="col-md-8 form-group">
                                            <input type="text" id="address" class="form-control"
                                                name="address" value="{{ $employee->address }}" disabled>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Pendidikan Terakhir</label>
                                        </div>
                                        <div class="col-md-8 form-group">
                                            <input type="text" id="last_education" class="form-control"
                                                name="last_education" value="{{ $employee->last_education }}" disabled>
                                        </div>
                                        <div class="col-md-4">
                                            <label>No. HP</label>
                                        </div>
                                        <div class="col-md-8 form-group">
                                            <input type="text" id="phone" class="form-control"
                                                name="phone" value="{{ $employee->phone }}" disabled>
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
                    <div class="card-header">
                        <h4 class="card-title">Data Pegawai</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form form-horizontal">
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>NPP</label>
                                        </div>
                                        <div class="col-md-8 form-group">
                                            <input type="text" id="npp" class="form-control"
                                                name="npp" value="{{ $employee->npp }}" disabled>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Jabatan</label>
                                        </div>
                                        <div class="col-md-8 form-group">
                                            <input type="text" id="position" class="form-control"
                                                name="position" value="{{ $employee->position }}" disabled>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Divisi</label>
                                        </div>
                                        <div class="col-md-8 form-group">
                                            <input type="text" id="division" class="form-control"
                                                name="division" value="{{ $employee->division }}" disabled>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Tahun Bergabung</label>
                                        </div>
                                        <div class="col-md-8 form-group">
                                            <input type="text" id="joined" class="form-control"
                                                name="joined" value="{{ $employee->joined }}" disabled>
                                        </div>
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
@endsection