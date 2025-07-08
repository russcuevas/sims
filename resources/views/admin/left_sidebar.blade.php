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
            <li class="nav-label first">Main Menu</li>
            <li><a href="{{ route('admin.dashboard.page') }}" aria-expanded="false"><i class="icon icon-home"></i><span
                        class="nav-text">Dashboard</span></a></li>
            <li><a href="{{ route('admin.user.management.page') }}" aria-expanded="false"><i class="icon icon-single-04"></i><span
                        class="nav-text">User Management</span></a>
            </li>
            <li><a href="{{ route('admin.stock.in.page') }}" aria-expanded="false"><i class="icon icon-form"></i><span
                        class="nav-text">Stock In</span></a>
            </li>
            <li><a href="{{ route('admin.stock.management.page') }}" aria-expanded="false"><i class="icon icon-layout-25"></i><span
                        class="nav-text">Stock Management</span></a>
            </li>
            <li><a href="process_management.html" aria-expanded="false"><i class="icon icon-app-store"></i><span
                        class="nav-text">Process Management</span></a>
            </li>
            <li><a href="delivery_management.html" aria-expanded="false"><i class="icon icon-cart-9"></i><span
                        class="nav-text">Delivery
                        Management</span></a>
            </li>
            <li><a href="sales_reports.html" aria-expanded="false"><i class="icon icon-chart-bar-33"></i><span
                        class="nav-text">Sales
                        Report</span></a>
            </li>
            <li><a href="archive.html" aria-expanded="false"><i class="icon icon-card-update"></i><span
                        class="nav-text">Archive
                    </span></a>
            </li>

        </ul>
    </div>
</div>