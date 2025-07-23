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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
        @include('delivery.right_sidebar')
        {{-- end right sidebar --}}

        {{-- left sidebar --}}
        @include('delivery.left_sidebar')
        {{-- left sidebar end --}}

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-6 mx-auto">
                        <div class="card shadow">
                            <div class="card-header bg-primary">
                                <h4 class="mb-2 text-white">Update Profile</h4>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('delivery.profile.update.request') }}">
                                    @csrf

                                    <div class="form-group">
                                        <label style="color: black" for="email" class="form-label">Email Address</label>
                                        <input type="email" name="email" class="form-control" id="email"
                                            value="{{ old('email', $user->email) }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label style="color: black" for="username" class="form-label">Username</label>
                                        <input type="text" name="username" class="form-control" id="username"
                                            value="{{ old('username', $user->username) }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label style="color: black" for="password" class="form-label">New Password <small style="color: red !important" class="text-muted">(Leave blank to keep current)</small></label>
                                        <input type="password" name="password" class="form-control" id="password">
                                    </div>

                                    <div class="form-group">
                                        <label style="color: black" for="password_confirmation" class="form-label">Confirm Password</label>
                                        <input type="password" name="password_confirmation" class="form-control" id="password_confirmation">
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if(session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif
    </script>
    <script src="{{ asset('partials/js/dashboard/dashboard-1.js') }}"></script>

</body>

</html>