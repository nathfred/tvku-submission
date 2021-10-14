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
                            <h3>Data Pengajuan Permohonan</h3>
                        </div>
                    </div>
                    <p class="text-subtitle text-muted">Pengajuan Permohonan Ijin dan Cuti Pegawai</p>
                </div>
            </div>
        </div>

        <!-- Basic Horizontal form layout section start -->
        <section id="basic-horizontal-layouts">
            <div class="row match-height">
                <div class="col-md-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Edit Data Pengajuan</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <form class="form form-horizontal" method="POST" action="{{ route('super-edit-submission', ['id' => $submission->id]) }}">
                                    @csrf
                                    @method('POST')
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>ID</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="text" id="id" class="form-control"
                                                    name="id" value="{{ $submission->id }}" disabled>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Employee ID</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="text" id="employee_id" class="form-control"
                                                    name="employee_id" value="{{ $submission->employee_id }}" >
                                            </div>
                                            <div class="col-md-4">
                                                <label>Deskripsi</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="text" id="description" class="form-control"
                                                    name="description" value="{{ $submission->description }}" >
                                            </div>
                                            <div class="col-md-4">
                                                <label>Jenis Ijin</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="type" id="type" value="Sakit" {{ ($submission->type == "Sakit") ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="type">Sakit</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="type" id="type" value="Lainnya" {{ ($submission->type == "Lainnya") ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="type">Lainnya</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Tanggal Ijin</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="date" id="start_date" class="form-control"
                                                    name="start_date" value="{{ $submission->start_date }}" >
                                            </div>
                                            <div class="col-md-4">
                                                <label>Tanggal Kembali</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="date" id="end_date" class="form-control"
                                                    name="end_date" value="{{ $submission->end_date }}" >
                                            </div>
                                            <input type="hidden" name="id" value="{{ $submission->id }}">
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

            </div>
        </section>

    </div>

@include('super.alerts')

@endsection