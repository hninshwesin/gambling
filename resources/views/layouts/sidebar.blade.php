<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="dist/img/logo-xs.png" alt="AdminLTE Docs Logo Small" class="brand-image-xl logo-xs">
        <span class="brand-text font-weight-light">Admin Dashboard</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                {{-- <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image"> --}}
            </div>
            <div class="info">
                <a href="{{ url('/agent_register') }}">
                    <i class="fas fa-user-plus nav-icon"></i> Agent Register</a>
            </div>

        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
                <li class="nav-item menu-open">
                    {{-- <a href="#" class="nav-link active">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Starter Pages
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a> --}}
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/home" class="nav-link active">
                                <i class="fas fa-home nav-icon"></i>
                                <p>Home</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/app_user" class="nav-link active">
                                <i class="fas fa-list-ul nav-icon"></i>
                                <p>Client Detail Lists</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/order" class="nav-link active">
                                <i class="fas fa-list-ul nav-icon"></i>
                                <p>Order Details</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/deposit" class="nav-link active">
                                <i class="fas fa-list-ul nav-icon"></i>
                                <p>Deposits</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/withdraw" class="nav-link active">
                                <i class="fas fa-list-ul nav-icon"></i>
                                <p>Withdraws</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/depositPercents" class="nav-link active">
                                <i class="fas fa-list-ul nav-icon"></i>
                                <p>Deposit Percentage</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/withdrawPercents" class="nav-link active">
                                <i class="fas fa-list-ul nav-icon"></i>
                                <p>Withdraw Percentage</p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Simple Link
                            <span class="right badge badge-danger">New</span>
                        </p>
                    </a>
                </li> --}}
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>