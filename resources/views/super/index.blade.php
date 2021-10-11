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
                    <h3>Daftar Pegawai</h3>
                    <p class="text-subtitle text-muted">Tabel Daftar Pegawai TVKU</p>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="table1" name="table1">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Nama Lengkap</th>
                                <th>NPP</th>
                                <th>Jabatan</th>
                                <th>Divisi</th>
                                <th>Tahun</th>
                                <th>KTP</th>
                                <th>Alamat</th>
                                <th>Tanggal Lahir</th>
                                <th>No. HP</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 0;
                            @endphp
                            @if ($employees->isNotEmpty())
                                @foreach ($employees as $employee)
                                    @php
                                        $i++;
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $i }}</td>
                                        <td>{{ $employee->user->name }}</td>
                                        <td>{{ $employee->npp }}</td>
                                        <td>{{ $employee->position }}</td>
                                        <td>{{ $employee->division }}</td>
                                        <td>{{ $employee->joined }}</td>
                                        <td>{{ $employee->user->ktp }}</td>
                                        <td>{{ $employee->user->address }}</td>
                                        <td>{{ $employee->user->birth }}</td>
                                        <td>{{ $employee->user->phone }}</td>
                                        @if ($employee->total == 0)
                                            <td>-</td> 
                                        @else
                                            <td>{{ $employee->total }} Kali</td>
                                        @endif
                                        <td style="height: 70px;">
                                            <a href="{{ route('super-show-user', ['id' => $employee->user->id]) }}" class="btn btn-info"><i class="bi bi-arrow-left-square"></i></a>
                                            <a href="{{ route('super-delete-user', ['id' => $employee->user->id]) }}" class="btn btn-danger"><i class="bi bi-x-square"></i></a>
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

        </section>
    </div>

    <script src="{{ asset('vendors/simple-datatables/simple-datatables.js') }}"></script>
    <script>
        // Simple Datatable
        let table1 = document.querySelector('#table1');
        let dataTable = new simpleDatatables.DataTable(table1);
    </script>

@include('admin-hrd.alerts')

@endsection