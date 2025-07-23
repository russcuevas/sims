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
    <link href="{{ asset('partials/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">

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
                            <li class="breadcrumb-item"><a style="color: #A16D28;" href="{{ route('admin.dashboard.page')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Sales Report Management</li>
                        </ol>
                    </div>
                </div>
                <!-- row -->


                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title m-0" style="font-size: 20px; color: #A16D28;">
                                    Sales Report Management
                                </h4>
                            </div>

                            <div class="card-body">


                                <div class="table-responsive">
                                    <div class="d-flex flex-wrap justify-content-center gap-2 mb-3">
                                            <form method="GET" action="" class="d-flex gap-2 align-items-center mb-3">
                                            <input type="text" name="search" value="" class="form-control w-auto" placeholder="Search here">
                                            <button type="submit" class="btn btn-primary mr-2">Search</button>

                                            {{-- Filter Dropdown --}}
                                            <div class="dropdown">
                                                <button class="btn btn-outline-primary dropdown-toggle mr-2" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Filter by Category

                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="filterDropdown">

                                                </ul>
                                            </div>
                                        
                                            {{-- Sort Dropdown --}}
                                            <div class="dropdown">
                                                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Sort
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="sortDropdown">

                                                </ul>
                                            </div>
                                    
                                        
                                        </form>
                                    </div>
                                    <button id="add_supplier_button"
                                            data-toggle="modal" data-target="#add_transaction_modal" 
                                            class="btn btn-outline-primary float-right mb-2">
                                        Add Transaction
                                    </button>

                                    <div class="modal fade" id="add_transaction_modal">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Add Transaction</h5>
                                                        <button type="button" class="close"
                                                            data-dismiss="modal"><span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="modal-body">
                                                            <form class="add_transaction_validation" action="{{ route('admin.sales.request.transaction') }}" method="POST">
                                                                @csrf
                                                                <div class="form-group row mb-3">
                                                                    <label for="transaction_date"
                                                                        class="col-sm-4 col-form-label text-end">
                                                                        Transaction Date <span class="text-danger">*</span>
                                                                    </label>
                                                                    <div class="col-sm-8">
                                                                        <input type="date" class="form-control"
                                                                            id="transaction_date" name="transaction_date"
                                                                            required>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row mb-3">
                                                                    <label for="process_by"
                                                                        class="col-sm-4 col-form-label text-end">
                                                                        Process by <span class="text-danger">*</span>
                                                                    </label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control" id="process_by" name="process_by"
                                                                            value="{{ $user->employee_firstname . ' ' . $user->employee_lastname }}" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row mb-3">
                                                                    <label for="transaction_type"
                                                                        class="col-sm-4 col-form-label text-end">
                                                                        Transaction Type <span class="text-danger">*</span>
                                                                    </label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control"
                                                                            id="transaction_type"
                                                                            name="transaction_type"
                                                                            placeholder="Enter transaction type" required>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row mb-3">
                                                                    <label for="transaction_id"
                                                                        class="col-sm-4 col-form-label text-end">
                                                                        Transaction ID <span class="text-danger">*</span>
                                                                    </label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control"
                                                                            id="transaction_id"
                                                                            name="transaction_id"
                                                                            placeholder="Enter transaction type" required>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row mb-3">
                                                                    <label for="type" class="col-sm-4 col-form-label text-end">
                                                                        Debit/Credit <span class="text-danger">*</span>
                                                                    </label>
                                                                    <div class="col-sm-8">
                                                                        <select class="form-control" id="type" name="type" required>
                                                                            <option value="">-- Select Type --</option>
                                                                            <option value="credit">Credit</option>
                                                                            <option value="debit">Debit</option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <!-- Input Amount (initially hidden) -->
                                                                <div class="form-group row mb-3" id="amount_field" style="display: none;">
                                                                    <label for="amount" class="col-sm-4 col-form-label text-end">
                                                                        Input Amount: <span class="text-danger">*</span>
                                                                    </label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control" id="amount" name="amount" placeholder="Enter amount">
                                                                    </div>
                                                                </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger"
                                                                data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Save
                                                                changes</button>
                                                        </div>
                                                        </form>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <table class="table table-bordered table-responsive-sm">
                                        <thead>
                                            <tr>
                                                <th style="color: #A16D28;">Transaction date</th>
                                                <th style="color: #A16D28;">Process by</th>
                                                <th style="color: #A16D28;">Transaction type</th>
                                                <th style="color: #A16D28;">Transaction ID</th>
                                                <th style="color: #A16D28;">Debit</th>
                                                <th style="color: #A16D28;">Credit</th>
                                                <th style="color: #A16D28;">Balances</th>
                                                <th style="color: #A16D28;">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse($transactions as $transaction)
                                                <tr>
                                                    <td style="color: black">{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('Y-m-d') }}</td>
                                                    <td style="color: black">{{ $transaction->process_by }}</td>
                                                    <td style="color: black">{{ $transaction->transaction_type }}</td>
                                                    <td style="color: black">{{ $transaction->transaction_id }}</td>
                                                    <td style="color: black">₱{{ number_format($transaction->debit, 2) }}</td>
                                                    <td style="color: black">₱{{ number_format($transaction->credit, 2) }}</td>
                                                    <td style="color: black">₱{{ number_format($transaction->balances, 2) }}</td>
                                                    <td>
                                                        <form action="{{ route('admin.transaction.archive', $transaction->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to archive this transaction?')">Archive</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center">No transactions found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>

                                        <tfoot>
                                            <tr>
                                                <th colspan="4" class="text-end" style="color: #A16D28;">Total:</th>
                                                <th style="color: #A16D28;">₱{{ number_format($transactions->sum('debit'), 2) }}</th>
                                                <th style="color: #A16D28;">₱{{ number_format($transactions->sum('credit'), 2) }}</th>
                                                <td style="color: 
                                                    @if(isset($transaction) && $transaction->balances < 0) 
                                                        #A16D28
                                                    @elseif(isset($transaction)) 
                                                        #A16D28
                                                    @else 
                                                        inherit
                                                    @endif
                                                ">
                                                    @if(isset($transaction) && $transaction->balances !== null)
                                                        @if($transaction->balances < 0)
                                                            ₱-{{ number_format(abs($transaction->balances), 2) }}
                                                        @else
                                                            ₱{{ number_format($transaction->balances, 2) }}
                                                        @endif
                                                    @else
                                                            <span style="color: #A16D28; font-weight: 900">₱0.00</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
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
    <script src="{{ asset('partials/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>

    {{-- CREDIT OR DEBIT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const transactionType = document.getElementById('type');
            const amountField = document.getElementById('amount_field');

            transactionType.addEventListener('change', function () {
                const value = this.value.toLowerCase();
                if (value === 'credit' || value === 'debit') {
                    amountField.style.display = 'flex';
                    amountField.querySelector('input').required = true;
                } else {
                    amountField.style.display = 'none';
                    amountField.querySelector('input').required = false;
                    amountField.querySelector('input').value = '';
                }
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#poTable').DataTable({
                pageLength: 10,
                responsive: true,
            });
        });
    </script>
    
    <!-- ADD TRANSACTION VALIDATION -->
    <script>
        $(document).ready(function () {
            $(".add_transaction_validation").validate({
                ignore: [],
                errorClass: "invalid-feedback animated fadeInUp",
                errorElement: "div",
                errorPlacement: function (error, element) {
                    $(element).parents(".form-group > div").append(error);
                },
                highlight: function (element) {
                    $(element).closest(".form-group").addClass("is-invalid").removeClass("is-valid");
                },
                unhighlight: function (element) {
                    $(element).closest(".form-group").removeClass("is-invalid").addClass("is-valid");
                }
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            const today = new Date().toISOString().split('T')[0];
            $('#process_date').val(today);
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


</body>

</html>