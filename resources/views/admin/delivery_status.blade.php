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
                            <li class="breadcrumb-item"><a style="color: blueviolet;" href="{{ route('admin.dashboard.page')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Delivery Management</li>
                        </ol>
                    </div>
                </div>
                <!-- row -->


                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title" style="font-size: 20px; color: blueviolet;">
                                    Delivery Management
                                </h4>
                            </div>

                            

                            <div class="container my-4">
                                <!-- Search bar and actions -->
                                <div class="d-flex flex-wrap justify-content-center gap-2 mb-3">
                                    <form method="GET" action="" class="d-flex flex-wrap justify-content-center gap-2 mb-3" id="filterSortForm">
                                        <input type="text" name="search" value="" class="form-control w-auto" placeholder="Search product here">
                                        <button type="submit" class="btn btn-primary mr-2">Search</button>
                                        <select name="process_by" class="btn btn-outline-secondary dropdown-toggle mr-2" onchange="document.getElementById('filterSortForm').submit()">
                                            <option value="">Filter by Processor</option>
                                        </select>
                                        <select name="sort" class="btn btn-outline-secondary dropdown-toggle" onchange="document.getElementById('filterSortForm').submit()">
                                            <option value="">Sort by Date</option>
                                            <option value="newest">Newest First</option>
                                            <option value="oldest">Oldest First</option>
                                        </select>
                                    </form>
                                </div>

                                <!-- Status Buttons aligned to the right -->
                                <div class="row mb-3 justify-content-center">
                                    <div class="col-auto px-1">
                                        <a href="{{ route('admin.delivery.management.page') }}" class="btn btn-outline-secondary" id="status_preparing">Preparing</a>
                                    </div>
                                    <div class="col-auto px-1">
                                        <a href="" class="btn btn-outline-secondary" id="status_to_ship">Return item</a>
                                    </div>
                                    <div class="col-auto px-1">
                                        <a href="{{ route('admin.pending.management.page') }}" class="btn btn-outline-secondary" id="status_delivered">Pending delivery</a>
                                    </div>
                                    <div class="col-auto px-1">
                                        <a href="{{ route('admin.delivery.status.page') }}" class="btn btn-secondary" id="status_return">Delivery Status</a>
                                    </div>
                                </div>

                    

                        
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center align-middle">
                                        <thead class="table-light fw-bold">
                                            <tr>
                                                <th style="width: 10%; color: #593bdb;">Details</th>
                                                <th style="width: 15%; color: #593bdb;">Transaction Date</th>
                                                <th style="width: 20%; color: #593bdb;">Delivered By</th>
                                                <th style="width: 20%; color: #593bdb;">Upload image</th>
                                                <th style="width: 20%; color: #593bdb;">Add notes</th>
                                                <th style="width: 20%; color: #593bdb;">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($deliveryOrders as $transactId => $orders)
                                                @php
                                                    $first = $orders->first();
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <a target="_blank" href="{{ route('admin.delivery.view', $transactId) }}" class="btn btn-outline-primary btn-sm">View</a>
                                                    </td>
                                                    <td style="color: black">{{ \Carbon\Carbon::parse($first->transaction_date)->format('m/d/Y') ?? 'N/A' }}</td>
                                                    <td style="color: black">{{ $first->delivered_by_name ?? 'N/A' }}</td>
                                                        <td>
                                                            @if($first->upload_image)
                                                                <!-- Button trigger modal -->
                                                                <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#imageModal-{{ $transactId }}">
                                                                    <i class="fa-solid fa-images"></i>
                                                                </button>

                                                                <!-- Modal -->
                                                                <div class="modal fade" id="imageModal-{{ $transactId }}" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel-{{ $transactId }}" aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="imageModalLabel-{{ $transactId }}">Uploaded Image</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body text-center">
                                                                                <img src="{{ asset('storage/upload_images/' . $first->upload_image) }}" alt="Image" class="img-fluid">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <span class="text-muted">No Image</span>
                                                            @endif
                                                        </td>

                                                        <td style="color: black">
                                                            @if($first->upload_notes)
                                                                <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#notesModal-{{ $transactId }}">
                                                                    <i class="fa-solid fa-note-sticky"></i>
                                                                </button>

                                                                <!-- Modal -->
                                                                <div class="modal fade" id="notesModal-{{ $transactId }}" tabindex="-1" role="dialog" aria-labelledby="notesModalLabel-{{ $transactId }}" aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="notesModalLabel-{{ $transactId }}">Upload Notes</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <p style="text-align: left;">{{ $first->upload_notes }}</p>   
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <span class="text-muted">No Notes</span>
                                                            @endif
                                                        </td>

                                                    <td style="color: black">
                                                        <span class="badge 
                                                            {{ $first->status == 'completed' ? 'bg-success' : 'bg-danger' }}">
                                                            {{ ucfirst($first->status) }}
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

    <!-- SCRIPT -->
    <!-- REQUIRED VENDORS -->
    <script src="{{ asset('partials/vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('partials/js/quixnav-init.js') }}"></script>
    <script src="{{ asset('partials/js/custom.min.js') }}"></script>
    <!-- JQUERY VALIDATION -->
    <script src="{{ asset('partials/vendor/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('partials/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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