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
                                        <a href="{{ route('admin.archive.stocks.page') }}" class="btn btn-outline-primary">Stocks</a>
                                    </div>
                                    <div class="col-auto px-1">
                                        <a href="{{ route('admin.archive.stock.in.page') }}" class="btn btn-primary">Stock In History</a>
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
                                                <th style="color: #A16D28;">Details</th>
                                                <th style="color: #A16D28;">Process Date</th>
                                                <th style="color: #A16D28;">Received Date</th>
                                                <th style="color: #A16D28;">Processed By</th>
                                                <th style="color: #A16D28;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($historyGroups as $transactId => $group)
                                                @php
                                                    $first = $group->first();
                                                    $totalAmount = $group->sum('amount');
                                                @endphp

                                                <!-- Table Row -->
                                                <tr>
                                                    <td>
                                                        <a href="#" data-toggle="modal" data-target="#viewRawHistory{{ $transactId }}" class="btn btn-outline-primary btn-sm">View</a>
                                                    </td>
                                                    <td style="color: black">
                                                        {{ \Carbon\Carbon::parse($first->created_at)->toDateString() }}
                                                    </td>
                                                    <td style="color: black">
                                                        {{ \Carbon\Carbon::parse($first->received_date)->toDateString() }}
                                                    </td>
                                                    <td style="color: black">
                                                        {{ $first->process_by }}
                                                    </td>
                                                    <td>
                                                        <form action="{{ route('admin.stockin.restore', ['transact_id' => $transactId]) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-outline-success btn-sm">Restore</button>
                                                        </form>
                                                    </td>
                                                </tr>

                                                <!-- Modal outside <tr> -->
                                                <div class="modal fade" id="viewRawHistory{{ $transactId }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog modal-xl">
                                                        <div class="modal-content">

                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Transaction Details - {{ $transactId }}</h5>
                                                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <div class="p-4 position-relative">
                                                                    <h5>Details</h5>
                                                                    <div class="row mt-4">
                                                                        <div class="col-md-6">
                                                                            <p style="color: black" class="mb-1"><strong>Supplier:</strong> {{ $first->supplier_name }}</p>
                                                                            <p style="color: black" class="mb-1"><strong>Contact number:</strong> {{ $first->supplier_contact_num }}</p>
                                                                            <p style="color: black" class="mb-1"><strong>Email address:</strong> {{ $first->supplier_email_add }}</p>
                                                                            <p style="color: black" class="mb-1"><strong>Address:</strong> {{ $first->supplier_address }}</p>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <p style="color: black" class="mb-1"><strong>Process Date:</strong> {{ \Carbon\Carbon::parse($first->created_at)->format('F d, Y') }}</p>
                                                                            <p style="color: black" class="mb-1"><strong>Deliver Date:</strong> {{ \Carbon\Carbon::parse($first->received_date)->format('F d, Y') }}</p>
                                                                        </div>
                                                                    </div>

                                                                    <div class="mt-4">
                                                                        <div class="row fw-bold border-bottom pb-2">
                                                                            <div style="color: #A16D28; font-weight: 900;" class="col-2">Qty</div>
                                                                            <div style="color: #A16D28; font-weight: 900;" class="col-4">Product</div>
                                                                            <div style="color: #A16D28; font-weight: 900;" class="col-2">Unit</div>
                                                                            <div style="color: #A16D28; font-weight: 900;" class="col-2 text-end">Price</div>
                                                                            <div style="color: #A16D28; font-weight: 900;" class="col-2 text-end">Amount</div>
                                                                        </div>

                                                                        @foreach ($group as $item)
                                                                            <div class="row py-2 border-bottom">
                                                                                <div style="color: black" class="col-2">{{ $item->quantity }}</div>
                                                                                <div style="color: black" class="col-4">{{ $item->product_name }}</div>
                                                                                <div style="color: black" class="col-2">{{ $item->unit }}</div>
                                                                                <div style="color: black" class="col-2 text-end">₱{{ number_format($item->price, 2) }}</div>
                                                                                <div style="color: black" class="col-2 text-end">₱{{ number_format($item->amount, 2) }}</div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>

                                                                    <div class="row mt-4">
                                                                        <div class="col-md-6"></div>
                                                                        <div class="col-md-6 text-end">
                                                                            <p style="color: black" class="mb-2"><strong>Total amount:</strong> ₱{{ number_format($totalAmount, 2) }}</p>
                                                                            <p style="color: black" class="mb-0"><strong>Received by:</strong> {{ $first->process_by }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted">No archived stock-in records found.</td>
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