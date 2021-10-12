<script src="{{ asset('js/sweetalert2.js') }}"></script>

{{-- SweetAlert2 New : CDN (V11) --}}
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="sweetalert2.all.min.js"></script>

@if (Session::has('message') && Session::get('message') == 'user-not-found')
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'User Salah',
            text: 'Data User Tidak Ditemukan',
            showConfirmButton: true,
        })
    </script>
@elseif (Session::has('message') && Session::get('message') == 'success-update-user')
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil Update',
            text: 'Berhasil Update Data User',
            showConfirmButton: true,
        })
    </script>
@elseif (Session::has('message') && Session::get('message') == 'success-update-employee')
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil Update',
            text: 'Berhasil Update Data Employee',
            showConfirmButton: true,
        })
    </script>
@elseif (Session::has('message') && Session::get('message') == 'success-delete')
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil Menghapus',
            text: 'Berhasil Menghapus Data User, Employee, dan Submission',
            showConfirmButton: true,
        })
    </script>
@endif
