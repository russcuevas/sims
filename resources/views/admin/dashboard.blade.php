<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Sales & Inventory Management System </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="">
    <link rel="stylesheet" href="{{ asset('partials/vendor/owl-carousel/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('partials/vendor/owl-carousel/css/owl.theme.default.min.css') }}">
    <link href="{{ asset('partials/vendor/jqvmap/css/jqvmap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('partials/css/style.css') }}" rel="stylesheet">
</head>

<body>

    {{-- loader start --}}
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    {{-- end loader --}}
    <div id="main-wrapper">
        {{-- right sidebar --}}
        @include('admin.right_sidebar')
        {{-- end right sidebar --}}

        {{-- left sidebar --}}
        @include('admin.left_sidebar')
        {{-- left sidebar end --}}

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->
            <div class="container-fluid">
                <h1>Dashboard</h1>
            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->

        <!--**********************************
           Support ticket button start
        ***********************************-->

        <!--**********************************
           Support ticket button end
        ***********************************-->


    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="{{ asset('partials/vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('partials/js/quixnav-init.js') }}"></script>
    <script src="{{ asset('partials/js/custom.min.js') }}"></script>


    <!-- Vectormap -->
    <script src="{{ asset('partials/vendor/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('partials/vendor/morris/morris.min.js') }}"></script>


    <script src="{{ asset('partials/vendor/circle-progress/circle-progress.min.js') }}"></script>
    <script src="{{ asset('partials/vendor/chart.js/Chart.bundle.min.js') }}"></script>

    <script src="{{ asset('partials/vendor/gaugeJS/dist/gauge.min.js') }}"></script>

    <!--  flot-chart js -->
    <script src="{{ asset('partials/vendor/flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('partials/vendor/flot/jquery.flot.resize.js') }}"></script>

    <!-- Owl Carousel -->
    <script src="{{ asset('partials/vendor/owl-carousel/js/owl.carousel.min.js') }}"></script>

    <!-- Counter Up -->
    <script src="{{ asset('partials/vendor/jqvmap/js/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('partials/vendor/jqvmap/js/jquery.vmap.usa.js') }}"></script>
    <script src="{{ asset('partials/vendor/jquery.counterup/jquery.counterup.min.js') }}"></script>


    <script src="{{ asset('partials/js/dashboard/dashboard-1.js') }}"></script>

</body>

</html>