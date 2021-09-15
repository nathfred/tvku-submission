<script src="{{ asset('js/sweetalert2.js') }}"></script>

@if (Session::has('message') && Session::get('message') == 'success-register')
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Data Tersimpan',
            text: 'Profil Pegawai Berhasil Tersimpan',
            showConfirmButton: true,
        })
    </script>
@endif
