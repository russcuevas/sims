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
                            <li class="breadcrumb-item active">Stock In Management</li>
                        </ol>
                    </div>
                </div>
                <!-- row -->


                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title" style="font-size: 20px; color: #A16D28;">
                                    Stock in Management
                                </h4>
                            </div>

                            <div class="card-body">
                                <div class="container my-4">
                                    <div class="d-flex justify-content-end mb-2 gap-2">

                                        <!-- ADD PRODUCT -->
                                        <button id="add_product_button" class="btn btn-outline-primary me-2 mr-2"
                                            data-toggle="modal" data-target="#add_product_modal">+ Add
                                            Products</button>

                                        <!-- ADD PRODUCT MODAL -->
                                        <div class="modal fade" id="add_product_modal">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Add Product</h5>
                                                        <button type="button" class="close"
                                                            data-dismiss="modal"><span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="modal-body">
                                                            <form class="add_product_validation" action="{{ route('admin.stock.in.add.product') }}" method="POST">
                                                                @csrf
                                                                <div class="form-group row mb-3">
                                                                    <label for="product_name" class="col-sm-4 col-form-label text-end">
                                                                        Product Name <span class="text-danger">*</span>
                                                                    </label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Enter product name" required>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row mb-3">
                                                                    <label for="product_unit" class="col-sm-4 col-form-label text-end">
                                                                        Product Unit <span class="text-danger">*</span>
                                                                    </label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control" id="product_unit" name="product_unit" placeholder="Enter product unit" required>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row mb-3">
                                                                    <label for="product_price" class="col-sm-4 col-form-label text-end">
                                                                        Product Price <span class="text-danger">*</span>
                                                                    </label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control" id="product_price" name="product_price" placeholder="Enter product price" required>
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

                                        <!-- ADD SUPPLIER -->
                                        <button id="add_supplier_button" class="btn btn-outline-primary me-2"
                                            data-toggle="modal" data-target="#add_supplier_modal">+ Add
                                            Suppliers</button>

                                        <!-- ADD SUPPLIERS MODAL -->
                                        <div class="modal fade" id="add_supplier_modal">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Add Suppliers</h5>
                                                        <button type="button" class="close"
                                                            data-dismiss="modal"><span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="modal-body">
                                                            <form class="add_suppliers_validation" action="{{ route('admin.stock.in.add.supplier') }}" method="POST">
                                                                @csrf
                                                                <div class="form-group row mb-3">
                                                                    <label for="supplier_name"
                                                                        class="col-sm-4 col-form-label text-end">
                                                                        Name <span class="text-danger">*</span>
                                                                    </label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control"
                                                                            id="supplier_name" name="supplier_name"
                                                                            placeholder="Enter supplier name" required>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row mb-3">
                                                                    <label for="supplier_contact_num"
                                                                        class="col-sm-4 col-form-label text-end">
                                                                        Phone Number <span class="text-danger">*</span>
                                                                    </label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control"
                                                                            id="supplier_contact_num"
                                                                            name="supplier_contact_num"
                                                                            placeholder="Enter phone number" required>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row mb-3">
                                                                    <label for="supplier_email_add"
                                                                        class="col-sm-4 col-form-label text-end">
                                                                        Email Address <span class="text-danger">*</span>
                                                                    </label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control"
                                                                            id="supplier_email_add"
                                                                            name="supplier_email_add"
                                                                            placeholder="Enter email address" required>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row mb-3">
                                                                    <label for="supplier_address"
                                                                        class="col-sm-4 col-form-label text-end">
                                                                        Address <span class="text-danger">*</span>
                                                                    </label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control"
                                                                            id="supplier_address"
                                                                            name="supplier_address"
                                                                            placeholder="Enter address" required>
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

                                    <form action="{{ route('admin.stock.in.add.batch.product.details') }}" method="POST">
                                        @csrf
                                        <div class="d-flex gap-2 align-items-center">
                                            <select id="product_select" name="product_ids[]" class="form-control mr-2" multiple="multiple" style="flex: 1; color: black !important">
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}">
                                                        {{ $product->product_name }} ({{ $product->stock_unit_id }}) - ₱{{ $product->product_price }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
                                    
                                    
                                </div>

                                <form action="{{ route('admin.raw.stocks.request') }}" method="POST">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-md-3 text-left">
                                            <label style="color: #A16D28;" for="received_date"
                                                class="form-label">Received
                                                date</label>
                                            <input type="date" id="received_date" name="received_date"
                                                class="form-control">
                                        </div>
                                        <div class="col-md-3 text-left">
                                            <label style="color: #A16D28;" for="process_date" class="form-label">Process
                                                date</label>
                                            <input type="date" id="process_date" name="process_date"
                                                class="form-control" readonly>
                                        </div>

                                        <div class="col-md-3 text-left">
                                            <label style="color: #A16D28;" for="process_by" class="form-label">Process
                                                by</label>
                                                <input type="text" id="process_by" name="process_by" class="form-control"
                                                value="{{ $user->employee_firstname }} {{ $user->employee_lastname }}" readonly>
                                            
                                        </div>
                                        <div class="col-md-3 text-left">
                                            <label style="color: #A16D28;" for="supplier" class="form-label">Supplier</label>
                                            <select id="supplier" name="supplier" class="form-control">
                                                <option value="">Select supplier</option>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="table-responsive">

                                        <table class="table table-bordered table-responsive-sm">
                                            <thead>
                                                <tr>
                                                    <th style="color: #A16D28;">Quantity</th>
                                                    <th style="color: #A16D28;">Product</th>
                                                    <th style="color: #A16D28;">Unit</th>
                                                    <th style="color: #A16D28;">Price</th>
                                                    <th style="color: #A16D28;">Amount</th>
                                                    <th style="color: #A16D28;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($batchProductDetails as $detail)
                                                    <tr>
                                                        <td>
                                                            <input type="number"
                                                                class="form-control input-rounded update-quantity"
                                                                value="{{ $detail->quantity }}"
                                                                data-id="{{ $detail->id }}"               
                                                                data-price="{{ $detail->price }}"
                                                                min="0"
                                                                style="border-color: #A16D28;">
                                                        </td>
                                                    
                                                        <td style="color: black;">{{ $detail->product_name }}</td>
                                                        <td><span class="badge badge-primary">{{ $detail->stock_unit_id }}</span></td>
                                                    
                                                        <td>
                                                            <input type="number" 
                                                                class="form-control input-rounded update-price" 
                                                                value="{{ number_format($detail->price, 2, '.', '') }}" 
                                                                data-id="{{ $detail->id }}"                 
                                                                data-product-id="{{ $detail->product_id }}" 
                                                                min="0" step="0.01" 
                                                                style="border-color: #A16D28;">
                                                        </td>
                                                    
                                                        <td style="color: black;" class="amount-cell" id="amount-{{ $detail->id }}">
                                                            ₱{{ number_format($detail->amount, 2) }}
                                                        </td>
                                                    
                                                        <td>
                                                            <a href="{{ route('admin.batch.product.remove', $detail->id) }}"
                                                                onclick="return confirm('Are you sure you want to remove this item?');"
                                                                class="btn btn-outline-danger" title="Remove">
                                                                <i class="fa fa-close"></i> Remove
                                                            </a>                                                         
                                                        </td>                                                    
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center text-muted">
                                                            No products available.
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>

                                            <tfoot>
                                                <tr>
                                                    <td colspan="4" class="text-end fw-bold" style="color: #A16D28;">
                                                    </td>
                                                    <td colspan="2" id="total_amount" class="fw-bold" style="color: black;">
                                                        <span style="color: red;">Total Amount: <span id="total_amount_value">₱{{ number_format($totalAmount, 2) }}</span></span>
                                                    </td>                                                    
                                                </tr>
                                            </tfoot>
                                            
                                        </table>

                                        <!-- SUBMIT STOCKS -->
                                        <button type="submit" class="btn btn-primary me-2 float-right">Save
                                            stocks</button>
                                </form>
                            </div>
                            <hr class="my-4">

                            <h5 class="text-center text-primary">History</h5>

                            <div class="d-flex flex-wrap justify-content-center gap-2 mb-3">
                                <form method="GET" action="{{ route('admin.stock.in.page') }}" class="d-flex flex-wrap justify-content-center gap-2 mb-3" id="filterSortForm">

                                    <input type="text" name="search" value="{{ request('search') }}" class="form-control w-auto" placeholder="Search product here">
                                    <button type="submit" class="btn btn-primary mr-2">Search</button>
                                
                                    <select name="supplier" class="btn btn-outline-primary dropdown-toggle mr-2" onchange="document.getElementById('filterSortForm').submit()">
                                        <option value="">Filter by Supplier</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ request('supplier') == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->supplier_name }}
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
                                            <th style="width: 15%; color: #A16D28;">Process Date</th>
                                            <th style="width: 15%; color: #A16D28;">Received Date</th>
                                            <th style="width: 30%; color: #A16D28;">Processed By</th>
                                            <th style="width: 30%; color: #A16D28;">Actions</th>
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
                                                <td>
                                                    <input type="date" class="form-control form-control-sm" value="{{ \Carbon\Carbon::parse($first->created_at)->toDateString() }}" readonly>
                                                </td>
                                                <td>
                                                    <input type="date" class="form-control form-control-sm" value="{{ \Carbon\Carbon::parse($first->received_date)->toDateString() }}" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm" value="{{ $first->process_by }}" readonly>
                                                </td>
                                                <td>
                                                    <form action="{{ route('admin.archive.raw.stock', ['transactId' => $transactId]) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-danger btn-sm">Archive</button>
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

                                                                <!-- Products Table -->
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

                                                                <!-- Total and Received By -->
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
                                                <td colspan="5" class="text-center text-muted">No history records found.</td>
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
    <!-- SCRIPT -->
    <!-- REQUIRED VENDORS -->
    <script src="{{ asset('partials/vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('partials/js/quixnav-init.js') }}"></script>
    <script src="{{ asset('partials/js/custom.min.js') }}"></script>
    <!-- JQUERY VALIDATION -->
    <script src="{{ asset('partials/vendor/jquery-validation/jquery.validate.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

        function recalculateTotal() {
            let total = 0;
            document.querySelectorAll('.amount-cell').forEach(cell => {
                const amountText = cell.innerText.replace('₱', '').replace(',', '').trim();
                total += parseFloat(amountText) || 0;
            });

            const totalAmountEl = document.getElementById('total_amount_value');
            if (totalAmountEl) {
                totalAmountEl.innerText = `₱${total.toFixed(2)}`;
            }
        }

        // Quantity Update
        document.querySelectorAll('.update-quantity').forEach(input => {
            input.addEventListener('change', function () {
                const batchDetailId = this.dataset.id;        
                const newQuantity = parseFloat(this.value) || 0;
                const unitPrice = parseFloat(this.dataset.price) || 0;

                fetch(`/admin/batch-product-details/${batchDetailId}/quantity`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ quantity: newQuantity })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const amount = newQuantity === 0 ? unitPrice : unitPrice * newQuantity;
                        const amountCell = document.getElementById(`amount-${batchDetailId}`);
                        if (amountCell) {
                            amountCell.innerText = `₱${amount.toFixed(2)}`;
                        }
                        recalculateTotal();
                    } else {
                        alert('Failed to update quantity.');
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });

        // PRICE UPDATE
        document.querySelectorAll('.update-price').forEach(input => {
            input.addEventListener('change', function () {
                const batchDetailId = this.dataset.id;         
                const productId = this.dataset.productId;       
                const newPrice = parseFloat(this.value);

                if (isNaN(newPrice) || newPrice < 0) {
                    alert('Invalid price');
                    return;
                }

                fetch(`/admin/products/${productId}/update-price`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ price: newPrice })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.value = newPrice.toFixed(2);

                        const qtyInput = document.querySelector(`input.update-quantity[data-id="${batchDetailId}"]`);
                        if (qtyInput) {
                            qtyInput.dataset.price = newPrice;
                            const quantity = parseFloat(qtyInput.value) || 0;
                            const amountCell = document.getElementById(`amount-${batchDetailId}`);

                            // Show price if quantity is zero, else price * quantity
                            const amount = quantity === 0 ? newPrice : newPrice * quantity;

                            if (amountCell) {
                                amountCell.innerText = `₱${amount.toFixed(2)}`;
                            }
                        }

                        recalculateTotal();
                    } else {
                        alert('Failed to update price.');
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    });
    </script>    
    
    <!-- ADD USERS VALIDATION -->
    <script>
        $(document).ready(function () {
            $(".add_product_validation").validate({
                rules: {
                    product_name: {
                        required: true,
                        minlength: 3
                    },
                    product_unit: {
                        required: true,
                    },
                    product_price: {
                        required: true,
                        number: true,
                        min: 0.01
                    }
                },
                messages: {
                    product_name: {
                        required: "Please enter the product name",
                        minlength: "Product name must be at least 3 characters"
                    },
                    product_unit: {
                        required: "Please enter the unit",
                    },
                    product_price: {
                        required: "Please enter the product price",
                        number: "Please enter a valid number",
                        min: "Price must be greater than zero"
                    }
                },
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

            $("#product_price").on("input", function () {
                this.value = this.value.replace(/[^0-9.]/g, "");
            });
        });
    </script>


<script>
    $(document).ready(function () {
        $(".add_suppliers_validation").validate({
            rules: {
                supplier_name: {
                    required: true,
                    minlength: 3
                },
                supplier_contact_num: {
                    required: true,
                    digits: true,
                    minlength: 7,
                    maxlength: 15
                },
                supplier_email_add: {
                    required: true,
                    email: true
                },
                supplier_address: {
                    required: true,
                    minlength: 5
                }
            },
            messages: {
                supplier_name: {
                    required: "Please enter the supplier name",
                    minlength: "Supplier name must be at least 3 characters long"
                },
                supplier_contact_num: {
                    required: "Please enter the contact number",
                    digits: "Please enter only numbers",
                    minlength: "Contact number must be at least 7 digits",
                    maxlength: "Contact number cannot exceed 15 digits"
                },
                supplier_email_add: {
                    required: "Please enter an email address",
                    email: "Please enter a valid email address"
                },
                supplier_address: {
                    required: "Please enter the supplier address",
                    minlength: "Address must be at least 5 characters long"
                }
            },
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
        

</body>

</html>