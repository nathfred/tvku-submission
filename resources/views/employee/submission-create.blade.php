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
                    <h3>Pengajuan Cuti</h3>
                    <p class="text-subtitle text-muted">Buat Pengajuan Cuti Pegawai</p>
                </div>
            </div>
        </div>

        <!-- Basic Horizontal form layout section start -->
        <section id="basic-horizontal-layouts">
            <div class="row match-height">
                <div class="col-md-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Form Pengajuan</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <form class="form form-horizontal" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('POST')
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Nama Lengkap</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="text" id="name" class="form-control" name="name" value="{{ $user->name }}" disabled required>
                                            </div>
                                            <div class="col-md-4">
                                                <label>NPP</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="text" id="npp" class="form-control" name="npp" value="{{ $employee->npp }}" disabled required>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Jabatan</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="text" id="position" class="form-control" name="position" value="{{ $employee->position }}" disabled required>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Divisi</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="text" id="division" class="form-control" name="division" value="{{ $employee->division }}" disabled required>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Jenis</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="type" id="type" value="Sakit" required>
                                                    <label class="form-check-label" for="type">
                                                        Sakit
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="type" id="type" value="Lainnya">
                                                    <label class="form-check-label" for="type">
                                                        Lainnya
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Tanggal Ijin</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="date" id="start_date" class="form-control" name="start_date" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Tanggal Kembali</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="date" id="end_date" class="form-control" name="end_date" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Keterangan</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="text" id="description" class="form-control" name="description" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Lampiran (.png)</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input class="form-control" type="file" id="attachment" name="attachment">
                                            </div>
                                            <div class="col-sm-12 d-flex justify-content-end">
                                                <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
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