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
                            @if ($user_submissions->isNotEmpty())
                                @foreach ($user_submissions as $submission)
                                    @php
                                        $i++;
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $i }}</td>
                                        <td>{{ $submission->type }}</td>
                                        <td>{{ $submission->description }}</td>
                                        <td>{{ $submission->start_date }}</td>
                                        <td>{{ $submission->end_date }}</td>
                                        <td>{{ $submission->duration }} Hari</td>
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
                                            <span class="badge bg-info">Menunggu Konfirmasi</span>
                                        </td>
                                        @else
                                            <td>
                                                <span class="badge bg-warning">Menunggu Konfirmasi</span>
                                            </td>
                                        @endif
                                        <!-- Aksi -->
                                        <td>
                                            <a href="{{ route('create-pdf-submission', ['id' => $submission->id]) }}" class="btn btn-info"><i class="bi bi-printer-fill"></i></a>
                                            <!-- Jika belum di acc keduanya -->
                                            @if (!($submission->division_approval == '1' && $submission->hrd_approval == '1'))
                                                <button class="btn btn-danger" onclick="delete_confirm('{{ $submission->id }}')"><i class="bi bi-x-square"></i></button>
                                            @endif            
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr><td align='center' colspan='9'>Tidak Ada Pengajuan</td></tr>
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

    <script>
        function delete_confirm(submission_id) {
            var submission_id = submission_id;
            var url = '{{ route("employee-delete-submission", ":slug") }}';
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

@include('employee.alerts')

@endsection