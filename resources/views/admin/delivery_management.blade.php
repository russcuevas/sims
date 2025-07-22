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
                            <li class="breadcrumb-item"><a style="color: #D96F32;" href="{{ route('admin.dashboard.page')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Delivery Management</li>
                        </ol>
                    </div>
                </div>
                <!-- row -->


                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title" style="font-size: 20px; color: #D96F32;">
                                    Delivery Management
                                </h4>
                            </div>

                            <div class="container my-4">
                                <!-- Status Buttons aligned to the right -->
                                <div class="row mb-3 justify-content-center">
                                    <div class="col-auto px-1">
                                        <a href="{{ route('admin.delivery.management.page') }}" class="btn btn-primary" id="status_preparing">Preparing</a>
                                    </div>
                                    <div class="col-auto px-1">
                                        <a href="{{ route('admin.return.item.page') }}" class="btn btn-outline-primary" id="status_to_ship">Return item</a>
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
                                    <!-- Add Store Buttons aligned to the right -->
                                    <div class="row mb-3 justify-content-end">
                                        <div class="col-md-2 text-end">
                                        <a href="{{ route('admin.view.available.cars') }}" class="btn btn-outline-primary w-100">
                                            View available cars
                                        </a>
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <button type="button" id="add_store_button"
                                                class="btn btn-outline-primary w-100"
                                                data-toggle="modal" data-target="#add_car">
                                                Add car details
                                            </button>
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <button type="button" id="add_store_button_2"
                                                class="btn btn-outline-primary w-100"
                                                data-toggle="modal" data-target="#add_store_modal">
                                                Add store
                                            </button>
                                        </div>
                                    </div>

                                    {{-- ADD CAR --}}
                                    <div class="modal fade" id="add_car">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Add Car</h5>
                                                    <button type="button" class="close"
                                                        data-dismiss="modal"><span>&times;</span></button>
                                                </div>
                                                <form action="{{ route('admin.delivery.add.car') }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">

                                                        <div class="form-group row mb-3">
                                                            <label for="car"
                                                                class="col-sm-4 col-form-label text-end">
                                                                CAR <span class="text-danger">*</span>
                                                            </label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="car"
                                                                    name="car" placeholder="Enter car"
                                                                    required>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row mb-3">
                                                            <label for="plate_number"
                                                                class="col-sm-4 col-form-label text-end">
                                                                Plate Number <span class="text-danger">*</span>
                                                            </label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="plate_number"
                                                                    name="plate_number" placeholder="Enter plate number"
                                                                    required>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- ADD STORE MODAL --}}
                                    <div class="modal fade" id="add_store_modal">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Add Store</h5>
                                                    <button type="button" class="close"
                                                        data-dismiss="modal"><span>&times;</span></button>
                                                </div>
                                                <form action="{{ route('admin.delivery.add.store') }}" method="POST">
                                                        @csrf
                                                    <div class="modal-body">

                                                        <!-- Store Name -->
                                                        <div class="form-group row mb-3">
                                                            <label for="store_name"
                                                                class="col-sm-4 col-form-label text-end">
                                                                Store name <span class="text-danger">*</span>
                                                            </label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="store_name"
                                                                    name="store_name" placeholder="Enter store name"
                                                                    required>
                                                            </div>
                                                        </div>


                                                        <!-- Store Code -->
                                                        <div class="form-group row mb-3">
                                                            <label for="store_code"
                                                                class="col-sm-4 col-form-label text-end">
                                                                Store code <span class="text-danger">*</span>
                                                            </label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="store_code"
                                                                    name="store_code" placeholder="Enter store code"
                                                                    required>
                                                            </div>
                                                        </div>

                                                        
                                                        <!-- Store Address -->
                                                        <div class="form-group row mb-3">
                                                            <label for="store_address"
                                                                class="col-sm-4 col-form-label text-end">
                                                                Store address <span class="text-danger">*</span>
                                                            </label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="store_address"
                                                                    name="store_address" placeholder="Enter store address"
                                                                    required>
                                                            </div>
                                                        </div>

                                                    
                                                        
                                                        <!-- Store Tel No -->
                                                        <div class="form-group row mb-3">
                                                            <label for="store_tel_no"
                                                                class="col-sm-4 col-form-label text-end">
                                                                Tel no. <span class="text-danger">*</span>
                                                            </label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="store_tel_no"
                                                                    name="store_tel_no" placeholder="Enter tel no."
                                                                    required>
                                                            </div>
                                                        </div>

                                                        
                                                        <!-- Store CP No -->
                                                        <div class="form-group row mb-3">
                                                            <label for="store_cp_number"
                                                                class="col-sm-4 col-form-label text-end">
                                                                Store Cellphone No. <span class="text-danger">*</span>
                                                            </label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="store_cp_number"
                                                                    name="store_cp_number" placeholder="Enter cellphone no."
                                                                    required>
                                                            </div>
                                                        </div>

                                                        
                                                        <!-- Store FAX -->
                                                        <div class="form-group row mb-3">
                                                            <label for="store_fax"
                                                                class="col-sm-4 col-form-label text-end">
                                                                FAX <span class="text-danger">*</span>
                                                            </label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="store_fax"
                                                                    name="store_fax" placeholder="Enter fax"
                                                                    required>
                                                            </div>
                                                        </div>

                                                        <!-- Store TIN -->
                                                        <div class="form-group row mb-3">
                                                            <label for="store_tin"
                                                                class="col-sm-4 col-form-label text-end">
                                                                TIN <span class="text-danger">*</span>
                                                            </label>
                                                            <div class="col-sm-8">
                                                                <input type="text" class="form-control" id="store_tin"
                                                                    name="store_tin" placeholder="Enter tin"
                                                                    required>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <form action="{{ route('admin.delivery.submit.batch') }}" method="POST">
                                        @csrf
                                        <div class="d-flex gap-2" style="width: 100%;">
                                            <select id="product_select" name="products[]" class="form-control" multiple>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}">
                                                        {{ $product->product_name }} | Qty: {{ $product->quantity }} | {{ $product->stock_unit_id }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>


                                <form action="{{ route('admin.delivery.add') }}" method="POST">
                                    @csrf                                    
                                    <div class="row mb-3 align-items-end">
                                        <div class="col-md-3 text-left">
                                            <label style="color: #D96F32;" for="memo" class="form-label">Memo</label>
                                            <input type="text" id="memo" name="memo"
                                                class="form-control">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="process_date" class="form-label"
                                                style="color: #D96F32;">Transaction Date</label>
                                            <input type="date" id="transaction_date" name="transaction_date"
                                                class="form-control">
                                        </div>

                                        <div class="col-md-3">
                                            <label for="process_date" class="form-label"
                                                style="color: #D96F32;">Expected Delivery</label>
                                            <input type="date" id="expected_date" name="expected_date"
                                                class="form-control">
                                        </div>

                                        <div class="col-md-3 text-left">
                                            <label style="color: #D96F32;" for="process_by" class="form-label">Process
                                                by</label>
                                            <input type="text" id="process_by" name="process_by" class="form-control"
                                            value="{{ $user->employee_firstname }} {{ $user->employee_lastname }}" readonly>
                                        </div>

                                        <div class="col-md-3 text-left" style="margin-top: 20px">
                                            <label style="color: #D96F32;" for="approved_by" class="form-label">Approved by</label>
                                            <select id="approved_by" name="approved_by" class="form-control">
                                                @foreach ($allEmployees as $employee)
                                                    @if ($employee->position_id == 1)  <!-- Approved by: Position 1 -->
                                                        <option value="{{ $employee->id }}">
                                                            {{ $employee->employee_firstname }} {{ $employee->employee_lastname }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3 text-left" style="margin-top: 20px">
                                            <label style="color: #D96F32;" for="delivered_by" class="form-label">Delivered by</label>
                                            <select id="delivered_by" name="delivered_by" class="form-control">
                                                @foreach ($allEmployees as $employee)
                                                    @if ($employee->position_id == 3)  <!-- Delivered by: Position 3 -->
                                                        <option value="{{ $employee->id }}">
                                                            {{ $employee->employee_firstname }} {{ $employee->employee_lastname }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>


                                        <div class="col-md-3 text-left">
                                            <label style="color: #D96F32;" for="car" class="form-label">Select Car</label>
                                            <select id="car" name="car" class="form-control">
                                                @foreach ($cars as $car)
                                                    <option value="{{ $car->id }}">
                                                        {{ $car->car }} ({{ $car->plate_number }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3 text-left">
                                            <label style="color: #D96F32;" for="store" class="form-label">Select Store</label>
                                            <select id="store" name="store" class="form-control">
                                                @foreach ($stores as $store)
                                                    <option value="{{ $store->id }}">
                                                        {{ $store->store_name }} - {{ $store->store_code }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>


                                <!-- Modal -->
                                <div class="modal fade" id="select_products_modal" tabindex="-1" role="dialog" aria-labelledby="selectProductsModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Select Product</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <table id="productTable" class="table table-bordered table-hover table-striped">
                                                    <thead class="thead-primary">
                                                        <tr>
                                                            <th>Product Name</th>
                                                            <th class="text-center">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                



                                <div class="table-responsive">
                                        @csrf
                                        <input type="hidden" name="process_date" value="{{ now()->toDateString() }}">
                                    
                                        <table class="table table-bordered table-responsive-sm">
                                        <thead>
                                            <tr>
                                                <th style="color: #D96F32;">Product</th>
                                                <th style="color: #D96F32;">Pack</th>
                                                <th style="color: #D96F32;">Unit</th>
                                                <th style="color: #D96F32;">Qty Ordered</th>
                                                <th style="color: #D96F32;">Qty Received</th>
                                                <th style="color: #D96F32;">Price</th>
                                                <th style="color: #D96F32;">Amount</th>
                                                <th style="color: #D96F32;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tbody>
                                            @forelse ($fetch_finished_products as $batch)
                                                <tr>
                                                    <td style="color: black">{{ $batch->product_name }}</td>
                                                    <td>
                                                        <input type="number" class="form-control quantity" value="1" style="width: 50px; background-color: gray; color: white; cursor: none;" readonly>
                                                    </td>
                                                    <td style="color: black">{{ $batch->unit }}</td>
                                                    <td>
                                                        <input type="hidden" name="product_id[]" value="{{ $batch->id }}">
                                                        <input type="number" name="quantity_ordered[]" class="form-control quantity" style="width: 70px" value="1" required data-price="{{ $batch->price }}" data-row-id="row-{{ $batch->id }}">
                                                    </td>
                                                    <td></td>
                                                    <td style="color: black">₱{{ $batch->price }}
                                                        <input type="hidden" name="price[]" value="{{ $batch->price }}">
                                                    </td>
                                                    <td style="color: black">
                                                        <span class="amount">₱{{ $batch->price }}</span>
                                                        <input type="hidden" name="amount[]" class="amount-hidden" value="{{ $batch->price }}">
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.delivery.remove.product', $batch->id) }}" 
                                                        class="btn btn-outline-danger" 
                                                        title="Remove" 
                                                        onclick="return confirm('Are you sure you want to remove this product from the batch?');">
                                                            <i class="fa fa-close"></i> Remove
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center text-muted">
                                                        No products
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" class="text-end fw-bold" style="color: #D96F32;">
                                                    Total Ordered:
                                                </td>
                                                <td colspan="5" id="total_amount" class="fw-bold" style="color: black;">
                                                    <span style="color: red;">Total Amount: ₱</span>
                                                    <input type="hidden" name="total_amount">
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                        <button type="submit" class="btn btn-primary float-right">Submit & Print</button>
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
                                            @foreach ($processors as $processor)
                                                <option value="{{ $processor }}" {{ (request('process_by') == $processor) ? 'selected' : '' }}>
                                                    {{ $processor }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <select name="sort" class="btn btn-outline-primary dropdown-toggle" onchange="document.getElementById('filterSortForm').submit()">
                                            <option value="">Sort by Date</option>
                                            <option value="newest" {{ (request('sort') == 'newest') ? 'selected' : '' }}>Newest First</option>
                                            <option value="oldest" {{ (request('sort') == 'oldest') ? 'selected' : '' }}>Oldest First</option>
                                        </select>

                                    </form>

                                    
                                    
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered text-center align-middle">
                                        <thead class="table-light fw-bold">
                                            <tr>
                                                <th style="width: 10%; color: #D96F32;">Details</th>
                                                <th style="width: 15%; color: #D96F32;">Transaction Date</th>
                                                <th style="width: 20%; color: #D96F32;">Processed By</th>
                                                <th style="width: 20%; color: #D96F32;">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($deliveryOrders as $transactId => $orders)
                                                @php $first = $orders->first(); @endphp
                                                <tr>
                                                    <td>
                                                        <a target="_blank" href="{{ route('admin.delivery.view', $transactId) }}" class="btn btn-outline-primary btn-sm">View</a>
                                                    </td>
                                                    <td style="color: black">{{ \Carbon\Carbon::parse($first->transaction_date)->format('m/d/Y') ?? 'N/A' }}</td>
                                                    <td style="color: black">{{ $first->process_by }}</td>
                                                    <td>
                                                        <form method="POST" action="{{ route('admin.delivery.archive', $transactId) }}">
                                                            @csrf
                                                            <button type="submit" class="btn btn-outline-danger btn-sm">Archive</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">
                                                        No delivery orders
                                                    </td>
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
        function updateAmount(input) {
            const quantity = parseFloat(input.value);
            const price = parseFloat(input.getAttribute('data-price'));
            const amount = quantity * price; 

            // Update the amount display for this row
            const amountDisplay = input.closest('tr').querySelector('.amount');
            const amountHidden = input.closest('tr').querySelector('.amount-hidden');
            
            // Update the display
            amountDisplay.textContent = '₱' + amount.toFixed(2); // Display amount with two decimals
            amountHidden.value = amount.toFixed(2); // Store amount in hidden input
            
            // Recalculate and update the total amount
            updateTotalAmount();
        }

        // Function to calculate the total amount from all rows
        function updateTotalAmount() {
            let totalAmount = 0;

            // Get all amount hidden inputs and sum their values
            const amountHiddenInputs = document.querySelectorAll('.amount-hidden');
            amountHiddenInputs.forEach(input => {
                totalAmount += parseFloat(input.value); // Add the hidden amount value
            });

            // Update the total amount display
            const totalAmountDisplay = document.getElementById('total_amount');
            totalAmountDisplay.querySelector('span').textContent = 'Total Amount: ₱' + totalAmount.toFixed(2); // Update the total amount
            totalAmountDisplay.querySelector('input[name="total_amount"]').value = totalAmount.toFixed(2); // Update the hidden total amount field
        }

        // Get all quantity input elements and add event listeners
        const quantityInputs = document.querySelectorAll('.quantity');
        quantityInputs.forEach(input => {
            input.addEventListener('input', function() {
                updateAmount(input); // Call the updateAmount function when quantity changes
            });

            // Call the updateAmount function on page load to initialize the amounts
            updateAmount(input);
        });
    </script>



    <!-- ADD USERS VALIDATION -->
    <script>
        $(document).ready(function () {
            $(".add_store_validation").validate({
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
            $(".add_car_validation").validate({
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


<script>
    $(document).ready(function () {
        $('#productTable').DataTable({
            paging: true,
            searching: true,
            ordering: false,
            info: false,
            lengthChange: false,
            pageLength: 6,
            language: {
                searchPlaceholder: "Search product...",
                search: ""
            }
        });
    });
</script>

</body>

</html>