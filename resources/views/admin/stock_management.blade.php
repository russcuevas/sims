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
                            <li class="breadcrumb-item active">Stock Management</li>
                        </ol>
                    </div>
                </div>
                <!-- row -->


                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title" style="font-size: 20px; color: blueviolet;">
                                    Stock Management
                                </h4>
                            </div>

                            <div class="card-body">


                                <div class="table-responsive">
                                    <!-- Search bar and actions -->
                                    <div class="d-flex flex-wrap justify-content-center gap-2 mb-3">
                                        <input type="text" class="form-control w-auto"
                                            placeholder="Search product here">
                                        <button class="btn btn-primary mr-2">Search</button>
                                        <button class="btn btn-primary mr-2">Filter</button>
                                        <button class="btn btn-primary mr-2">Sort</button>
                                    </div>
                                    <table class="table table-bordered table-responsive-sm">
                                        <thead>
                                            <tr>
                                                <th style="color: #593bdb;">Date</th>
                                                <th style="color: #593bdb;">Quantity</th>
                                                <th style="color: #593bdb;">Product</th>
                                                <th style="color: #593bdb;">Unit</th>
                                                <th style="color: #593bdb;">Category</th>
                                                <th style="color: #593bdb;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($productDetails as $product)
                                                <tr>
                                                    <td style="color: black;">{{ \Carbon\Carbon::parse($product->created_at)->format('m/d/Y') ?? 'N/A' }}</td>
                                                    <td>
                                                        <input style="border-color: #593bdb;" type="text" 
                                                            class="form-control input-rounded" placeholder="0" value="{{ $product->quantity ?? '' }}">
                                                    </td>
                                                    <td style="color: black;">{{ $product->product_name ?? 'N/A' }} - ₱{{ $product->price }}</td>                                    
                                                    <td><span class="badge badge-primary">{{ $product->stock_unit_id ?? 'unit' }}</span></td>                                    
                                                    <td style="color: black; text-transform: capitalize">{{ $product->category ?? 'N/A' }}</td>
                                                    <td>
                                                        <span>
                                                            <a class="btn btn-outline-warning" href="#" data-toggle="tooltip" data-placement="top" title="Update">
                                                                <i class="fa fa-pencil"></i> Update
                                                            </a>
                                                            <a class="btn btn-outline-danger" href="#" data-toggle="tooltip" data-placement="top" title="Archive">
                                                                <i class="fa fa-close"></i> Archive
                                                            </a>
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
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


</body>

</html>