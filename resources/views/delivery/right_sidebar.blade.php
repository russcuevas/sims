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

                    <li class="nav-item dropdown header-profile">
                        <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                            <i class="mdi mdi-account"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="{{ route('delivery.profile.page') }}" class="dropdown-item">
                                <i class="icon-user"></i>
                                <span class="ml-2">Profile </span>
                            </a>
                            <form method="POST" action="{{ route('logout.request') }}">
                                @csrf
                                <button type="submit" class="dropdown-item" style="border: none; background: none;">
                                    <i class="icon-key"></i>
                                    <span class="ml-2">Logout</span>
                                </button>
                            </form>
                            
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>