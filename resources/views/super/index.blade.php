@extends('layouts.admin.app')

@section('content')
    <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
    </header>

    <div class="page-heading">
        <h3>Dashboard</h3>
    </div>
    <div class="page-content">
        <section class="row">
            <div class="col-12 col-lg-9">
                <div class="row">
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon purple">
                                            <i class="iconly-boldPaper"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Total Pengajuan</h6>
                                        <h6 class="font-extrabold mb-0">{{ $total_submissions }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon blue">
                                            <i class="iconly-boldShow"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Sudah Direspon</h6>
                                        <h6 class="font-extrabold mb-0">{{ $responded_submissions }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon red">
                                            <i class="iconly-boldDanger"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Belum Direspon</h6>
                                        <h6 class="font-extrabold mb-0">{{ $unresponded_submissions }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon green">
                                            <i class="iconly-boldProfile"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Ijin Hari Ini</h6>
                                        <h6 class="font-extrabold mb-0">{{ $current_submissions }} Pegawai</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <p class="font-weight-bold text-uppercase">Tabel Daftar Pengajuan Cuti</p>
                            </div>
                            <div class="card-body mt-0 pt-0">
                                <table class="table table-striped" id="table1">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th>Nama</th>
                                            <th>NPP</th>
                                            <th>Jabatan</th>
                                            <th>Divisi</th>
                                            <th>Jenis</th>
                                            <th>Keterangan</th>
                                            <th>Tanggal Ijin</th>
                                            <th>Tanggal Kembali</th>
                                            <th>Acc Divisi</th>
                                            <th>Acc HRD</th>
                                            <th>Lampiran</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 0;
                                        @endphp
                                        @if ($all_submissions->isNotEmpty())
                                            @foreach ($all_submissions as $submission)
                                                @php
                                                    $i++;
                                                @endphp
                                                <tr>
                                                    <td class="text-center">{{ $i }}</td>
                                                    <td>{{ $submission->employee->user->name }}</td>
                                                    <td>{{ $submission->employee->npp }}</td>
                                                    <td>{{ $submission->employee->position }}</td>
                                                    <td>{{ $submission->employee->division }}</td>
                                                    <td>{{ $submission->type }}</td>
                                                    <td>{{ $submission->description }}</td>
                                                    <td>{{ $submission->start_date }}</td>
                                                    <td>{{ $submission->end_date}}</td>
                                                    <!-- Acc Divisi -->
                                                    @if ($submission->division_approval === NULL)
                                                        <td>Belum direspon</td>
                                                    @elseif ($submission->division_approval == '0')
                                                        <td>Ditolak</td>
                                                    @elseif ($submission->division_approval == '1')
                                                        <td>Diterima</td>
                                                    @endif
                                                    <!-- Acc HRD -->
                                                    @if ($submission->hrd_approval === NULL)
                                                        <td>Belum direspon</td>
                                                    @elseif ($submission->hrd_approval == '0')
                                                        <td>Ditolak</td>
                                                    @elseif ($submission->hrd_approval == '1')
                                                        <td>Diterima</td>
                                                    @endif
                                                    <!-- Lampiran (Attachment) -->
                                                    @if ($submission->attachment === NULL || $submission->attachment == '')
                                                        <td>-</td>
                                                    @else
                                                        <td><a href="/data_file/cuti/{{ $submission->attachment }}" target="_blank"><img src="{{ asset('data_file/cuti/'.$submission->attachment) }}" alt="Attachment" class="text-center center" style="max-width: 35px; max-height: 35px;"></a></td>
                                                    @endif
                                                    <!-- Status Submisison -->
                                                    @if ($submission->division_approval == '1' && $submission->hrd_approval == '1')
                                                        <td>
                                                            <span class="badge bg-success">Diterima</span>
                                                        </td>
                                                    @elseif ($submission->division_approval == '0' && $submission->hrd_approval == '0')
                                                        <td>
                                                            <span class="badge bg-danger">Ditolak</span>
                                                        </td>
                                                    @elseif (($submission->division_approval == '1' && $submission->hrd_approval === NULL) || ($submission->division_approval === NULL && $submission->hrd_approval == '1'))
                                                    <td>
                                                        <span class="badge bg-info">Menunggu</span>
                                                    </td>
                                                    @else
                                                        <td>
                                                            <span class="badge bg-warning">Ditolak</span>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr><td align='center' colspan='13'>Tidak Ada Pengajuan</td></tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-3">
                <div class="card">
                    <div class="card-body py-4 px-5">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-xl">
                                @if ($user->gender = 'male')
                                    <i class="fas fa-grin-alt" style="width:50px; height:50px;"></i>
                                @else
                                    <i class="fas fa-smile-beam" style="width:50px; height:50px;"></i>
                                @endif
                            </div>
                            <div class="ms-3 name">
                                <h5 class="font-bold">{{ $user->name }}</h5>
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
                        <h4>Pengajuan Terbaru</h4>
                    </div>
                    <div class="card-content pb-4">
                        @if ($recent_submissions->isNotEmpty())
                            @foreach ($recent_submissions as $submission)
                                <div class="recent-message d-flex px-4 py-3">
                                    <div class="avatar avatar-lg">
                                        @if ($submission->employee->user->gender == 'female')
                                            <img src="{{ asset('images/faces/female') }}.png">
                                        @else
                                            <img src="{{ asset('images/faces/male') }}.png">
                                        @endif
                                    </div>
                                    <div class="name ms-4">
                                        <h5 class="mb-1">{{ $submission->employee->user->name }}</h5>
                                        <h6 class="text-muted mb-0">{{ $submission->start_date }} - {{ $submission->end_date }}</h6>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        <div class="px-4">
                            <a href="{{ route('adminhrd-submission') }}" class='btn btn-block btn-xl btn-light-primary font-bold mt-3'>Lebih Lanjut</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="{{ asset('vendors/simple-datatables/simple-datatables.js') }}"></script>
    <script>
        // Simple Datatable
        let table1 = document.querySelector('#table1');
        let dataTable = new simpleDatatables.DataTable(table1);
    </script>
@endsection