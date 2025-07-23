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
                            <li class="breadcrumb-item"><a style="color: #A16D28;" href="{{ route('admin.dashboard.page')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active">User Management</li>
                        </ol>
                    </div>
                </div>
                <!-- row -->


                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title" style="font-size: 20px; color: #A16D28;">User Management</h4>
                                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                                    <button id="add_user_button" class="btn btn-outline-primary me-2 mr-2"
                                        data-toggle="modal" data-target=".add-users-modal-lg">+ Add
                                        Users</button>
                                    <!-- ADD MODAL -->
                                    <div class="modal fade add-users-modal-lg" tabindex="-1" role="dialog"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Add Users</h5>
                                                    <button type="button" class="close"
                                                        data-dismiss="modal"><span>&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form class="add_users_validation" action="{{ route('admin.user.add') }}" method="POST">
                                                        @csrf
                                                        <div class="container">
                                                            <div class="row">
                                                                <!-- LEFT COLUMN: Personal Information -->
                                                                <div class="col-md-6">
                                                                    <h5 class="mb-3 text-primary">Personal Information
                                                                    </h5>

                                                                    <!-- First Name -->
                                                                    <div class="form-group row mb-3">
                                                                        <label class="col-sm-4 col-form-label text-end"
                                                                            for="first_name">First Name <span
                                                                                class="text-danger">*</span></label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control"
                                                                                id="first_name" name="first_name"
                                                                                placeholder="Enter first name">
                                                                        </div>
                                                                    </div>

                                                                    <!-- Last Name -->
                                                                    <div class="form-group row mb-3">
                                                                        <label class="col-sm-4 col-form-label text-end"
                                                                            for="last_name">Last Name <span
                                                                                class="text-danger">*</span></label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control"
                                                                                id="last_name" name="last_name"
                                                                                placeholder="Enter last name">
                                                                        </div>
                                                                    </div>

                                                                    <!-- Birthday -->
                                                                    <div class="form-group row mb-3">
                                                                        <label class="col-sm-4 col-form-label text-end"
                                                                            for="birthday">Birthday <span
                                                                                class="text-danger">*</span></label>
                                                                        <div class="col-sm-8">
                                                                            <input type="date" class="form-control"
                                                                                id="birthday" name="birthday">
                                                                        </div>
                                                                    </div>

                                                                    <!-- Role -->
                                                                    <div class="form-group row mb-3">
                                                                        <label class="col-sm-4 col-form-label text-end" for="role">Select Role <span class="text-danger">*</span></label>
                                                                        <div class="col-sm-8">
                                                                            <select class="form-control" id="role" name="role">
                                                                                <option value="">Please select</option>
                                                                                @foreach($positions as $position)
                                                                                    <option value="{{ $position->id }}">{{ $position->position_name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Contract -->
                                                                    <div class="form-group row mb-3">
                                                                        <label class="col-sm-4 col-form-label text-end" for="contract">Contract <span class="text-danger">*</span></label>
                                                                        <div class="col-sm-8">
                                                                            <select class="form-control" id="contract" name="contract">
                                                                                <option value="">Please select</option>
                                                                                @foreach($contracts as $contract)
                                                                                    <option value="{{ $contract->id }}">{{ $contract->contract }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- RIGHT COLUMN: Account Information -->
                                                                <div class="col-md-6">
                                                                    <h5 class="mb-3 text-primary">Account Information
                                                                    </h5>

                                                                    <!-- Email -->
                                                                    <div class="form-group row mb-3">
                                                                        <label class="col-sm-4 col-form-label text-end"
                                                                            for="email">Email <span
                                                                                class="text-danger">*</span></label>
                                                                        <div class="col-sm-8">
                                                                            <input type="email" class="form-control"
                                                                                id="email" name="email"
                                                                                placeholder="Your valid email">
                                                                        </div>
                                                                    </div>

                                                                
                                                                    <!-- Pin -->
                                                                    <div class="form-group row mb-3">
                                                                        <label class="col-sm-4 col-form-label text-end"
                                                                            for="pin">Pin <span
                                                                                class="text-danger">*</span></label>
                                                                        <div class="col-sm-8">
                                                                            <input 
                                                                            type="text" 
                                                                            class="form-control" 
                                                                            id="pin" 
                                                                            name="pin" 
                                                                            placeholder="Enter 4-digit pin"
                                                                            maxlength="4"
                                                                            pattern="\d{4}" 
                                                                            title="Please enter exactly 4 digits"
                                                                            inputmode="numeric" 
                                                                            required
                                                                        >
                                                                        
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                </div>

                                                <!-- Modal Footer -->
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                </div>
                                                </form>


                                            </div>
                                        </div>
                                    </div>
                                    <form method="GET" action="{{ route('admin.user.management.page') }}" class="d-flex gap-2">
                                        {{-- Filter Dropdown --}}
                                        <div class="dropdown">
                                            <button class="btn btn-outline-primary dropdown-toggle mr-2" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                Filter by Role
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                                                <li><a class="dropdown-item" href="{{ route('admin.user.management.page') }}">All</a></li>
                                                @foreach($positions as $position)
                                                    <li><a class="dropdown-item" href="{{ route('admin.user.management.page', ['role' => $position->id]) }}">{{ $position->position_name }}</a></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    
                                        {{-- Sort Dropdown --}}
                                        <div class="dropdown">
                                            <button class="btn btn-outline-primary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                Sort Alphabetically
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                                                <li><a class="dropdown-item" href="{{ route('admin.user.management.page', ['sort' => 'asc']) }}">A-Z</a></li>
                                                <li><a class="dropdown-item" href="{{ route('admin.user.management.page', ['sort' => 'desc']) }}">Z-A</a></li>
                                            </ul>
                                        </div>
                                    </form>
                                    
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example" class="display" style="min-width: 845px">
                                        <thead>
                                            <tr>
                                                <th style="color: #A16D28;">Image</th>
                                                <th style="color: #A16D28;">Name</th>
                                                <th style="color: #A16D28;">Role</th>
                                                <th style="color: #A16D28;">Contract</th>
                                                <th style="color: #A16D28;">Email</th>
                                                <th style="color: #A16D28;">Username</th>
                                                <th style="color: #A16D28;">Pin</th>
                                                <th style="color: #A16D28;">Attempt</th>
                                                <th style="color: #A16D28;">Status</th>
                                                <th style="color: #A16D28;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($employees as $employee)
                                            <tr>
                                                <td style="color: black;">
                                                    <img src="https://images.rawpixel.com/image_png_800/czNmcy1wcml2YXRlL3Jhd3BpeGVsX2ltYWdlcy93ZWJzaXRlX2NvbnRlbnQvdjkzNy1hZXctMTY1LnBuZw.png?s=b4SEVfKYcskH9PiGnSKmpM9SloVv-yAI_PKnNBsL-3o" alt="Default Image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                                                </td>
                                                <td style="color: black;">{{ $employee->employee_firstname }} {{ $employee->employee_lastname }}</td>
                                                <td style="color: black;">{{ $employee->position_name ?? 'N/A' }}</td>
                                                <td style="color: black;">{{ $employee->contract ?? 'N/A' }}</td>
                                                <td style="color: black;">{{ $employee->email }}</td>
                                                <td style="color: black;">{{ $employee->username }}</td>
                                                <td style="color: black;">{{ str_repeat('*', strlen($employee->pin)) }}</td>
                                                <td style="color: black;">{{ $employee->login_attempts }}</td>
                                                <td style="color: black;">{{ $employee->status }}</td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <button class="btn btn-outline-warning mr-2" data-toggle="modal" data-target="#updateUserModal{{ $employee->id }}">
                                                            Update
                                                        </button>
                                            
                                                        <form action="{{ route('admin.user.archive', $employee->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to archive this user?');">
                                                            @csrf
                                                            <button type="submit" class="btn btn-outline-danger">Archive</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            
                                            <!-- Update Modal for this user -->
                                            <div class="modal fade" id="updateUserModal{{ $employee->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Update User - {{ $employee->employee_firstname }} {{ $employee->employee_lastname }}</h5>
                                                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('admin.user.update', $employee->id) }}" method="POST" class="update_users_validation">
                                                                @csrf
                                                                @method('PUT')
                                            
                                                                <div class="container">
                                                                    <div class="row">
                                                                        <!-- LEFT COLUMN: Personal Information -->
                                                                        <div class="col-md-6">
                                                                            <h5 class="mb-3 text-primary">Personal Information</h5>
                                            
                                                                            <!-- First Name -->
                                                                            <div class="form-group row mb-3">
                                                                                <label class="col-sm-4 col-form-label text-end" for="first_name_{{ $employee->id }}">First Name <span class="text-danger">*</span></label>
                                                                                <div class="col-sm-8">
                                                                                    <input type="text" class="form-control" id="first_name_{{ $employee->id }}" name="first_name" value="{{ $employee->employee_firstname }}" required>
                                                                                </div>
                                                                            </div>
                                            
                                                                            <!-- Last Name -->
                                                                            <div class="form-group row mb-3">
                                                                                <label class="col-sm-4 col-form-label text-end" for="last_name_{{ $employee->id }}">Last Name <span class="text-danger">*</span></label>
                                                                                <div class="col-sm-8">
                                                                                    <input type="text" class="form-control" id="last_name_{{ $employee->id }}" name="last_name" value="{{ $employee->employee_lastname }}" required>
                                                                                </div>
                                                                            </div>
                                            
                                                                            <!-- Role -->
                                                                            <div class="form-group row mb-3">
                                                                                <label class="col-sm-4 col-form-label text-end" for="role_{{ $employee->id }}">Select Role <span class="text-danger">*</span></label>
                                                                                <div class="col-sm-8">
                                                                                    <select class="form-control" id="role_{{ $employee->id }}" name="role" required>
                                                                                        <option value="">Please select</option>
                                                                                        @foreach($positions as $position)
                                                                                            <option value="{{ $position->id }}" {{ $employee->position_id == $position->id ? 'selected' : '' }}>
                                                                                                {{ $position->position_name }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                            
                                                                            <!-- Contract -->
                                                                            <div class="form-group row mb-3">
                                                                                <label class="col-sm-4 col-form-label text-end" for="contract_{{ $employee->id }}">Contract <span class="text-danger">*</span></label>
                                                                                <div class="col-sm-8">
                                                                                    <select class="form-control" id="contract_{{ $employee->id }}" name="contract" required>
                                                                                        <option value="">Please select</option>
                                                                                        @foreach($contracts as $contract)
                                                                                            <option value="{{ $contract->id }}" {{ $employee->contract_id == $contract->id ? 'selected' : '' }}>
                                                                                                {{ $contract->contract }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                            
                                                                        <!-- RIGHT COLUMN: Account Information -->
                                                                        <div class="col-md-6">
                                                                            <h5 class="mb-3 text-primary">Account Information</h5>
                                            
                                                                            <!-- Email -->
                                                                            <div class="form-group row mb-3">
                                                                                <label class="col-sm-4 col-form-label text-end" for="email_{{ $employee->id }}">Email <span class="text-danger">*</span></label>
                                                                                <div class="col-sm-8">
                                                                                    <input type="email" class="form-control" id="email_{{ $employee->id }}" name="email" value="{{ $employee->email }}" required>
                                                                                </div>
                                                                            </div>
                                            
                                                                            <!-- Username -->
                                                                            <div class="form-group row mb-3">
                                                                                <label class="col-sm-4 col-form-label text-end" for="username_{{ $employee->id }}">Username <span class="text-danger">*</span></label>
                                                                                <div class="col-sm-8">
                                                                                    <input type="text" class="form-control" id="username_{{ $employee->id }}" name="username" value="{{ $employee->username }}" required>
                                                                                </div>
                                                                            </div>
                                            
                                                                            <!-- Pin -->
                                                                            <div class="form-group row mb-3">
                                                                                <label class="col-sm-4 col-form-label text-end" for="pin_{{ $employee->id }}">Pin <span class="text-danger">*</span></label>
                                                                                <div class="col-sm-8">
                                                                                    <input
                                                                                        type="text"
                                                                                        class="form-control"
                                                                                        id="pin_{{ $employee->id }}"
                                                                                        name="pin"
                                                                                        value="{{ $employee->pin }}"
                                                                                        maxlength="4"
                                                                                        pattern="\d{4}"
                                                                                        title="Please enter exactly 4 digits"
                                                                                        inputmode="numeric"
                                                                                        required
                                                                                    >
                                                                                </div>
                                                                            </div>

                                                                            <!-- Status -->
                                                                            <div class="form-group row mb-3">
                                                                                <label class="col-sm-4 col-form-label text-end" for="status_{{ $employee->id }}">Status <span class="text-danger">*</span></label>
                                                                                <div class="col-sm-8">
                                                                                    <select class="form-control" id="status_{{ $employee->id }}" name="status" required>
                                                                                        <option value="">Please select</option>
                                                                                        <option value="Unlocked" {{ $employee->status == 'Unlocked' ? 'selected' : '' }}>Unlocked</option>
                                                                                        <option value="Locked" {{ $employee->status == 'Locked' ? 'selected' : '' }}>Locked</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                            
                                                                <!-- Modal Footer -->
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                                </div>
                                            
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
    </div>

    <!-- SCRIPTS -->
    <!-- REQUIRED VENDORS -->
    <script src="{{ asset('partials/vendor/global/global.min.js') }}"></script>
    <!-- Bootstrap 5 JS + Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

    <script src="{{ asset('partials/js/quixnav-init.js') }}"></script>
    <script src="{{ asset('partials/js/custom.min.js') }}"></script>
    <!-- DATATABLE PLUGINS -->
    <script src="{{ asset('partials/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('partials/js/plugins-init/datatables.init.js') }}"></script>

    <!-- JQUERY VALIDATION -->
    <script src="{{ asset('partials/vendor/jquery-validation/jquery.validate.min.js') }}"></script>
    <!-- ADD USERS VALIDATION -->
    <script>
        jQuery(".add_users_validation").validate({
            rules: {
                first_name: {
                    required: true,
                    minlength: 3
                },
                last_name: {
                    required: true,
                    minlength: 3
                },
                birthday: {
                    required: true,
                    date: true
                },
                role: {
                    required: true
                },
                contract: {
                    required: true
                },
                status: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                username: {
                    required: true,
                    minlength: 3
                },
                password: {
                    required: true,
                    minlength: 5
                },
                confirm_password: {
                    required: true,
                    equalTo: "#password"
                }
            },
            messages: {
                first_name: {
                    required: "Please enter a first name",
                    minlength: "Your first name must be at least 3 characters"
                },
                last_name: {
                    required: "Please enter a last name",
                    minlength: "Your last name must be at least 3 characters"
                },
                birthday: {
                    required: "Please select a birthday"
                },
                role: {
                    required: "Please select a role"
                },
                contract: {
                    required: "Please select a contract"
                },
                status: {
                    required: "Please enter a status"
                },
                email: {
                    required: "Please enter an email address",
                    email: "Please enter a valid email address"
                },
                username: {
                    required: "Please enter a username",
                    minlength: "Username must be at least 3 characters"
                },
                password: {
                    required: "Please provide a password",
                    minlength: "Password must be at least 5 characters long"
                },
                confirm_password: {
                    required: "Please confirm your password",
                    equalTo: "Passwords do not match"
                }
            },
            ignore: [],
            errorClass: "invalid-feedback animated fadeInUp",
            errorElement: "div",
            errorPlacement: function (error, element) {
                jQuery(element).parents(".form-group > div").append(error);
            },
            highlight: function (element) {
                jQuery(element).closest(".form-group").removeClass("is-valid").addClass("is-invalid");
            },
            success: function (label) {
                jQuery(label).closest(".form-group").removeClass("is-invalid").addClass("is-valid");
                jQuery(label).remove();
            }
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