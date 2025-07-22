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

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="container-fluid">
                <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="welcome-text">

                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a style="color: blueviolet;" href="{{ route('admin.dashboard.page')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Delivery Management</li>
                        </ol>
                    </div>
                </div>
                <!-- row -->


                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title" style="font-size: 20px; color: blueviolet;">
                                    Archive Management
                                </h4>
                            </div>

                            <div class="container my-4">
                                <!-- Status Buttons aligned to the right -->
                                <div class="row mb-3 justify-content-center">
                                    <div class="col-auto px-1">
                                        <a href="{{ route('admin.archive.page') }}" class="btn btn-secondary">Users</a>
                                    </div>
                                    <div class="col-auto px-1">
                                        <a href="{{ route('admin.archive.stocks.page') }}" class="btn btn-outline-secondary">Stocks</a>
                                    </div>
                                    <div class="col-auto px-1">
                                        <a href="{{ route('admin.archive.stock.in.page') }}" class="btn btn-outline-secondary">Stock In History</a>
                                    </div>
                                    <div class="col-auto px-1">
                                        <a href="" class="btn btn-outline-secondary">Process History</a>
                                    </div>
                                    <div class="col-auto px-1">
                                        <a href="" class="btn btn-outline-secondary">Delivery History</a>
                                    </div>
                                    <div class="col-auto px-1">
                                        <a href="" class="btn btn-outline-secondary">Sales Reports</a>
                                    </div>
                                </div>
                            </div>
                        

                            <div class="card-body">
                                <div class="container my-4">
                                    <div class="table-responsive">                                    
                                        <table id="example">
                                        <thead>
                                            <tr>
                                                <th style="color: #593bdb;">Image</th>
                                                <th style="color: #593bdb;">Name</th>
                                                <th style="color: #593bdb;">Role</th>
                                                <th style="color: #593bdb;">Contract</th>
                                                <th style="color: #593bdb;">Email</th>
                                                <th style="color: #593bdb;">Username</th>
                                                <th style="color: #593bdb;">Pin</th>
                                                <th style="color: #593bdb;">Attempt</th>
                                                <th style="color: #593bdb;">Status</th>
                                                <th style="color: #593bdb;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($archivedEmployees as $employee)
                                                <tr>
                                                    <td style="color: black;">
                                                        <img src="https://images.rawpixel.com/image_png_800/czNmcy1wcml2YXRlL3Jhd3BpeGVsX2ltYWdlcy93ZWJzaXRlX2NvbnRlbnQvdjkzNy1hZXctMTY1LnBuZw.png?s=b4SEVfKYcskH9PiGnSKmpM9SloVv-yAI_PKnNBsL-3o" alt="Default Image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                                                    </td>
                                                    <td style="color: black;">{{ $employee->employee_firstname }} {{ $employee->employee_lastname }}</td>
                                                    <td style="color: black;">{{ $employee->position_name ?? 'N/A' }}</td>
                                                    <td style="color: black;">{{ $employee->contract ?? 'N/A' }}</td>
                                                    <td style="color: black;">{{ $employee->email }}</td>
                                                    <td style="color: black;">{{ $employee->username }}</td>
                                                    <td style="color: black;">{{ $employee->pin }}</td>
                                                    <td style="color: black;">{{ $employee->login_attempts }}</td>
                                                    <td style="color: black;">
                                                        @if($employee->status == 'active')
                                                            <span class="badge bg-success">Active</span>
                                                        @else
                                                            <span class="badge bg-warning">Inactive</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <form action="{{ route('admin.employees.restore', $employee->id) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-outline-success btn-sm">
                                                                Restore
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="10" class="text-center text-muted">No archived employees found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                        </table>                                    
                                    </div>
                                </div>
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

    <!-- SCRIPT -->
    <!-- REQUIRED VENDORS -->
    <script src="{{ asset('partials/vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('partials/js/quixnav-init.js') }}"></script>
    <script src="{{ asset('partials/js/custom.min.js') }}"></script>
    <!-- JQUERY VALIDATION -->
    <script src="{{ asset('partials/vendor/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('partials/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#example').DataTable({
                pageLength: 10,
                responsive: true,
                autoWidth: false,
                ordering: true,
                order: [], // Prevent default sorting on first column
                language: {
                    searchPlaceholder: "Search records...",
                    search: "",
                }
            });
        });
    </script>

    
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

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>