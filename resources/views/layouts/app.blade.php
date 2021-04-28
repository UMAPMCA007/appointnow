<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="{{ env('APP_NAME') }}">
    <meta name="author" content="Javier Mercedes">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', env('APP_NAME'))</title>

    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

    <!-- Custom fonts for this template-->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template-->
    <link href="{{ asset('vendor/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <!-- DataTables -->
    <link href="{{ asset('vendor/datatables/datatables/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('vendor/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/select2/select2-bootstrap4.min.css') }}">

    <!-- Fullcalendar CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/fullcalendar/main.min.css') }}">
</head>
<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">
@if(auth()->guard("business")->check())
    @include('business.layout.sidebar')
@elseif(auth()->guard('staff')->check())
    @include('staff.layout.sidebar')
@else
    @include('admin.layout.sidebar')
@endif
<!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
        <!-- Main Content -->
        <div id="content">
        @if(auth()->guard("business")->check())
            @include('business.layout.header')
        @elseif(auth()->guard('staff')->check())
            @include('staff.layout.header')
        @else
            @include('admin.layout.header')
        @endif
        <!-- Begin Page Content -->
            <div class="container-fluid">
                @yield('content')
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- End of Main Content -->
        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; {{ env('APP_NAME') }} 2021</span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->
    </div>
    <!-- End of Content Wrapper -->
</div>
<!-- End of Page Wrapper -->

<!-- Bootstrap core JavaScript-->
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Core plugin JavaScript-->
<script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

<!-- Custom scripts for all pages-->
<script src="{{ asset('vendor/js/sb-admin-2.min.js') }}"></script>

<!-- Moment JS -->
<script src="{{ asset('vendor/moment/moment-with-locales.js') }}"></script>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

<!-- dataTables -->
<script src="{{ asset('vendor/datatables/datatables.min.js') }}"></script>

<!-- Select2 -->
<script src="{{ asset('vendor/select2/select2.min.js') }}"></script>

<!-- FullCalendar JS -->
<script src="{{ asset('vendor/fullcalendar/main.min.js') }}"></script>
@if(session()->get('language') == App\Enums\LanguageType::SPANISH)
    <script src="{{ asset('vendor/fullcalendar/locales/es.js') }}"></script>
@else
    <script src="{{ asset('vendor/fullcalendar/locales/en-au.js') }}"></script>
@endif

@yield('javascript')
</body>
</html>
