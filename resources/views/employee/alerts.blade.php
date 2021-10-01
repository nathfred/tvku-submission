<script src="{{ asset('js/sweetalert2.js') }}"></script>

{{-- Employee Profile --}}
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
@elseif (Session::has('message') && Session::get('message') == 'register-employee-first')
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Silahkan Register Pegawai',
            text: 'Register Pegawai Diperlukan Terlebih Dahulu',
            showConfirmButton: true,
        })
    </script>

{{-- Employee Create Submission --}}
@elseif (Session::has('message') && Session::get('message') == 'incorrect-date')
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Kesalahan Input Tanggal',
            text: 'Tanggal Ijin/Kembali Tidak Valid',
            showConfirmButton: true,
        })
    </script>
@elseif (Session::has('message') && Session::get('message') == 'max-submission-per-month')
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Batas Cuti Bulanan Dilampaui',
            text: 'Maximal Cuti 2x Dalam 1 Bulan (Kecuali Hamil)',
            showConfirmButton: true,
        })
    </script>

{{-- Employee Submission --}}
@elseif (Session::has('message') && Session::get('message') == 'success-submission')
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Pengajuan Berhasil Dibuat',
            text: 'Silahkan Menunggu Konfirmasi Divisi & HRD',
            showConfirmButton: true,
        })
    </script>
@elseif (Session::has('message') && Session::get('message') == 'incorrect-sub-id')
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Pengajuan Cuti Tidak Valid',
            text: 'ID Pengajuan Cuti Tidak Ditemukan',
            showConfirmButton: true,
        })
    </script>
@endif
