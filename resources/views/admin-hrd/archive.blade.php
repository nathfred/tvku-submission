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
                    <h3>Rekap Bulanan</h3>
                    <p class="text-subtitle text-muted">Rekap Pengajuan Cuti Pegawai Bulanan</p>
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
                                <th>I</th>
                                <th>II</th>
                                <th>III</th>
                                <th>IV</th>
                                <th>V</th>
                                <th>VI</th>
                                <th>VII</th>
                                <th>VIII</th>
                                <th>IX</th>
                                <th>X</th>
                                <th>XI</th>
                                <th>XII</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = -1;
                            @endphp
                            @if ($employees->isNotEmpty())
                                @foreach ($employees as $employee)
                                    @php
                                        $i++;
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $i }}</td>
                                        <td>{{ $employee->user->name }}</td>
                                        <td>{{ $employee->month_sub[$i] }}</td>
                                        <td>{{ $employee->month_sub[$i] }}</td>
                                        <td>{{ $employee->month_sub[$i] }}</td>
                                        <td>{{ $employee->month_sub[$i] }}</td>
                                        <td>{{ $employee->month_sub[$i] }}</td>
                                        <td>{{ $employee->month_sub[$i] }}</td>
                                        <td>{{ $employee->month_sub[$i] }}</td>
                                        <td>{{ $employee->month_sub[$i] }}</td>
                                        <td>{{ $employee->month_sub[$i] }}</td>
                                        <td>{{ $employee->month_sub[$i] }}</td>
                                        <td>{{ $employee->month_sub[$i] }}</td>
                                        <td>{{ $employee->month_sub[$i] }}</td>
                                        @if ($employee->total == 0)
                                            <td>-</td> 
                                        @else
                                            <td>{{ $employee->total }} Kali</td>
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

@include('admin-hrd.alerts')

@endsection