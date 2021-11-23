<script src="{{ asset('js/sweetalert2.js') }}"></script>

{{-- Employee Profile --}}
@if (Session::has('message') && Session::get('message') == 'success-submission-acc')
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Menyetujui Pengajuan',
            text: 'Berhasil Menyetujui Pengajuan Cuti',
            showConfirmButton: true,
        })
    </script>
@elseif (Session::has('message') && Session::get('message') == 'success-submission-dec')
    <script>
        Swal.fire({
            icon: 'info',
            title: 'Menolak Pengajuan',
            text: 'Berhasil Menolak Pengajuan Cuti',
            showConfirmButton: true,
        })
    </script>
@elseif (Session::has('message') && Session::get('message') == 'success-submission-unknown')
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Kesalahan Penyetujuan',
            text: 'Terjadi Kesalahan Dalam Penyetujuan, Mohon Periksa Ulang',
            showConfirmButton: true,
        })
    </script>
@elseif (Session::has('message') && Session::get('message') == 'submission-not-found')
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan!',
            text: 'ID Pengajuan Tidak Ditemukan!',
            showConfirmButton: true,
        })
    </script>
@elseif (Session::has('message') && Session::get('message') == 'success-delete-submission')
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Menghapus Pengajuan!',
            text: 'Berhasil Menghapus Data Permohonan Cuti',
            showConfirmButton: true,
        })
    </script>
@endif
