<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>SIMPECOR - {{ $title ?? 'Dashboard' }}</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('assets') }}/img/kaiadmin/favicon.ico" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="{{ asset('assets') }}/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {
                families: ["Public Sans:300,400,500,600,700"]
            },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["{{ asset('assets') }}/css/fonts.min.css"],
            },
            active: function() {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets') }}/css/bootstrap.min.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/css/plugins.min.css" />
    <link rel="stylesheet" href="{{ asset('assets') }}/css/kaiadmin.min.css" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="{{ asset('assets') }}/css/demo.css" />

    @stack('styles')
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar" data-background-color="dark">
            <div class="sidebar-logo">
                <!-- Logo Header -->
                <div class="logo-header" data-background-color="dark">
                    <a href="index.html" class="logo">
                        <img src="{{ asset('assets') }}/img/kaiadmin/logo_light.svg" alt="navbar brand"
                            class="navbar-brand" height="20" />
                    </a>
                    <div class="nav-toggle">
                        <button class="btn btn-toggle toggle-sidebar">
                            <i class="gg-menu-right"></i>
                        </button>
                        <button class="btn btn-toggle sidenav-toggler">
                            <i class="gg-menu-left"></i>
                        </button>
                    </div>
                    <button class="topbar-toggler more">
                        <i class="gg-more-vertical-alt"></i>
                    </button>
                </div>
                <!-- End Logo Header -->
            </div>
            <div class="sidebar-wrapper scrollbar scrollbar-inner">
                <div class="sidebar-content">
                    <ul class="nav nav-secondary">
                        <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
                            <a href="{{ route('dashboard') }}">
                                <i class="fas fa-home"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        @if (auth()->user()->role === 'admin' || auth()->user()->role === 'direktur')
                            <li class="nav-item">
                                <a data-bs-toggle="collapse" href="#masterdt">
                                    <i class="fas fa-database"></i>
                                    <p>Data Master</p>
                                    <span class="caret"></span>
                                </a>
                                <div class="collapse" id="masterdt">
                                    <ul class="nav nav-collapse">
                                        <li class="{{ request()->routeIs('supplier.*') ? 'active' : '' }}">
                                            <a href="{{ route('supplier.index') }}">
                                                <span class="sub-item">Supplier</span>
                                            </a>
                                        </li>
                                        <li class="{{ request()->routeIs('mitra.*') ? 'active' : '' }}">
                                            <a href="{{ route('mitra.index') }}">
                                                <span class="sub-item">Mitra</span>
                                            </a>
                                        </li>
                                        <li class="{{ request()->routeIs('bahan.*') ? 'active' : '' }}">
                                            <a href="{{ route('bahan.index') }}">
                                                <span class="sub-item">Bahan Baku</span>
                                            </a>
                                        </li>
                                        <li class="{{ request()->routeIs('kategori.*') ? 'active' : '' }}">
                                            <a href="{{ route('kategori.index') }}">
                                                <span class="sub-item">Kategori Product Cor</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item {{ request()->is('user') ? 'active' : '' }}">
                                <a href="{{ route('user.index') }}">
                                    <i class="fas fa-users"></i>
                                    <p>User</p>
                                </a>
                            </li>
                        @endif

                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#transaksi">
                                <i class="fas fa-exchange-alt"></i>
                                <p>Transaksi</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="transaksi">
                                <ul class="nav nav-collapse">
                                    @if (auth()->user()->role === 'admin' || auth()->user()->role === 'direktur')
                                        <li class="{{ request()->routeIs('barang-masuk.*') ? 'active' : '' }}">
                                            <a href="{{ route('barang-masuk.index') }}">
                                                <span class="sub-item">Barang Masuk</span>
                                            </a>
                                        </li>
                                    @endif
                                    <li class="{{ request()->routeIs('pemesanan.*') ? 'active' : '' }}">
                                        <a href="{{ route('pemesanan.index') }}">
                                            <span class="sub-item">Pesanan</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item {{ request()->is('pemesanan.history') ? 'active' : '' }}">
                            <a href="{{ route('pemesanan.history') }}">
                                <i class="fas fa-history"></i>
                                <p>History Pengerjaan</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- End Sidebar -->

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <!-- Logo Header -->
                    <div class="logo-header" data-background-color="dark">
                        <a href="index.html" class="logo">
                            <img src="{{ asset('assets') }}/img/kaiadmin/logo_light.svg" alt="navbar brand"
                                class="navbar-brand" height="20" />
                        </a>
                        <div class="nav-toggle">
                            <button class="btn btn-toggle toggle-sidebar">
                                <i class="gg-menu-right"></i>
                            </button>
                            <button class="btn btn-toggle sidenav-toggler">
                                <i class="gg-menu-left"></i>
                            </button>
                        </div>
                        <button class="topbar-toggler more">
                            <i class="gg-more-vertical-alt"></i>
                        </button>
                    </div>
                    <!-- End Logo Header -->
                </div>
                <!-- Navbar Header -->
                <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
                    <div class="container-fluid">
                        {{-- <nav
                            class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button type="submit" class="btn btn-search pe-1">
                                        <i class="fa fa-search search-icon"></i>
                                    </button>
                                </div>
                                <input type="text" placeholder="Search ..." class="form-control" />
                            </div>
                        </nav> --}}

                        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">

                            <li class="nav-item topbar-user dropdown hidden-caret">
                                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#"
                                    aria-expanded="false">
                                    <div class="avatar-sm">
                                        <img src="{{ asset('assets') }}/img/profile.jpg" alt="..."
                                            class="avatar-img rounded-circle" />
                                    </div>
                                    <span class="profile-username">
                                        <span class="op-7">Hi,</span>
                                        <span class="fw-bold">Test</span>
                                        {{-- <span class="fw-bold">{{ auth()->user()->name }}</span> --}}
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-user animated fadeIn">
                                    <div class="dropdown-user-scroll scrollbar-outer">
                                        <li>
                                            <div class="user-box">
                                                <div class="avatar-lg">
                                                    <img src="{{ asset('assets') }}/img/profile.jpg"
                                                        alt="image profile" class="avatar-img rounded" />
                                                </div>
                                                <div class="u-text">
                                                    <h4>Hizrian</h4>
                                                    {{-- <p class="text-muted">{{ auth()->user()->email }}</p> --}}
                                                    <p class="text-muted">Email</p>
                                                    {{-- <a href="profile.html"
                                                        class="btn btn-xs btn-secondary btn-sm">View
                                                        Profile</a> --}}
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="">Profile</a>
                                            <form action="{{ route('logout') }}" style="display: none;"
                                                method="POST" id="logout-form">
                                                @csrf
                                            </form>
                                            <a class="dropdown-item" href="{{ route('logout') }}"
                                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                                        </li>
                                    </div>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
                <!-- End Navbar -->
            </div>

            <div class="container">
                <div class="page-inner">
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                        <div>
                            <h3 class="fw-bold mb-3">{{ $title ?? 'Dashboard' }}</h3>
                            <h6 class="op-7 mb-2">
                                {{ $pageDescrition ?? 'Sistem Informasi Pemesanan Jasa Pengecoran' }}
                            </h6>
                        </div>
                        <div class="ms-md-auto py-2 py-md-0">
                            @yield('action-button')
                            {{-- <a href="#" class="btn btn-label-info btn-round me-2">Manage</a>
                            <a href="#" class="btn btn-primary btn-round">Add Customer</a> --}}
                        </div>
                    </div>

                    @yield('content')

                </div>
            </div>

            <footer class="footer">
                <div class="container-fluid d-flex justify-content-between">
                    <nav class="pull-left">
                        <ul class="nav">
                            <li class="nav-item">
                                <a class="nav-link" href="http://www.themekita.com">
                                    ThemeKita
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#"> Help </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#"> Licenses </a>
                            </li>
                        </ul>
                    </nav>
                    <div class="copyright">
                        2024, made with <i class="fa fa-heart heart text-danger"></i> by
                        <a href="http://www.themekita.com">ThemeKita</a>
                    </div>
                    <div>
                        Distributed by
                        <a target="_blank" href="https://themewagon.com/">ThemeWagon</a>.
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <!--   Core JS Files   -->
    <script src="{{ asset('assets') }}/js/core/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('assets') }}/js/core/popper.min.js"></script>
    <script src="{{ asset('assets') }}/js/core/bootstrap.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="{{ asset('assets') }}/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

    <!-- jQuery Sparkline -->
    {{-- <script src="{{ asset('assets') }}/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script> --}}

    <!-- Datatables -->
    <script src="{{ asset('assets') }}/js/plugin/datatables/datatables.min.js"></script>

    <!-- Bootstrap Notify -->
    <script src="{{ asset('assets') }}/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

    <!-- Sweet Alert -->
    <script src="{{ asset('assets') }}/js/plugin/sweetalert/sweetalert.min.js"></script>

    <!-- Kaiadmin JS -->
    <script src="{{ asset('assets') }}/js/kaiadmin.min.js"></script>

    <script>
        $('.datatable').DataTable();
    </script>

    @if (session('success'))
        <script>
            $(document).ready(function() {
                $.notify({
                    icon: 'fas fa-check',
                    title: 'Berhasil',
                    message: '{{ session('success') }}',
                }, {
                    type: 'success',
                    placement: {
                        from: "top",
                        align: "right"
                    },
                    time: 1000,
                });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            $(document).ready(function() {
                $.notify({
                    icon: 'fas fa-times',
                    title: 'Gagal',
                    message: '{{ session('error') }}',
                }, {
                    type: 'danger',
                    placement: {
                        from: "top",
                        align: "right"
                    },
                    time: 1000,
                });
            });
        </script>
    @endif
    @stack('scripts')
</body>

</html>
