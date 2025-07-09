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
                            <li class="breadcrumb-item"><a style="color: blueviolet;" href="">Dashboard</a></li>
                            <li class="breadcrumb-item active">Process Management</li>
                        </ol>
                    </div>
                </div>
                <!-- row -->


                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title" style="font-size: 20px; color: blueviolet;">
                                    Process Management
                                </h4>
                            </div>

                            <div class="card-body">
                                <div class="container my-4">
                                    <form action="{{ route('admin.batch.add.raw.products') }}" method="POST">
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

                                <div class="table-responsive">
                                            <table class="table table-bordered table-responsive-sm">
                                                <thead>
                                                    <tr>
                                                        <th style="color: #593bdb;">Current Quantity</th>
                                                        <th style="color: #593bdb;">Quantity</th>
                                                        <th style="color: #593bdb;">Product</th>
                                                        <th style="color: #593bdb;">Unit</th>
                                                        <th style="color: #593bdb;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($batchProducts as $product)
                                                        <tr>
                                                            <td style="color: black;">{{ $product->quantity }}</td>
                                        
                                                            <td>
                                                                <input style="border-color: #593bdb;" type="number" class="form-control input-rounded"
                                                                    name="quantities[{{ $product->id }}]" value="1" min="1">
                                                            </td>
                                                            
                                        
                                                            <td style="color: black;">{{ $product->product_name }}</td>
                                        
                                                            <td>
                                                                <span class="badge badge-primary">{{ $product->stock_unit_id ?? 'N/A' }}</span>
                                                            </td>
                                        
                                                            <td>
                                                                
                                                                <a href="{{ route('admin.batch.raw.product.remove', $product->id) }}" 
                                                                    class="btn btn-outline-danger" 
                                                                    title="Remove" 
                                                                    onclick="return confirm('Are you sure you want to remove this product from the batch?');">
                                                                     <i class="fa fa-close"></i> Remove
                                                                 </a>                                        
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center text-muted">No batch products added yet.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                </div>

                                <br>
                                <br>
                                <br>
                                <form action="#">
                                    <div class="row mb-3 align-items-end">

                                        <div class="col-md-3">
                                            <label for="process_date" class="form-label"
                                                style="color: #593bdb;">Process Date</label>
                                            <input type="date" id="process_date" name="process_date"
                                                class="form-control">
                                        </div>

                                        <div class="col-md-3 text-left">
                                            <label style="color: #593bdb;" for="process_by" class="form-label">Process
                                                by</label>
                                            <input type="text" id="process_by" name="process_by" class="form-control"
                                            value="{{ $user->employee_firstname }} {{ $user->employee_lastname }}" readonly>
                                        </div>

                                        <div class="col-md-3 text-end">
                                            <button type="button" id="add_product_button"
                                                class="btn btn-outline-primary w-100" data-toggle="modal"
                                                data-target="#add_product_modal">
                                                + Add Products
                                            </button>
                                        </div>
                                    </form>
                                        <div class="col-md-3 text-end">
                                            <button type="button" id="select_products_button"
                                                class="btn btn-outline-primary w-100"
                                                data-toggle="modal"
                                                data-target="#select_products_modal"
                                                title="{{ $hasFinishProducts ? 'You already have finished products submitted.' : '' }}"
                                                @if($hasFinishProducts) disabled @endif>
                                                Select Products
                                            </button>
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
                                                        @foreach($multipleUnitProducts as $product)
                                                            <tr>
                                                                <td style="color: black">{{ $product->product_name }}</td>
                                                                <td class="text-center">
                                                                    <button class="btn btn-sm btn-outline-primary finish-product-btn"
                                                                            data-product="{{ $product->product_name }}">
                                                                        <i class="fa fa-check"></i> Select
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                


                                <!-- ADD PRODUCTS MODAL -->
                                <div class="modal fade" id="add_product_modal">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Add Product With Multiple Units</h5>
                                                <button type="button" class="close"
                                                    data-dismiss="modal"><span>&times;</span></button>
                                            </div>
                                            <form class="add_product_validation" action="{{ route('admin.add.batch.multiple.product') }}" method="POST">
                                                @csrf
                                                <div class="modal-body">

                                                    <!-- Product Name -->
                                                    <div class="form-group row mb-3">
                                                        <label for="product_name"
                                                            class="col-sm-4 col-form-label text-end">
                                                            Product Name <span class="text-danger">*</span>
                                                        </label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control" id="product_name"
                                                                name="product_name" placeholder="Enter product name"
                                                                required>
                                                        </div>
                                                    </div>

                                                    <!-- Product Units + Prices -->
                                                    <div class="form-group row mb-3">
                                                        <label class="col-sm-4 col-form-label text-end">
                                                            Product Units & Prices <span class="text-danger">*</span>
                                                        </label>
                                                        <div class="col-sm-8">
                                                            <div class="input-group mb-2">
                                                                <span class="input-group-text">80g</span>
                                                                <input type="text" class="form-control" name="price_80g"
                                                                    placeholder="Enter price for 80g" required>
                                                            </div>
                                                            <div class="input-group mb-2">
                                                                <span class="input-group-text">130g</span>
                                                                <input type="text" class="form-control"
                                                                    name="price_130g" placeholder="Enter price for 130g"
                                                                    required>
                                                            </div>
                                                            <div class="input-group">
                                                                <span class="input-group-text">230g</span>
                                                                <input type="text" class="form-control"
                                                                    name="price_230g" placeholder="Enter price for 230g"
                                                                    required>
                                                            </div>
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


                                <div class="table-responsive">
                                    <table class="table table-bordered table-responsive-sm">
                                        <thead>
                                            <tr>
                                                <th style="color: #593bdb;">Quantity</th>
                                                <th style="color: #593bdb;">Product</th>
                                                <th style="color: #593bdb;">Unit</th>
                                                <th style="color: #593bdb;">Price</th>
                                                <th style="color: #593bdb;">Amount</th>
                                                <th style="color: #593bdb;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $totalAmount = 0; @endphp
                                            @foreach($finishProducts as $product)
                                                @php
                                                    $amount = $product->quantity * $product->product_price;
                                                    $totalAmount += $amount;
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <input style="border-color: #593bdb;" type="text"
                                                               class="form-control input-rounded"
                                                               value="{{ $product->quantity }}" readonly>
                                                    </td>
                                                    <td style="color: black;">{{ $product->product_name }}</td>
                                                    <td><span class="badge badge-primary">{{ $product->stock_unit_id }}</span></td>
                                                    <td>
                                                        <input style="border-color: #593bdb;" type="text"
                                                               class="form-control input-rounded"
                                                               value="{{ number_format($product->product_price, 2) }}" readonly>
                                                    </td>
                                                    <td style="color: black;">{{ number_format($amount, 2) }}</td>
                                                    <td>
                                                        <a class="btn btn-outline-danger" href=""
                                                           onclick="return confirm('Are you sure you want to remove this product?')">
                                                            <i class="fa fa-close"></i> Remove
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" class="text-end fw-bold" style="color: blueviolet;"></td>
                                                <td colspan="2" id="total_amount" class="fw-bold" style="color: black;">
                                                    <span style="color: red;">Total Amount: {{ number_format($totalAmount, 2) }}</span>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>

                                    <!-- SUBMIT STOCKS -->
                                    <button type="submit" class="btn btn-primary me-2 float-right">
                                        Submit Product
                                    </button>
                                    </form>
                                </div>
                                <hr class="my-4">

                                <h5 class="text-center text-primary">History</h5>

                                <!-- Search bar and actions -->
                                <div class="d-flex flex-wrap justify-content-center gap-2 mb-3">
                                    <input type="text" class="form-control w-auto" placeholder="Search product here">
                                    <button class="btn btn-primary mr-2">Search</button>
                                    <button class="btn btn-primary mr-2">Filter</button>
                                    <button class="btn btn-primary mr-2">Sort</button>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered text-center align-middle">
                                        <thead class="table-light fw-bold">
                                            <tr>
                                                <th style="width: 10%; color: #593bdb;">Details</th>
                                                <th style="width: 15%; color: #593bdb;">Process Date</th>
                                                <th style="width: 20%; color: #593bdb;">Processed By</th>
                                                <th style="width: 20%; color: #593bdb;">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <button class="btn btn-outline-primary btn-sm">View</button>
                                                </td>
                                                <td>
                                                    <input type="date" class="form-control form-control-sm"
                                                        value="2025-07-07" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm"
                                                        value="Juan dela Cruz" readonly>
                                                </td>
                                                <td>
                                                    <button class="btn btn-outline-primary btn-sm">Archive</button>
                                                </td>
                                            </tr>
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
                        in: 0.
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
    $(document).on('click', '.finish-product-btn', function () {
        const productName = $(this).data('product');

        Swal.fire({
            title: 'Are you sure?',
            text: `Submit "${productName}" and all its units to batch finish products?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, submit it',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('admin.batch.finish.product') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        batch_product_name: productName
                    },
                    success: function (response) {
                        Swal.fire('Success!', 'Product submitted successfully.', 'success').then(() => {
                            location.reload();
                        });
                    },
                    error: function (xhr) {
                        Swal.fire('Error', 'An error occurred while submitting.', 'error');
                    }
                });
            }
        });
    });
</script>

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