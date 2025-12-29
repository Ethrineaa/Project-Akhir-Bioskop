<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIMINLAB - {{ $title ?? 'Dashboard' }}</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('template-admin/src/assets/images/logos/favicon.png') }}" />
    <link rel="stylesheet" href="{{ asset('template-admin/src/assets/css/styles.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('template-admin/src/assets/css/custom.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    @yield('css')
</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
        @include('admin.layouts.sidebar')
        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!--  Header Start -->
            @include('admin.layouts.header')
            <!--  Header End -->
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
        <script src="{{ asset('template-admin/src/assets/libs/jquery/dist/jquery.min.js') }}"></script>
        <script src="{{ asset('template-admin/src/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('template-admin/src/assets/js/sidebarmenu.js') }}"></script>
        <script src="{{ asset('template-admin/src/assets/js/app.min.js') }}"></script>
        <script src="{{ asset('template-admin/src/assets/libs/simplebar/dist/simplebar.js') }}"></script>
        @yield('js')
        <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script>
            @if (session('status'))
                swal({
                    title: '{{ session('title') }}',
                    text: '{{ session('message') }}',
                    icon: '{{ session('status') }}',
                });
            @endif
        </script>
        <script>
            $(document).ready(function() {
                const wrapper = $('#main-wrapper');

                // LOAD SIDEBAR MODE
                const savedMode = localStorage.getItem('sidebar-mode');
                if (savedMode === 'mini') {
                    wrapper.addClass('mini-sidebar');
                    wrapper.attr('data-sidebartype', 'mini-sidebar');
                }

                // TOGGLE SIDEBAR
                $('.sidebartoggler').on('click', function(e) {
                    e.preventDefault();

                    wrapper.toggleClass('mini-sidebar');

                    if (wrapper.hasClass('mini-sidebar')) {
                        wrapper.attr('data-sidebartype', 'mini-sidebar');
                        localStorage.setItem('sidebar-mode', 'mini');
                    } else {
                        wrapper.attr('data-sidebartype', 'full');
                        localStorage.setItem('sidebar-mode', 'full');
                    }
                });
            });
        </script>


</body>

</html>
