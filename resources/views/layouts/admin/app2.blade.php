<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TVKU | {{ $title }}</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">

    <link rel="stylesheet" href="{{ asset('vendors/fontawesome/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/iconly/bold.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/bootstrap-icons/bootstrap-icons.css') }}">

    <link rel="stylesheet" href="{{ asset('css/app-mazer.css') }}">

    <link rel="shortcut icon" href="{{ asset('img/tvku_favicon.png') }}" type="image/x-icon">
</head>

<body>
    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header py-0" style="height: 10%;">
                    <div class="d-flex justify-content-between" style="position: relative;">
                        <div class="logo"></div>
                        <div class="logo center text-center mt-4" style="position: absolute;">
                            <a href="{{ route('admindivisi-index') }}"><img src="{{ asset('img/tvku_logo_ori.png') }}" alt="TVKU Logo" style="width: 50%; height: 75%;"></a>
                        </div>
                        <div class="toggler">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-title">Menu</li>

                        <li class="sidebar-item {{ ($active === "index") ? 'active' : '' }}">
                            <a href="{{ route('admindivisi-index') }}" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ ($active === "employees") ? 'active' : '' }}">
                            <a href="{{ route('admindivisi-employee') }}" class='sidebar-link'>
                                <i class="bi bi-file-person-fill"></i>
                                <span>Daftar Pegawai</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ ($active === "cuti") ? 'active' : '' }}">
                            <a href="{{ route('admindivisi-submission') }}" class='sidebar-link'>
                                <i class="bi bi-file-earmark-medical-fill"></i>
                                <span>Daftar Pengajuan</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ ($active === "archive") ? 'active' : '' }}">
                            <a href="{{ route('admindivisi-archive') }}" class='sidebar-link'>
                                <i class="bi bi-file-earmark-bar-graph-fill"></i>
                                <span>Rekap Bulanan</span>
                            </a>
                        </li>

                    </ul>
                </div>
                <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
            </div>
        </div>
        <div id="main">
            @yield('content')
            <footer>
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-end">
                        <p>2021 &copy; nathfred</p>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="{{ asset('vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('vendors/fontawesome/all.min.js') }}"></script>

    <script src="{{ asset('vendors/apexcharts/apexcharts.js') }}"></script>
    <script src="{{ asset('js/pages/dashboard.js') }}"></script>

    <script src="{{ asset('vendors/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.js') }}"></script>

    {{-- SweetAlert2 New : CDN (V11) --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweetalert2.all.min.js"></script>

    <script src="{{ asset('js/main.js') }}"></script>
</body>

</html>