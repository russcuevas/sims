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
                            <li class="breadcrumb-item active">Stock Management</li>
                        </ol>
                    </div>
                </div>
                <!-- row -->


                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title m-0" style="font-size: 20px; color: #A16D28;">
                                    Stock Management
                                </h4>

                                <!-- Group the buttons in a flex container -->
                                <div class="d-flex gap-2">
                                    <a href="#" class="btn btn-primary mr-2" data-toggle="modal" data-target="#historyPOModal">
                                        History P.O
                                    </a>
                                    <div class="modal fade" id="historyPOModal" tabindex="-1" aria-labelledby="historyPOModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="historyPOModalLabel">P.O History</h5>
                                                        <button type="button" class="close"
                                                            data-dismiss="modal"><span>&times;</span>
                                                        </button>                                                
                                                    </div>
                                                <div class="modal-body">
                                                    <table id="poTable" class="table table-bordered text-center">
                                                        <thead>
                                                            <tr>
                                                                <th style="color: black">Details</th>
                                                                <th style="color: black">P.O Number</th>
                                                                <th style="color: black">Process By</th>
                                                                <th style="color: black">Total Amount</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($purchaseOrders as $order)
                                                                <tr>
                                                                    <td style="color: black">
                                                                        <a target="_blank" href="{{ route('admin.view.po', $order->po_number) }}" class="btn btn-primary">
                                                                            View
                                                                        </a>
                                                                    </td>
                                                                    <td style="color: black">{{ $order->po_number }}</td>
                                                                    <td style="color: black">{{ $order->process_by }}</td>
                                                                    <td style="color: black">₱{{ number_format($order->total_amount, 2) }}</td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="4" class="text-muted" style="text-align: center;">
                                                                        No purchase orders found.
                                                                    </td>
                                                                </tr>
                                                            @endforelse

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.purchase.order.page') }}" target="_blank" class="btn btn-primary position-relative">
                                        View P.O
                                        @if($lowRawMaterialsCount > 0)
                                            <span class="position-relative top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                {{ $lowRawMaterialsCount }}
                                                <span class="visually-hidden">low stock alerts</span>
                                            </span>
                                        @endif
                                    </a>
                                </div>
                            </div>

                            <div class="card-body">


                                <div class="table-responsive">
                                    <div class="d-flex flex-wrap justify-content-center gap-2 mb-3">
                                        <form method="GET" action="{{ route('admin.stock.management.page') }}" class="d-flex gap-2 align-items-center mb-3">

                                            <input type="text" name="search" value="{{ request('search') }}" class="form-control w-auto" placeholder="Search product here">
                                            <button type="submit" class="btn btn-primary mr-2">Search</button>

                                            {{-- Filter Dropdown --}}
                                            <div class="dropdown">
                                                <button class="btn btn-outline-primary dropdown-toggle mr-2" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Filter by Category
                                                    @if(request('category'))
                                                        : {{ request('category') }}
                                                    @endif
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                                                    <li><a class="dropdown-item" href="{{ route('admin.stock.management.page', request()->except('category')) }}">All</a></li>
                                                    @foreach($categories as $category)
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('admin.stock.management.page', array_merge(request()->except('category'), ['category' => $category])) }}">
                                                                {{ ucfirst($category) }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        
                                            {{-- Sort Dropdown --}}
                                            <div class="dropdown">
                                                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Sort
                                                    @if(request('sort_by'))
                                                        : {{ ucfirst(str_replace('_', ' ', request('sort_by'))) }} {{ strtoupper(request('sort_dir', 'desc')) }}
                                                    @endif
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                                                    @php
                                                        $sortOptions = [
                                                            'product_name' => 'Name',
                                                            'price' => 'Price',
                                                            'quantity' => 'Quantity',
                                                            'created_at' => 'Date',
                                                        ];
                                                        $directions = ['asc' => 'Ascending', 'desc' => 'Descending'];
                                                    @endphp
                                        
                                                    @foreach($sortOptions as $key => $label)
                                                        @foreach($directions as $dirKey => $dirLabel)
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('admin.stock.management.page', array_merge(request()->except(['sort_by', 'sort_dir']), ['sort_by' => $key, 'sort_dir' => $dirKey])) }}">
                                                                    {{ $label }} - {{ $dirLabel }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    @endforeach
                                                </ul>
                                            </div>
                                    
                                        
                                        </form>
                                        
                                    </div>
                                    <table class="table table-bordered table-responsive-sm">
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
                                            @forelse ($productDetails as $product)
                                                <tr>
                                                    <td style="color: black;">{{ \Carbon\Carbon::parse($product->updated_at)->format('m/d/Y') }}</td>
                                                    <td>
                                                        <input style="border-color: #A16D28; background-color: gray; color: white;" type="number" 
                                                            class="form-control input-rounded"
                                                            value="{{ $product->quantity ?? '' }}" readonly>
                                                    </td>
                                                    <td style="color: black;">{{ $product->product_name }} - ₱{{ $product->price }}</td>
                                                    <td><span class="badge badge-primary">{{ $product->stock_unit_id }}</span></td>
                                                    <td style="color: black; text-transform: capitalize">{{ $product->category }}</td>
                                                    <td>
                                                        <button class="btn btn-outline-warning btn-sm" data-toggle="modal" data-target="#updateProductModal{{ $product->id }}">
                                                            <i class="fa fa-pencil"></i> Update
                                                        </button>

                                                        <form action="{{ route('admin.stock.archive.product', ['id' => $product->id]) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-outline-danger btn-sm">Archive</button>
                                                        </form>
                                                    </td>
                                                </tr>

                                                <!-- Update Modal -->
                                                <div class="modal fade" id="updateProductModal{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel{{ $product->id }}" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <form action="{{ route('admin.stock.update.product', ['id' => $product->id]) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Update Product - {{ $product->product_name }}</h5>
                                                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label style="color: black">Product Name</label>
                                                                        <input type="text" name="product_name" value="{{ $product->product_name }}" class="form-control" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label style="color: black">Quantity</label>
                                                                        <input type="number" name="quantity" value="{{ $product->quantity }}" min="0" class="form-control" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label style="color: black">Price</label>
                                                                        <input type="number" step="0.01" name="price" value="{{ $product->price }}" class="form-control" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label style="color: black">Stock Unit</label>
                                                                        <input type="text" name="stock_unit_id" value="{{ $product->stock_unit_id }}" class="form-control" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button class="btn btn-primary" type="submit">Save</button>
                                                                    <button class="btn btn-danger" type="button" data-dismiss="modal">Cancel</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">
                                                        No products available.
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
    <script>
        $(document).ready(function () {
            $('#poTable').DataTable({
                pageLength: 10,
                responsive: true,
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


</body>

</html>