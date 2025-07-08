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
                            <li class="breadcrumb-item active">Stock In Management</li>
                        </ol>
                    </div>
                </div>
                <!-- row -->


                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title" style="font-size: 20px; color: blueviolet;">
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

                                    <form action="">
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

                                <form action="">
                                    <div class="row mb-3">
                                        <div class="col-md-3 text-left">
                                            <label style="color: #593bdb;" for="received_date"
                                                class="form-label">Received
                                                date</label>
                                            <input type="date" id="received_date" name="received_date"
                                                class="form-control">
                                        </div>
                                        <div class="col-md-3 text-left">
                                            <label style="color: #593bdb;" for="process_date" class="form-label">Process
                                                date</label>
                                            <input type="date" id="process_date" name="process_date"
                                                class="form-control" readonly>
                                        </div>

                                        <div class="col-md-3 text-left">
                                            <label style="color: #593bdb;" for="process_by" class="form-label">Process
                                                by</label>
                                            <input type="text" id="process_by" name="process_by" class="form-control"
                                                value="Juan" readonly>
                                        </div>
                                        <div class="col-md-3 text-left">
                                            <label style="color: #593bdb;" for="supplier" class="form-label">Supplier</label>
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
                                                    <th style="color: #593bdb;">Quantity</th>
                                                    <th style="color: #593bdb;">Product</th>
                                                    <th style="color: #593bdb;">Unit</th>
                                                    <th style="color: #593bdb;">Price</th>
                                                    <th style="color: #593bdb;">Amount</th>
                                                    <th style="color: #593bdb;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th>
                                                        <input style="border-color: #593bdb;" type="text"
                                                            class="form-control input-rounded" placeholder="0">
                                                    </th>
                                                    <td style="color: black;">Sample Product</td>
                                                    <td><span class="badge badge-primary">80kg</span>
                                                    </td>
                                                    <td style="color: black;">100</td>
                                                    <td style="color: black;">10000</td>
                                                    <td>
                                                        <span>
                                                            <a class="btn btn-outline-danger" href=""
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="Close"><i class="fa fa-close"></i>
                                                                Remove
                                                            </a>
                                                        </span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="4" class="text-end fw-bold" style="color: blueviolet;">
                                                    </td>
                                                    <td colspan="2" id="total_amount" class="fw-bold"
                                                        style="color: black;">
                                                        <span style="color: red;">Total Amount: 0</span>
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
                                            <th style="width: 15%; color: #593bdb;">Received Date</th>
                                            <th style="width: 30%; color: #593bdb;">Processed By</th>
                                            <th style="width: 30%; color: #593bdb;">Actions</th>
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
                                                <input type="date" class="form-control form-control-sm"
                                                    value="2025-07-06" readonly>
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
    <!-- JQUERY VALIDATION -->
    <script src="{{ asset('partials/vendor/jquery-validation/jquery.validate.min.js') }}"></script>
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