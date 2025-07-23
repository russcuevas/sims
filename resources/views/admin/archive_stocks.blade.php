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
                            <li class="breadcrumb-item"><a style="color: #A16D28;" href="{{ route('admin.dashboard.page')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Archive Management</li>
                        </ol>
                    </div>
                </div>
                <!-- row -->


                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title" style="font-size: 20px; color: #A16D28;">
                                    Archive Management
                                </h4>
                            </div>

                            <div class="container my-4">
                                <!-- Status Buttons aligned to the right -->
                                <div class="row mb-3 justify-content-center">
                                    <div class="col-auto px-1">
                                        <a href="{{ route('admin.archive.page') }}" class="btn btn-outline-primary">Users</a>
                                    </div>
                                    <div class="col-auto px-1">
                                        <a href="{{ route('admin.archive.stocks.page') }}" class="btn btn-primary">Stocks</a>
                                    </div>
                                    <div class="col-auto px-1">
                                        <a href="{{ route('admin.archive.stock.in.page') }}" class="btn btn-outline-primary">Stock In History</a>
                                    </div>
                                    <div class="col-auto px-1">
                                        <a href="{{ route('admin.archive.process.page') }}" class="btn btn-outline-primary">Process History</a>
                                    </div>
                                    <div class="col-auto px-1">
                                        <a href="{{ route('admin.archive.delivery.page') }}" class="btn btn-outline-primary">Delivery History</a>
                                    </div>
                                    <div class="col-auto px-1">
                                        <a href="{{ route('admin.archive.sales.page') }}" class="btn btn-outline-primary">Sales Reports</a>
                                    </div>
                                </div>
                            </div>
                        

                            <div class="card-body">
                                <div class="container my-4">
                                    <div class="table-responsive">                                    
                                        <table id="example">
                                        <thead>
                                            <tr>
                                                <th style="color: #A16D28;">Date</th>
                                                <th style="color: #A16D28;">Quantity</th>
                                                <th style="color: #A16D28;">Product</th>
                                                <th style="color: #A16D28;">Unit</th>
                                                <th style="color: #A16D28;">Category</th>
                                                <th style="color: #A16D28;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($archivedStocks as $stock)
                                            <tr>
                                                <td style="color: black;">{{ \Carbon\Carbon::parse($stock->created_at)->format('Y-m-d') }}</td>
                                                <td style="color: black;">{{ $stock->quantity }}</td>
                                                <td style="color: black;">{{ $stock->product_name }}</td>
                                                <td style="color: black;">{{ $stock->stock_unit_id }}</td>
                                                <td style="color: black; text-transform: capitalize"> {{ $stock->category }}</td>
                                                <td>
                                                    <form action="{{ route('admin.stocks.restore', $stock->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-success btn-sm">Restore</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">No archived stock items found.</td>
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