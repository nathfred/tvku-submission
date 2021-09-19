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
@elseif (Session::has('message') && Session::get('message') == 'failed-register')
<script>
    Swal.fire({
        icon: 'error',
        title: 'Input Salah',
        text: 'Input Data Tidak Sesuai Kriteria',
        showConfirmButton: true,
    })
</script>
@endif
