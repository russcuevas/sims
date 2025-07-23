<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>

    .nav-header {
        height: 5rem;
        width: 17.1875rem;
        display: inline-block;
        text-align: left;
        position: absolute;
        left: 0;
        top: 0;
        background-color: #A16D28 !important;
        transition: all .2s ease;
        z-index: 4;
    }
    .quixnav {
        width: 17.1875rem;
        padding-bottom: 112px;
        height: 100%;
        position: absolute;
        top: 5rem 80px;
        padding-top: 0;
        z-index: 2;
        background-color: #A16D28 !important;
        color: black !important;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        transition: all .2s ease;
    }
    .quixnav .metismenu > li:hover > a, .quixnav .metismenu > li:focus > a, .quixnav .metismenu > li.mm-active > a {
        background-color: black !important;
        color: white !important;
    }

    .quixnav .metismenu > li > a {
        color: white;
    }
</style>
<div class="quixnav">
    <div class="quixnav-scroll">

        <a href="{{ route('admin.dashboard.page') }}" class="brand-logo"
        style="display: flex; margin-top: 20px; flex-direction: column; align-items: center; text-decoration: none;">
        <img class="logo-abbr" src="{{ asset('partials/images/logo.png') }}" alt="Logo" style="width: 50px;">
        <h6 style="margin-top: 10px; text-align: center; color: aliceblue;">
            Welcome {{ $role ?? 'User' }},<br>{{ $user->employee_firstname ?? '' }}
        </h6>
     </a>
     

        <ul class="metismenu" id="menu">
            <li class="nav-label first" style="font-weight: 900">Main Menu</li>
            <li><a href="{{ route('delivery.dashboard.page') }}" aria-expanded="false"><i class="fa-solid fa-truck-fast"></i><span
                        class="nav-text">Delivery
                        Management</span></a>
            </li>
        </ul>
    </div>
</div>