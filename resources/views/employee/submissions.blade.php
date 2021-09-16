@extends('layouts.employee.app')

@section('content')
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
                            <th>Acc Divisi</th>
                            <th>Acc HRD</th>
                            <th>Status</th>
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
                                    <td>{{ $submission->end_date}}</td>
                                    @if ($submission->division_approval === NULL)
                                        <td>Belum direspon</td>
                                    @elseif ($submission->division_approval == '0')
                                        <td>Ditolak</td>
                                    @elseif ($submission->division_approval == '1')
                                        <td>Diterima</td>
                                    @endif
                                    @if ($submission->hrd_approval === NULL)
                                        <td>Belum direspon</td>
                                    @elseif ($submission->hrd_approval == '0')
                                        <td>Ditolak</td>
                                    @elseif ($submission->hrd_approval == '1')
                                        <td>Diterima</td>
                                    @endif
                                    @if ($submission->division_approval == '1' && $submission->hrd_approval == '1')
                                        <td>
                                            <span class="badge bg-success">Diterima</span>
                                        </td>
                                    @elseif ($submission->division_approval == '0' && $submission->hrd_approval == '0')
                                        <td>
                                            <span class="badge bg-warning">Ditolak</span>
                                        </td>
                                    @else
                                        <td>
                                            <span class="badge bg-info">Menunggu Konfirmasi</span>
                                        </td>
                                    @endif
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
@endsection