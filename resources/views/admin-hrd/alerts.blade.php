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
@endif
