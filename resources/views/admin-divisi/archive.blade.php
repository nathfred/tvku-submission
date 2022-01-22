@extends('layouts.admin.app2')

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
                    @if (!empty($target_year))
                        <h3>Rekap Bulanan ({{ $target_year }})</h3>
                    @else
                        <h3>Rekap Bulanan</h3>
                    @endif
                    <p class="text-subtitle text-muted">Rekap Pengajuan Cuti Pegawai Bulanan</p>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-row bd-highlight">
                        <div class="buttons">
                            <a href="{{ route('create-pdf-archive2',['division'=>$division,'year'=>$target_year]) }}" class="btn btn-success rounded-pill me-1">Export Tabel</a>
                        </div>
                        <div class="btn-group mb-1">
                            <div class="dropdown">
                                <button class="btn btn-success rounded-pill dropdown-toggle me-1" type="button"
                                    id="dropdownMenuButton" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    Rekap Tahunan
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    @foreach ($years as $year)
                                        <a class="dropdown-item" href="{{ route('admindivisi-archive',['year'=>$year]) }}">{{ $year }}</a>
                                    @endforeach
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
                                <th>Nama Lengkap</th>
                                <th>Jan</th>
                                <th>Feb</th>
                                <th>Mar</th>
                                <th>Apr</th>
                                <th>Mei</th>
                                <th>Jun</th>
                                <th>Jul</th>
                                <th>Ags</th>
                                <th>Sep</th>
                                <th>Okt</th>
                                <th>Nov</th>
                                <th>Des</th>
                                <th>Total</th>
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
                                        @for ($j = 0; $j < 12; $j++)
                                            @if ($employee->month_sub[$j] == 0)
                                                <td class="text-center">-</td>
                                            @else
                                                <td class="text-center">{{ $employee->day_month_sub[$j] }}</td>
                                            @endif
                                        @endfor
                                        @if ($employee->total == 0)
                                            <td class="text-center">-</td> 
                                        @else
                                            <td class="text-center">{{ $employee->total_duration }} Hari</td>
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

@include('admin-divisi.alerts')

@endsection