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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
  /* Ensure all text in the table body is black */
  table tbody {
    color: black;
  }

  /* You can also target table cells directly if you prefer */
  table tbody td {
    color: black;
  }
</style>

<style>
  /* Ensure all text in the table body is black */
  table tbody {
    color: black;
  }

  /* You can also target table cells directly if you prefer */
  table tbody td {
    color: black;
  }
</style>

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

                                    <a href="{{ route('admin.sales.report.print') }}" 
                                            class="btn btn-outline-primary float-right ml-2 mb-2">
                                        Print Sales
                                    </a>

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
                                                                    <label for="type" class="col-sm-4 col-form-label text-end">
                                                                        Transaction Type <span class="text-danger">*</span>
                                                                    </label>
                                                                    <div class="col-sm-8">
                                                                    <select class="form-control" id="transaction_type" name="transaction_type" required>
                                                                        <option value="">-- Select Transaction Type --</option>
                                                                        <option value="return-item" selected>Return</option>
                                                                    </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row mb-3">
                                                                    <label for="transaction_id" class="col-sm-4 col-form-label text-end">
                                                                        Transaction ID <span class="text-danger">*</span>
                                                                    </label>
                                                                    <div class="col-sm-8">
                                                                        <select class="form-control" id="transaction_id" name="transaction_id" required>
                                                                            <option value="">Select Transaction ID</option>
                                                                            @foreach ($transactionIds as $transactionId)
                                                                                <option value="{{ $transactionId }}">{{ $transactionId }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row mb-3" id="amount_field">
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
                                    @php
                                        $totalDebit = 0;
                                        $totalCredit = 0;
                                        $totalStockInBalance = DB::table('sales_transactions')
                                            ->where('transaction_type', 'stock in')
                                            ->sum('balances');
                                    @endphp

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
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($stockInSalesTransaction as $transaction)
                                                @php
                                                    $totalDebit += $transaction->debit;
                                                    $totalCredit += $transaction->credit;
                                                @endphp
                                                <tr>
                                                    <td>{{ $transaction->transaction_date }}</td>
                                                    <td>{{ $transaction->process_by }}</td>
                                                    <td><span style="text-transform: capitalize">{{ $transaction->transaction_type }}</span></td>
                                                    <td>{{ $transaction->transaction_id }}</td>
                                                    <td>{{ number_format($transaction->debit, 2) }}</td>
                                                    <td>{{ number_format($transaction->credit, 2) }}</td>
                                                    <td>{{ number_format($transaction->balances, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                        <tfoot>
                                            <tr style="color: black">
                                                <td colspan="4" style="text-align: right; font-weight: bold;">Sub-total</td>
                                                <td style="font-weight: bold;">{{ number_format($totalDebit, 2) }}</td>
                                                <td style="font-weight: bold;">{{ number_format($totalCredit, 2) }}</td>
                                                <td style="font-weight: bold;">{{ number_format($totalStockInBalance, 2) }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>


                                    @php
                                        $runningDebit = 0;
                                        $runningCredit = 0;
                                        $runningBalance = 0;

                                        $sortedTransactions = $allSalesTransactions->sortBy(function ($transaction) {
                                            return match ($transaction->transaction_type) {
                                                'return-item' => 2, // show last
                                                'payment' => 1,     // show second last
                                                default => 0,       // show first
                                            };
                                        });
                                    @endphp

                                    <table class="table table-bordered table-responsive-sm mt-2">
                                        <thead>
                                            <tr>
                                                <th style="color: #A16D28;">Transaction date</th>
                                                <th style="color: #A16D28;">Process by</th>
                                                <th style="color: #A16D28;">Transaction type</th>
                                                <th style="color: #A16D28;">Transaction ID</th>
                                                <th style="color: #A16D28;">Debit</th>
                                                <th style="color: #A16D28;">Credit</th>
                                                <th style="color: #A16D28;">Balance</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($sortedTransactions as $transaction)
                                                @php
                                                    // accumulate totals
                                                    $runningDebit += $transaction->debit;
                                                    $runningCredit += $transaction->credit;

                                                    // formula: previous balance + debit - credit
                                                    $runningBalance += $transaction->debit - $transaction->credit;
                                                @endphp
                                                <tr>
                                                    <td>{{ $transaction->transaction_date }}</td>
                                                    <td>{{ $transaction->process_by }}</td>
                                                    <td>{{ ucfirst($transaction->transaction_type) }}</td>
                                                    <td>{{ $transaction->transaction_id }}</td>
                                                    <td>{{ number_format($transaction->debit, 2) }}</td>
                                                    <td>{{ number_format($transaction->credit, 2) }}</td>
                                                    <td>{{ number_format($runningBalance, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr style="color: black">
                                                <td colspan="4" class="text-right font-weight-bold">Sub-total</td>
                                                <td class="font-weight-bold">{{ number_format($runningDebit, 2) }}</td>
                                                <td class="font-weight-bold">{{ number_format($runningCredit, 2) }}</td>
                                                <td class="font-weight-bold">{{ number_format($runningBalance, 2) }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>



                                        @php
                                            $totalLoss = 0;
                                        @endphp

                                        <table class="table table-bordered table-responsive-sm mt-2">
                                            <thead>
                                                <tr>
                                                    <th style="color: #A16D28;">Transaction date</th>
                                                    <th style="color: #A16D28;">Process by</th>
                                                    <th style="color: #A16D28;">Transaction type</th>
                                                    <th style="color: #A16D28;">Transaction ID</th>
                                                    <th style="color: #A16D28;">Loss</th>
                                                    <th style="color: #A16D28;">Debit</th>
                                                    <th style="color: #A16D28;">Credit</th>
                                                    <th style="color: #A16D28;">Balance</th>
                                                </tr>
                                            </thead>
                                        <tbody>
                                            @foreach ($allSalesTransactions as $transaction)
                                                @if ($transaction->loss > 0)
                                                    @php $totalLoss += $transaction->loss; @endphp
                                                    <tr>
                                                        <td>{{ $transaction->transaction_date }}</td>
                                                        <td>{{ $transaction->process_by }}</td>
                                                        <td>{{ ucfirst($transaction->transaction_type) }}</td>
                                                        <td>{{ $transaction->transaction_id }}</td>
                                                        <td style="color: red; font-weight: bold;">
                                                            {{ number_format($transaction->loss, 2) }}
                                                        </td>
                                                        <td>0.00</td>
                                                        <td>0.00</td>
                                                        <td>{{ number_format($transaction->loss, 2) }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>

                                        <tfoot>
                                            <tr style="color: black">
                                                <td colspan="4" class="text-right font-weight-bold">Total Loss</td>
                                                <td class="font-weight-bold text-danger">{{ number_format($totalLoss, 2) }}</td>
                                                <td>{{ number_format(0, 2) }}</td>
                                                <td>{{ number_format(0, 2) }}</td>
                                                <td class="font-weight-bold text-danger">{{ number_format($totalLoss, 2) }}</td>
                                            </tr>
                                        </tfoot>
                                        </table>

                                        @php
                                            $totalProfit = $runningCredit - $totalDebit - $totalLoss;
                                        @endphp

                                        <table class="table table-bordered table-responsive-sm mt-2">
                                            <tfoot>
                                                <tr>
                                                    <td colspan="7" class="text-right font-weight-bold" style="color: green; font-size: 16px;">
                                                        Total Profit
                                                    </td>
                                                    <td class="font-weight-bold" style="color: green; font-size: 16px;">
                                                        {{ number_format($totalProfit, 2) }}
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#transaction_id').select2({
                placeholder: "Search purchase orders",
                allowClear: true,
                width: 'resolve'
            });
        });
    </script>
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
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('transaction_date').value = today;
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