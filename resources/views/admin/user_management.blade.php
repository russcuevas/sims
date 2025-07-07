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
    <style>
        form.add_users_validation label {
            color: #000 !important;
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
                            <li class="breadcrumb-item active">User Management</li>
                        </ol>
                    </div>
                </div>
                <!-- row -->


                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title" style="font-size: 20px; color: blueviolet;">User Management</h4>
                                <div>
                                    <button id="add_user_button" class="btn btn-outline-primary me-2"
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
                                                    <form class="add_users_validation" action="#" method="post">
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
                                                                        <label class="col-sm-4 col-form-label text-end"
                                                                            for="role">Select Role <span
                                                                                class="text-danger">*</span></label>
                                                                        <div class="col-sm-8">
                                                                            <select class="form-control" id="role"
                                                                                name="role">
                                                                                <option value="">Please select</option>
                                                                                <option value="admin">Admin</option>
                                                                                <option value="manager">Manager</option>
                                                                                <option value="supervisor">Supervisor
                                                                                </option>
                                                                                <option value="delivery">Delivery
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Contract -->
                                                                    <div class="form-group row mb-3">
                                                                        <label class="col-sm-4 col-form-label text-end"
                                                                            for="contract">Contract <span
                                                                                class="text-danger">*</span></label>
                                                                        <div class="col-sm-8">
                                                                            <select class="form-control" id="contract"
                                                                                name="contract">
                                                                                <option value="">Please select</option>
                                                                                <option value="1 year - 5 years">1 year
                                                                                    - 5 years</option>
                                                                                <option value="5 year - 10 years">5 year
                                                                                    - 10 years</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Status -->
                                                                    <div class="form-group row mb-3">
                                                                        <label class="col-sm-4 col-form-label text-end"
                                                                            for="status">Status <span
                                                                                class="text-danger">*</span></label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control"
                                                                                id="status" name="status"
                                                                                placeholder="Enter status">
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

                                                                    <!-- Username -->
                                                                    <div class="form-group row mb-3">
                                                                        <label class="col-sm-4 col-form-label text-end"
                                                                            for="username">Username <span
                                                                                class="text-danger">*</span></label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control"
                                                                                id="username" name="username"
                                                                                placeholder="Enter username">
                                                                        </div>
                                                                    </div>

                                                                    <!-- Password -->
                                                                    <div class="form-group row mb-3">
                                                                        <label class="col-sm-4 col-form-label text-end"
                                                                            for="password">Password <span
                                                                                class="text-danger">*</span></label>
                                                                        <div class="col-sm-8">
                                                                            <input type="password" class="form-control"
                                                                                id="password" name="password"
                                                                                placeholder="Choose a secure one">
                                                                        </div>
                                                                    </div>

                                                                    <!-- Confirm Password -->
                                                                    <div class="form-group row mb-3">
                                                                        <label class="col-sm-4 col-form-label text-end"
                                                                            for="confirm_password">Confirm Password
                                                                            <span class="text-danger">*</span></label>
                                                                        <div class="col-sm-8">
                                                                            <input type="password" class="form-control"
                                                                                id="confirm_password"
                                                                                name="confirm_password"
                                                                                placeholder="Re-enter password">
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

                                    <button id="filter_button" class="btn btn-outline-primary me-2">Filter</button>
                                    <button id="sort_button" class="btn btn-outline-secondary">Sort</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example" class="display" style="min-width: 845px">
                                        <thead>
                                            <tr>
                                                <th style="color: #593bdb;">Image</th>
                                                <th style="color: #593bdb;">Name</th>
                                                <th style="color: #593bdb;">Role</th>
                                                <th style="color: #593bdb;">Contact</th>
                                                <th style="color: #593bdb;">Email</th>
                                                <th style="color: #593bdb;">Username</th>
                                                <th style="color: #593bdb;">Password</th>
                                                <th style="color: #593bdb;">Pin</th>
                                                <th style="color: #593bdb;">Attempt</th>
                                                <th style="color: #593bdb;">Status</th>
                                                <th style="color: #593bdb;">Action</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="color: black;">Tiger Nixon</td>
                                                <td style="color: black;">Tiger Nixon</td>
                                                <td style="color: black;">Tiger Nixon</td>
                                                <td style="color: black;">Tiger Nixon</td>
                                                <td style="color: black;">Tiger Nixon</td>
                                                <td style="color: black;">Tiger Nixon</td>
                                                <td style="color: black;">Tiger Nixon</td>
                                                <td style="color: black;">Tiger Nixon</td>
                                                <td style="color: black;">Tiger Nixon</td>
                                                <td style="color: black;">Tiger Nixon</td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <button id="filter_button"
                                                            class="btn btn-outline-primary mr-2">Update</button>
                                                        <button id="sort_button"
                                                            class="btn btn-outline-secondary">Archive</button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="color: black;">Tiger Nixon</td>
                                                <td style="color: black;">Tiger Nixon</td>
                                                <td style="color: black;">Tiger Nixon</td>
                                                <td style="color: black;">Tiger Nixon</td>
                                                <td style="color: black;">Tiger Nixon</td>
                                                <td style="color: black;">Tiger Nixon</td>
                                                <td style="color: black;">Tiger Nixon</td>
                                                <td style="color: black;">Tiger Nixon</td>
                                                <td style="color: black;">Tiger Nixon</td>
                                                <td style="color: black;">Tiger Nixon</td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <button id="filter_button"
                                                            class="btn btn-outline-primary mr-2">Update</button>
                                                        <button id="sort_button"
                                                            class="btn btn-outline-secondary">Archive</button>
                                                    </div>
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
    </div>

    <!-- SCRIPTS -->
    <!-- REQUIRED VENDORS -->
    <script src="{{ asset('partials/vendor/global/global.min.js') }}"></script>
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


</body>

</html>