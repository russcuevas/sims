<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Sales & Inventory Management System </title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('partials/images/favicon.png') }}">
    <link href="{{ asset('partials/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('partials/css/style.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .col-form-label {
            color: black;
        }
    
        .select2-container--default .select2-selection--multiple .select2-selection__choice__display {
            cursor: default;
            padding-left: 2px;
            padding-right: 5px;
            color: black !important;
        }
        </style>
    </head>
    
    <body>
    
        <!--*******************
            Preloader start
        ********************-->
        <div id="preloader">
            <div class="sk-three-bounce">
                <div class="sk-child sk-bounce1"></div>
                <div class="sk-child sk-bounce2"></div>
                <div class="sk-child sk-bounce3"></div>
            </div>
        </div>
        <!--*******************
            Preloader end
        ********************-->
    
    
        <!--**********************************
                Main wrapper start
            ***********************************-->
        <div id="main-wrapper">
            {{-- right sidebar --}}
            @include('admin.right_sidebar')
            {{-- end right sidebar --}}
            
            {{-- left sidebar --}}
            @include('admin.left_sidebar')
            {{-- left sidebar end --}}

        <div class="content-body">
            <div class="container-fluid">
                <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="welcome-text">

                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a style="color: #A16D28;" href="{{ route('admin.delivery.management.page') }}">Delivery Management</a></li>
                            <li class="breadcrumb-item active">View Available Cars</li>
                        </ol>
                    </div>
                </div>
                <!-- row -->


                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title m-0" style="font-size: 20px; color: #A16D28;">
                                    View Available Cars
                                </h4>
                            </div>

                            <div class="card-body">


                                <div class="table-responsive">
                                    <div class="d-flex flex-wrap justify-content-center gap-2 mb-3">
                                        <form method="GET" action="{{ route('admin.view.available.cars') }}" class="d-flex gap-2 align-items-center mb-3">
                                            <input type="text" name="search" value="{{ request('search') }}" class="form-control w-auto" placeholder="Search car or plate">

                                            <!-- Filter Dropdown -->
                                            <div class="dropdown me-2">
                                                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Filter Available
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                                                    <li><a class="dropdown-item" href="{{ route('admin.view.available.cars', ['filter' => 'Available']) }}">Available</a></li>
                                                    <li><a class="dropdown-item" href="{{ route('admin.view.available.cars', ['filter' => 'Not Available']) }}">Not Available</a></li>
                                                    <li><a class="dropdown-item" href="{{ route('admin.view.available.cars') }}">Clear Filter</a></li>
                                                </ul>
                                            </div>

                                            <!-- Sort Dropdown -->
                                            <div class="dropdown">
                                                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Sort
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                                                    <li><a class="dropdown-item" href="{{ route('admin.view.available.cars', ['sort' => 'asc']) }}">Availability Ascending</a></li>
                                                    <li><a class="dropdown-item" href="{{ route('admin.view.available.cars', ['sort' => 'desc']) }}">Availability Descending</a></li>
                                                    <li><a class="dropdown-item" href="{{ route('admin.view.available.cars') }}">Clear Sort</a></li>
                                                </ul>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Apply</button>
                                        </form>

                                        
                                    </div>
                                    <table class="table table-bordered table-responsive-sm">
                                        <thead>
                                            <tr>
                                                <th style="color: #A16D28;">Car</th>
                                                <th style="color: #A16D28;">Plate number</th>
                                                <th style="color: #A16D28;">Availability</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($cars as $car)
                                                <tr>
                                                    <td style="color: black">{{ $car->car }}</td>
                                                    <td style="color: black">{{ $car->plate_number }}</td>
                                                    <td class="{{ $car->status === 'Available' ? 'text-success' : 'text-danger' }}">
                                                        {{ $car->status }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted">No cars available.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <a href="{{ route('admin.delivery.management.page') }}" style="color: white; cursor: pointer;" class="btn btn-primary float-right">Go back</a>
                                </div>
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

    <!-- REQUIRED VENDORS -->
    <script src="{{ asset('partials/vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('partials/js/quixnav-init.js') }}"></script>
    <script src="{{ asset('partials/js/custom.min.js') }}"></script>
    <!-- Bootstrap 5 JS + Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <!-- JQUERY VALIDATION -->
    <script src="{{ asset('partials/vendor/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
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


</body>

</html>