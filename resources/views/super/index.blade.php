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
                    <a href="/logout" class="btn btn-primary">Logout</a>
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
                                            {{-- <a href="{{ route('super-delete-user', ['id' => $employee->user->id]) }}" class="btn btn-danger"><i class="bi bi-x-square"></i></a> --}}
                                            <button class="btn btn-danger" onclick="delete_confirm3()"><i class="bi bi-x-square"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr><td align='center' colspan='15'>Tidak Ada Data</td></tr>
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
        function delete_confirm() {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Aksi ini tidak dapat diulangi!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus data!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        'Terhapus!',
                        'Data berhasil terhapus.',
                        'success'
                    )
                    // window.location.href = "{{ route('super-delete-user', ['id' => $employee->user->id]) }}";
                }
            })
        }
    </script>
    <script>
        function delete_confirm2() {
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
            })

            swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
            }).then((result) => {
            if (result.isConfirmed) {
                swalWithBootstrapButtons.fire(
                'Deleted!',
                'Your file has been deleted.',
                'success'
                )
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                'Cancelled',
                'Your imaginary file is safe :)',
                'error'
                )
            }
            })
        }
    </script>
    <script>
        function delete_confirm3() {
            Swal.fire({
                title: 'Do you want to save the changes?',
                showConfirmButton: true,
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Save',
                denyButtonText: 'Dont save',
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    window.location.href = "{{ route('super-delete-user', ['id' => $employee->user->id]) }}";
                    Swal.fire('Saved!', '', 'success')
                } else if (result.isDenied) {
                    Swal.fire('Changes are not saved', '', 'info')
                }
            })
        }
    </script>

@include('admin-hrd.alerts')

@endsection