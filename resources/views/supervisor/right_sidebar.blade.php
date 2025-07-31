<style>
    .header-notification .mdi-bell-outline {
        font-size: 1.5rem;
        position: relative;
    }

    .header-notification .badge {
        position: absolute;
        top: 10px;
        right: -5px;
        font-size: 0.8rem;
    }

    .dropdown-menu-right {
        width: 300px;
    }

    #notifications-list {
        max-height: 200px;
        overflow-y: auto;
        padding: 10px;
    }

    .dropdown-item {
        font-size: 0.9rem;
    }

    .notification-item {
        padding: 8px;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
    }

    .notification-item span {
        font-size: 0.85rem;
        color: #555;
    }

</style>

<div class="nav-header">
    <div class="nav-control">
        <div class="hamburger">
            <span class="line"></span><span class="line"></span><span class="line"></span>
        </div>
    </div>
</div>

<div class="header">
    <div class="header-content">
        <nav class="navbar navbar-expand">
            <div class="collapse navbar-collapse justify-content-between">
                <div class="header-left">
                    <!-- <div class="search_bar dropdown">
                        <span class="search_icon p-3 c-pointer" data-toggle="dropdown">
                            <i class="mdi mdi-magnify"></i>
                        </span>
                        <div class="dropdown-menu p-0 m-0">
                            <form>
                                <input class="form-control" type="search" placeholder="Search"
                                    aria-label="Search">
                            </form>
                        </div>
                    </div> -->
                </div>

                <ul class="navbar-nav header-right">

                    <li class="nav-item dropdown header-notification">
                        <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                            <i class="mdi mdi-bell-outline"></i>
                        <span class="badge badge-danger">
                            {{ $lowFinishedProducts ? count($lowFinishedProducts) : 0 }}
                        </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div class="dropdown-header">
                                <h6 class="text-muted">Notifications</h6>
                            </div>

                            <div id="notifications-list">
                                @if(count($lowFinishedProducts) > 0)
                                    @foreach ($lowFinishedProducts as $product)
                                        <div class="notification-item">
                                            <div>
                                                <span class="mr-2">{{ $product->product_name }}:</span>
                                                <span class="text-danger">Low stock: {{ $product->quantity }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center">No notification</div>
                                @endif
                            </div>

                            {{-- <a href="#" class="dropdown-item text-center text-primary">
                                View All Notifications
                            </a> --}}
                        </div>
                    </li>


                    <li class="nav-item dropdown header-profile">
                        <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                            <i class="mdi mdi-account"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="{{ route('supervisor.profile.page') }}" class="dropdown-item">
                                <i class="icon-user"></i>
                                <span class="ml-2">Profile </span>
                            </a>
                            <button id="manual-logout-btn" class="dropdown-item" style="border: none; background: none;">
                                <i class="icon-key"></i>
                                <span class="ml-2">Logout</span>
                            </button>
                        
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>

<script>
    const logoutUser = (isInactivity = false) => {
        if (isInactivity) {
            sessionStorage.setItem('logout_message', 'You have been automatically logged out due to inactivity.');
            sessionStorage.setItem('logout_type', 'warning');
        } else {
            sessionStorage.setItem('logout_message', 'You have been logged out.');
            sessionStorage.setItem('logout_type', 'success');
        }

        fetch("{{ route('logout.request') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json",
                "Content-Type": "application/json"
            },
            credentials: "same-origin"
        }).then(() => {
            window.location.href = "{{ route('login.page') }}";
        });
    };

    let inactivityTime = function () {
        let time;
        const logoutAfter = 5 * 60 * 1000; 

        function resetTimer() {
            clearTimeout(time);
            time = setTimeout(() => logoutUser(true), logoutAfter);
        }

        window.onload = resetTimer;
        document.onmousemove = resetTimer;
        document.onkeydown = resetTimer;
        document.onclick = resetTimer;
        document.onscroll = resetTimer;
    };

    inactivityTime();

    document.getElementById('manual-logout-btn').addEventListener('click', function () {
        logoutUser();
    });
</script>

