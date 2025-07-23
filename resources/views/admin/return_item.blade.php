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
                            <li class="breadcrumb-item active">Delivery Management</li>
                        </ol>
                    </div>
                </div>
                <!-- row -->


                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title" style="font-size: 20px; color: #A16D28;">
                                    Delivery Management
                                </h4>
                            </div>

                            <div class="container my-4">
                                <!-- Status Buttons aligned to the right -->
                                <div class="row mb-3 justify-content-center">
                                    <div class="col-auto px-1">
                                        <a href="{{ route('admin.delivery.management.page') }}" class="btn btn-outline-primary" id="status_preparing">Preparing</a>
                                    </div>
                                    <div class="col-auto px-1">
                                        <a href="{{ route('admin.return.item.page') }}" class="btn btn-primary" id="status_to_ship">Return item</a>
                                    </div>
                                    <div class="col-auto px-1">
                                        <a href="{{ route('admin.pending.management.page') }}" class="btn btn-outline-primary" id="status_delivered">Pending delivery</a>
                                    </div>
                                    <div class="col-auto px-1">
                                        <a href="{{ route('admin.delivery.status.page') }}" class="btn btn-outline-primary" id="status_return">Delivery Status</a>
                                    </div>
                                </div>
                            </div>
                        

                            <div class="card-body">
                                <div class="container my-4">


                                    
                                <form action="{{ route('admin.return.submit.item') }}" method="POST">
                                        @csrf
                                        <div class="d-flex gap-2" style="width: 100%;">
                                            <select id="product_select" name="products[]" class="form-control" multiple>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}">
                                                        {{ $product->product_name }} | {{ $product->stock_unit_id }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>


                                <form action="{{ route('admin.return.submit') }}" method="POST">
                                    @csrf                                    
                                    <div class="row mb-3 align-items-end">
                                        <div class="col-md-3">
                                            <label for="transaction_date" class="form-label"
                                                style="color: #A16D28;">Transaction Date</label>
                                            <input type="date" id="transaction_date" name="transaction_date"
                                                class="form-control">
                                        </div>

                                        <div class="col-md-3 text-left">
                                            <label style="color: #A16D28;" for="process_by" class="form-label">Process
                                                by</label>
                                            <input type="text" id="process_by" name="process_by" class="form-control"
                                            value="{{ $user->employee_firstname }} {{ $user->employee_lastname }}" readonly>
                                        </div>


                                        <div class="col-md-3 text-left" style="margin-top: 20px">
                                            <label style="color: #A16D28;" for="picked_up_by" class="form-label">Picked up by</label>
                                            <select id="picked_up_by" name="picked_up_by" class="form-control">
                                                @foreach ($allEmployees as $employee)
                                                    @if ($employee->position_id == 3) 
                                                        <option value="{{ $employee->id }}">
                                                            {{ $employee->employee_firstname }} {{ $employee->employee_lastname }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3 text-left">
                                            <label style="color: #A16D28;" for="store" class="form-label">Select Store</label>
                                            <select id="store" name="store" class="form-control">
                                                @foreach ($stores as $store)
                                                    <option value="{{ $store->id }}">
                                                        {{ $store->store_name }} - {{ $store->store_code }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                



                                <div class="table-responsive">
                                        <table class="table table-bordered table-responsive-sm">
                                        <thead>
                                            <tr>
                                                <th style="color: #A16D28;">Product</th>
                                                <th style="color: #A16D28;">Quantity</th>
                                                <th style="color: #A16D28;">Unit</th>
                                                <th style="color: #A16D28;">Price</th>
                                                <th style="color: #A16D28;">Amount</th>
                                                <th style="color: #A16D28;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @forelse ($batchProducts as $item)
                                            <tr>
                                                <td style="color: black;">{{ $item->product_name }}</td>
                                                <td>
                                                    <input style="border-color: #A16D28; width: 100px;" type="number" class="form-control input-rounded" name="quantity[]" min="1" value="1">
                                                </td>
                                                <td style="color: black;">{{ $item->stock_unit_id }}</td>
                                                <td style="color: black;">₱ {{ number_format($item->price, 2) }}</td>
                                                <td style="color: black;">
                                                    <span class="amount-display">₱ 0.00</span>
                                                    <input type="hidden" name="amount[]" value="">
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.batch-return-item.delete', $item->id) }}" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to remove this item?');">
                                                                <i class="fa fa-close"></i> Remove
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">No returned products yet.</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3" class="text-end fw-bold" style="color: #A16D28;"></td>
                                                <td colspan="3" class="fw-bold" style="color: black;">
                                                    <span style="color: red;">Total Amount:</span>
                                                    <span style="color: red" id="total_amount_display">0.00</span>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                        <button type="submit" class="btn btn-primary float-right">Submit</button>
                                    </form>
                                    

                                </div>
                                <hr class="my-4">

                                <h5 class="text-center text-primary">History</h5>

                                <!-- Search bar and actions -->
                                <div class="d-flex flex-wrap justify-content-center gap-2 mb-3">
                                    <form method="GET" action="" class="d-flex flex-wrap justify-content-center gap-2 mb-3" id="filterSortForm">
                                        <input type="text" name="search" value="{{ request('search') }}" class="form-control w-auto" placeholder="Search product here">
                                        <button type="submit" class="btn btn-primary mr-2">Search</button>

                                        <select name="process_by" class="btn btn-outline-primary dropdown-toggle mr-2" onchange="document.getElementById('filterSortForm').submit()">
                                            <option value="">Filter by Processor</option>
                                            @foreach ($allEmployees as $employee)
                                                @php
                                                    $fullName = $employee->employee_firstname . ' ' . $employee->employee_lastname;
                                                @endphp
                                                <option value="{{ $fullName }}" {{ request('process_by') == $fullName ? 'selected' : '' }}>
                                                    {{ $fullName }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <select name="sort" class="btn btn-outline-primary dropdown-toggle" onchange="document.getElementById('filterSortForm').submit()">
                                            <option value="">Sort by Date</option>
                                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                        </select>
                                    </form>

                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered text-center align-middle">
                                        <thead class="table-light fw-bold">
                                            <tr>
                                                <th style="width: 10%; color: #A16D28;">Details</th>
                                                <th style="width: 15%; color: #A16D28;">Transaction Date</th>
                                                <th style="width: 20%; color: #A16D28;">Processed By</th>
                                                <th style="width: 20%; color: #A16D28;">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($historyReturns as $transactId => $group)
                                                @php
                                                    $first = $group->first();
                                                    $totalAmount = $group->sum('amount');
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <button type="button" data-toggle="modal" data-target="#viewReturnHistory{{ $transactId }}" class="btn btn-outline-primary btn-sm">View</button>
                                                    </td>
                                                    <td style="color: black;">
                                                        {{ \Carbon\Carbon::parse($first->transaction_date)->format('m/d/Y') }}
                                                    </td>
                                                    <td style="color: black;">{{ $first->process_by }}</td>
                                                    <td><span class="badge bg-success text-white">Returned</span></td>
                                                </tr>

                                                <!-- Modal -->
                                                <div class="modal fade" id="viewReturnHistory{{ $transactId }}" tabindex="-1" role="dialog" aria-hidden="true">
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
                                                                            <p style="color: black;" class="mb-1"><strong>Processed by:</strong> {{ $first->process_by }}</p>
                                                                            <p style="color: black;" class="mb-1">
                                                                                <strong>Pick-up by:</strong> 
                                                                                {{ $first->employee_firstname ?? 'N/A' }} {{ $first->employee_lastname ?? '' }}
                                                                            </p>
                                                                        </div>
                                                                        <div class="col-md-6 text-md-end">
                                                                            <p style="color: black;" class="mb-1"><strong>Process Date:</strong> {{ \Carbon\Carbon::parse($first->transaction_date)->format('F d, Y') }}</p>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Products Section -->
                                                                    <div class="mt-4">
                                                                        <div class="row fw-bold border-bottom pb-2">
                                                                            <div class="col-2" style="color: #A16D28;">Qty</div>
                                                                            <div class="col-4" style="color: #A16D28;">Product</div>
                                                                            <div class="col-2" style="color: #A16D28;">Unit</div>
                                                                            <div class="col-2 text-end" style="color: #A16D28;">Price</div>
                                                                            <div class="col-2 text-end" style="color: #A16D28;">Amount</div>
                                                                        </div>

                                                                        @foreach($group as $item)
                                                                            <div class="row py-2 border-bottom">
                                                                                <div class="col-2" style="color: black;">{{ $item->quantity }}</div>
                                                                                <div class="col-4" style="color: black;">{{ $item->product }}</div>
                                                                                <div class="col-2" style="color: black;">{{ $item->unit }}</div>
                                                                                <div class="col-2 text-end" style="color: black;">₱{{ number_format($item->price, 2) }}</div>
                                                                                <div class="col-2 text-end" style="color: black;">₱{{ number_format($item->amount, 2) }}</div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>

                                                                    <!-- Total and Footer -->
                                                                    <div class="row mt-4">
                                                                        <div class="col-md-6"></div>
                                                                        <div class="col-md-6 text-end">
                                                                            <p style="color: black;" class="mb-2"><strong>Total amount:</strong> ₱{{ number_format($totalAmount, 2) }}</p>
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
                                                    <td colspan="4">No return history found.</td>
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
       function calculateTotals() {
    let totalAmount = 0;

    const rows = document.querySelectorAll('tbody tr');

    rows.forEach(row => {
        const quantityInput = row.querySelector('input[name="quantity[]"]');
        const priceCell = row.querySelector('td:nth-child(4)');
        const amountDisplay = row.querySelector('.amount-display');
        const amountInput = row.querySelector('input[name="amount[]"]');

        if (!quantityInput || !priceCell || !amountDisplay) return; // skip row if missing

        const priceText = priceCell.innerText.replace(/[^\d.]/g, '');
        const quantity = parseInt(quantityInput.value) || 0;
        const price = parseFloat(priceText) || 0;

        const amount = quantity * price;

        amountDisplay.textContent = '₱ ' + amount.toFixed(2);

        if (amountInput) {
            amountInput.value = amount.toFixed(2);
        }

        totalAmount += amount;
    });

    // Update total display
    const totalDisplay = document.getElementById('total_amount_display');
    if (totalDisplay) {
        totalDisplay.textContent = '₱ ' + totalAmount.toFixed(2);
    }

    // Update hidden input (if any)
    const totalInput = document.getElementById('total_amount_input');
    if (totalInput) {
        totalInput.value = totalAmount.toFixed(2);
    }
}

// Recalculate totals whenever quantity changes
document.addEventListener('input', function(event) {
    if (event.target.matches('input[name="quantity[]"]')) {
        calculateTotals();
    }
});

// Run once on page load
window.addEventListener('load', calculateTotals);

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
    <script>
        $(function() {
            $('#product_select').select2({
                placeholder: "Search and select products",
                allowClear: true,
                width: 'resolve'
            });
        });
    </script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</body>

</html>