<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Sales & Inventory Management System</title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('partials/images/favicon.png') }}" />
    <link href="{{ asset('partials/css/style.css') }}" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
</head>

<body class="h-100">
    <div class="authincation h-100">
        <div class="container-fluid h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
                                    <h4 class="text-center mb-4">
                                        <img src="https://images.freecreatives.com/wp-content/uploads/2015/04/logo033.png" alt=""
                                            style="width: 150px;" />
                                        <br />
                                        Verify OTP
                                    </h4>

                                    {{-- Expired message --}}
                                    @if(!empty($expired) && $expired)
                                        <div class="alert alert-danger text-center font-weight-bold mb-4">
                                            Sorry, the reset link has expired or is invalid.
                                        </div>
                                    @endif

                                    {{-- OTP Form --}}
                                    @if(!empty($token))
                                        <form method="POST" action="{{ url('/verify-otp') }}">
                                            @csrf
                                            <input type="hidden" name="token" value="{{ $token }}" />
                                            <div class="form-group">
                                                <label style="color: #A16D28;"><strong>Enter OTP:</strong></label>
                                                <input 
                                                    type="text" 
                                                    name="otp" 
                                                    class="form-control" 
                                                    required 
                                                    maxlength="4" 
                                                    pattern="\d{4}" 
                                                    title="Please enter exactly 4 digits"
                                                    inputmode="numeric"
                                                />
                                            </div>
                                            
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary btn-block">Verify OTP</button>
                                            </div>
                                        </form>
                                    @else
                                        <p class="text-center">Please request a new password reset link.</p>
                                        <div class="text-center">
                                            <a href="{{ route('change.password.page') }}" class="btn btn-outline-primary p-3">
                                                Request Password Reset
                                            </a>
                                        </div>
                                    @endif

                                    <div class="new-account mt-3">
                                        <p style="text-align: center;">
                                            <a class="text-primary" href="{{ route('login.page') }}">Back to login</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Required vendors -->
        <script src="{{ asset('partials/vendor/global/global.min.js') }}"></script>
        <script src="{{ asset('partials/js/quixnav-init.js') }}"></script>
        <script src="{{ asset('partials/js/custom.min.js') }}"></script>
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
