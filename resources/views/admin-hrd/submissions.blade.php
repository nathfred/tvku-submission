@extends('layouts.admin.app')

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
                    <p class="text-subtitle text-muted">Daftar Pengajuan Cuti Pegawai</p>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    Tabel Daftar Pengajuan
                </div>
                <div class="card-body">
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
                                <th>Lama</th>
                                <th>Acc Divisi</th>
                                <th>Acc HRD</th>
                                <th>Lampiran</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 0;
                            @endphp
                            @if ($total_submissions->isNotEmpty())
                                @foreach ($total_submissions as $submission)
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
                                        <td style="min-width: 107px;">{{ $submission->start_date }}</td>
                                        <td style="min-width: 107px;">{{ $submission->end_date}}</td>
                                        <td>{{ $submission->duration }} hari</td>
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
                                            <td><a href="/data_file/cuti/{{ $submission->attachment }}"><img src="{{ asset('data_file/cuti/'.$submission->attachment) }}" alt="Attachment" class="text-center center" style="max-width: 50px; max-height: 50px;"></a></td>
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
                                        <!-- Aksi HRD -->
                                        @if ($submission->hrd_approval === NULL)
                                            <td style="height: 70px;">
                                                <a href="{{ route('adminhrd-submission-acc', ['id' => $submission->id, 'acc' => 1]) }}" class="btn btn-primary"><i class="bi bi-check-square"></i></a>
                                                <a href="{{ route('adminhrd-submission-acc', ['id' => $submission->id, 'acc' => 0]) }}" class="btn btn-danger"><i class="bi bi-x-square"></i></a>
                                            </td>
                                        @elseif ($submission->hrd_approval == '0')
                                            <td style="height: 70px;">
                                                <a href="{{ route('adminhrd-submission-acc', ['id' => $submission->id, 'acc' => 1]) }}" class="btn btn-primary"><i class="bi bi-check-square"></i></a>
                                            </td>
                                        @elseif ($submission->hrd_approval == '1')
                                            <td style="height: 70px;">
                                                <a href="{{ route('adminhrd-submission-acc', ['id' => $submission->id, 'acc' => 0]) }}" class="btn btn-danger"><i class="bi bi-x-square"></i></a>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <tr><td align='center' colspan='15'>Tidak Ada Pengajuan</td></tr>
                            @endif
                        </tbody>
                    </table>
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