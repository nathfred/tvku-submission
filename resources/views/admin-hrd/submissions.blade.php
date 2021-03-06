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
                    <div class="d-flex flex-row bd-highlight">
                        <div class="buttons">
                            <a href="{{ route('create-pdf',['month'=>0]) }}" class="btn btn-success rounded-pill me-1">Export Tabel</a>
                            <a href="{{ route('create-pdf',['month'=>100]) }}" class="btn btn-success rounded-pill me-1">Export Tahun</a>
                        </div>
                        <div class="btn-group mb-1">
                            <div class="dropdown">
                                <button class="btn btn-success rounded-pill dropdown-toggle me-1" type="button"
                                    id="dropdownMenuButton" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    Export Bulan
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="{{ route('create-pdf',['month'=>1]) }}">Januari</a>
                                    <a class="dropdown-item" href="{{ route('create-pdf',['month'=>2]) }}">Februari</a>
                                    <a class="dropdown-item" href="{{ route('create-pdf',['month'=>3]) }}">Maret</a>
                                    <a class="dropdown-item" href="{{ route('create-pdf',['month'=>4]) }}">April</a>
                                    <a class="dropdown-item" href="{{ route('create-pdf',['month'=>5]) }}">Mei</a>
                                    <a class="dropdown-item" href="{{ route('create-pdf',['month'=>6]) }}">Juni</a>
                                    <a class="dropdown-item" href="{{ route('create-pdf',['month'=>7]) }}">Juli</a>
                                    <a class="dropdown-item" href="{{ route('create-pdf',['month'=>8]) }}">Agustus</a>
                                    <a class="dropdown-item" href="{{ route('create-pdf',['month'=>9]) }}">September</a>
                                    <a class="dropdown-item" href="{{ route('create-pdf',['month'=>10]) }}">Oktober</a>
                                    <a class="dropdown-item" href="{{ route('create-pdf',['month'=>11]) }}">November</a>
                                    <a class="dropdown-item" href="{{ route('create-pdf',['month'=>12]) }}">Desember</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="table1" name="table1">
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
                                            <td>Ditolak ({{ $submission->division_signed_date }})</td>
                                        @elseif ($submission->division_approval == '1')
                                            <td>Diterima ({{ $submission->division_signed_date }})</td>
                                        @endif
                                        <!-- Acc HRD -->
                                        @if ($submission->hrd_approval === NULL)
                                            <td>Belum direspon</td>
                                        @elseif ($submission->hrd_approval == '0')
                                            <td>Ditolak ({{ $submission->hrd_signed_date }})</td>
                                        @elseif ($submission->hrd_approval == '1')
                                            <td>Diterima ({{ $submission->hrd_signed_date }})</td>
                                        @endif
                                        <!-- Lampiran (Attachment) -->
                                        @if ($submission->attachment === NULL || $submission->attachment == '')
                                            <td>-</td>
                                        @else
                                            <td><a href="/data_file/cuti/{{ $submission->attachment }}" target="_blank"><img src="{{ asset('data_file/cuti/'.$submission->attachment) }}" alt="Attachment" class="text-center center" style="max-width: 50px; max-height: 50px;"></a></td>
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
                                        <td style="height: 70px;">
                                            @if ($submission->hrd_approval === NULL)
                                                    <a href="{{ route('adminhrd-submission-acc', ['id' => $submission->id, 'acc' => 1]) }}" class="btn btn-primary"><i class="bi bi-check-square"></i></a>
                                                    <a href="{{ route('adminhrd-submission-acc', ['id' => $submission->id, 'acc' => 0]) }}" class="btn btn-warning"><i class="bi bi-slash-square"></i></a>
                                            @elseif ($submission->hrd_approval == '0')
                                                    <a href="{{ route('adminhrd-submission-acc', ['id' => $submission->id, 'acc' => 1]) }}" class="btn btn-primary"><i class="bi bi-check-square"></i></a>
                                                    <a href="{{ route('create-pdf-submission', ['id' => $submission->id]) }}" class="btn btn-info"><i class="bi bi-printer-fill"></i></a>
                                            @elseif ($submission->hrd_approval == '1')
                                                    <a href="{{ route('adminhrd-submission-acc', ['id' => $submission->id, 'acc' => 0]) }}" class="btn btn-warning"><i class="bi bi-slash-square"></i></a>
                                                    <a href="{{ route('create-pdf-submission', ['id' => $submission->id]) }}" class="btn btn-info"><i class="bi bi-printer-fill"></i></a>
                                            @endif
                                            <!-- Jika belum di acc keduanya -->
                                            @if (!($submission->division_approval == '1' && $submission->hrd_approval == '1'))
                                                <button class="btn btn-danger" onclick="delete_confirm('{{ $submission->id }}')"><i class="bi bi-x-square"></i></button>
                                            @endif
                                        </td> 
                                    </tr>
                                @endforeach
                            @else
                                <tr><td align='center' colspan='15'>Tidak Ada Pengajuan</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-3 col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h4 class="card-title">Export Pegawai</h4>
                    </div>
                    <div class="card-content pt-0">
                        <div class="card-body">
                            <form class="form form-horizontal" method="POST" action="{{ route('create-pdf-employee') }}">
                                @csrf
                                @method('POST')
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>NPP</label>
                                        </div>
                                        <div class="col-md-8 form-group">
                                            <input type="text" id="npp" class="form-control" name="npp">
                                        </div>
                                        <div class="col-sm-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-success me-1 mb-1">Export</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
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

    <script>
        function delete_confirm(submission_id) {
            var submission_id = submission_id;
            var url = '{{ route("adminhrd-delete-submission", ":slug") }}';
            // var url = '{{ route("adminhrd-index") }}';
            url = url.replace(':slug', submission_id);
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Aksi ini tidak dapat diulangi!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus pengajuan!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pengajuan Terhapus!',
                        text: 'Berhasil Menghapus Permohonan Ijin & Cuti',
                        showConfirmButton: true,
                    })
                }
            })
        }
    </script>

@include('admin-hrd.alerts')

@endsection