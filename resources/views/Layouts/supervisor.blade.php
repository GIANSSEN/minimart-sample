<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Supervisor Panel</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --primary-blue: #4e73df;
            --primary-yellow: #ffd966;
            --dark-blue: #1e3a8a;
            --sidebar-width: 280px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fc;
            overflow-x: hidden;
        }
        
        #wrapper {
            display: flex;
        }
        
        /* Sidebar Styles */
        #sidebar-wrapper {
            min-width: var(--sidebar-width);
            max-width: var(--sidebar-width);
            background: linear-gradient(180deg, #2c3e50 0%, #1a2b3c 100%);
            min-height: 100vh;
            transition: all 0.3s;
            box-shadow: 2px 0 20px rgba(0,0,0,0.1);
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 1000;
            overflow-y: auto;
        }
        
        #sidebar-wrapper::-webkit-scrollbar {
            width: 5px;
        }
        
        #sidebar-wrapper::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.1);
        }
        
        #sidebar-wrapper::-webkit-scrollbar-thumb {
            background: var(--primary-yellow);
            border-radius: 10px;
        }
        
        #page-content-wrapper {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: all 0.3s;
            min-height: 100vh;
        }
        
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            position: sticky;
            top: 0;
            background: linear-gradient(180deg, #2c3e50 0%, #1a2b3c 100%);
            z-index: 100;
        }
        
        .sidebar-header h3 {
            font-weight: 700;
            margin: 0;
            color: white;
        }
        
        .sidebar-header h3 i {
            color: var(--primary-yellow);
            margin-right: 10px;
        }
        
        .user-info {
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            background: linear-gradient(180deg, #2c3e50 0%, #1a2b3c 100%);
        }
        
        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: var(--primary-yellow);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--dark-blue);
            font-weight: bold;
            font-size: 1.2rem;
            margin-right: 12px;
        }
        
        .user-details h6 {
            margin: 0;
            font-weight: 600;
            color: white;
        }
        
        .user-details p {
            margin: 0;
            font-size: 0.8rem;
            color: rgba(255,255,255,0.7);
        }
        
        .online-dot {
            width: 8px;
            height: 8px;
            background: #1cc88a;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }
        
        .nav-menu {
            padding: 1rem 0;
        }
        
        .nav-link {
            padding: 0.8rem 1.5rem;
            color: rgba(255,255,255,0.8);
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.3s;
            cursor: pointer;
            border-left: 4px solid transparent;
        }
        
        .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            padding-left: 2rem;
        }
        
        .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: white;
            border-left-color: var(--primary-yellow);
        }
        
        .nav-link i {
            width: 24px;
            margin-right: 12px;
            font-size: 1.1rem;
            color: var(--primary-yellow);
        }
        
        .nav-link .arrow {
            margin-left: auto;
            transition: transform 0.3s;
        }
        
        .nav-link .arrow.rotated {
            transform: rotate(90deg);
        }
        
        /* Submenu Styles */
        .submenu {
            list-style: none;
            padding: 0;
            margin: 0;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
            background: rgba(0,0,0,0.1);
            position: relative;
        }

        .submenu.show {
            max-height: 1000px;
            transition: max-height 0.4s ease-in;
            padding-bottom: 8px;
        }

        .submenu li {
            position: relative;
        }

        .submenu li::before {
            content: "";
            position: absolute;
            left: 35.5px;
            top: -100px;
            bottom: 50%;
            width: 14px;
            border-left: 1.5px solid rgba(255, 255, 255, 0.15);
            border-bottom: 1.5px solid rgba(255, 255, 255, 0.15);
            border-bottom-left-radius: 8px;
            z-index: 1;
        }
        
        .submenu li a {
            padding: 0.6rem 1rem 0.6rem 60px;
            color: rgba(255,255,255,0.7);
            display: flex;
            align-items: center;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s;
            border-left: none;
            position: relative;
            z-index: 2;
        }

        .submenu li a i {
            width: 20px;
            text-align: center;
            margin-right: 12px;
            font-size: 1rem;
        }
        
        .submenu li a:hover {
            color: white;
            background: rgba(255,255,255,0.1);
        }
        
        .submenu li a.active {
            color: var(--primary-yellow);
            font-weight: 600;
        }
        
        /* Top Navbar */
        .navbar-top {
            background: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }
        
        .menu-toggle {
            display: none;
            background: transparent;
            border: none;
            color: #2c3e50;
            font-size: 1.5rem;
            cursor: pointer;
        }
        
        .user-dropdown {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }
        
        .user-dropdown img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid var(--primary-yellow);
        }
        
        /* Page Content */
        .page-content {
            padding: 2rem;
        }
        
        /* Alert Styles */
        .alert {
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            border: none;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border-left: 4px solid #28a745;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        
        /* Stat Cards */
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            transition: all 0.3s;
            height: 100%;
            border-bottom: 4px solid;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .stat-card.primary { border-bottom-color: #3498db; }
        .stat-card.success { border-bottom-color: #2ecc71; }
        .stat-card.warning { border-bottom-color: #f39c12; }
        .stat-card.danger { border-bottom-color: #e74c3c; }
        .stat-card.info { border-bottom-color: #00c0ef; }
        
        /* Responsive */
        @media (max-width: 768px) {
            #sidebar-wrapper {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            #sidebar-wrapper.toggled {
                margin-left: 0;
            }
            #page-content-wrapper {
                margin-left: 0;
            }
            .menu-toggle {
                display: block;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <div class="sidebar-header">
                <h3><i class="fas fa-barcode"></i> POS Barcode</h3>
            </div>
            
            <!-- User Info -->
            <div class="user-info">
                <div class="user-avatar">
                    {{ substr(Auth::user()->full_name ?? 'S', 0, 1) }}
                </div>
                <div class="user-details">
                    <h6>{{ Auth::user()->full_name ?? 'Supervisor' }}</h6>
                    <p><span class="online-dot"></span> Online</p>
                </div>
            </div>
            
            <!-- Navigation Menu -->
            <div class="nav-menu">
                <!-- Dashboard -->
                <a href="{{ route('supervisor.dashboard') }}" class="nav-link {{ request()->routeIs('supervisor.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-dashboard"></i> Dashboard
                </a>
                
                <!-- Sales Management -->
                <div class="nav-link {{ request()->routeIs('supervisor.sales.*') ? 'active' : '' }}" onclick="toggleSubmenu('salesSubmenu', this)">
                    <i class="fas fa-shopping-cart"></i> Sales Management
                    <i class="fas fa-chevron-right arrow" id="salesArrow"></i>
                </div>
                <ul class="submenu" id="salesSubmenu">
                    <li>
                        <a href="{{ route('supervisor.sales.index') }}" class="{{ request()->routeIs('supervisor.sales.index') && !request()->input('status') ? 'active' : '' }}">
                            <i class="fas fa-list me-2"></i> All Sales
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('supervisor.sales.index', ['status' => 'pending_void']) }}" class="{{ request()->input('status') == 'pending_void' ? 'active' : '' }}">
                            <i class="fas fa-ban me-2"></i> Pending Voids
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('supervisor.sales.index', ['status' => 'pending_refund']) }}" class="{{ request()->input('status') == 'pending_refund' ? 'active' : '' }}">
                            <i class="fas fa-undo-alt me-2"></i> Pending Refunds
                        </a>
                    </li>
                </ul>

                <!-- Transactions -->
                <a href="{{ route('supervisor.transactions.index') }}" class="nav-link {{ request()->routeIs('supervisor.transactions.*') ? 'active' : '' }}">
                    <i class="fas fa-receipt"></i> Transactions
                </a>

                <!-- Returns & Refunds -->
                <a href="{{ route('supervisor.returns.index') }}" class="nav-link {{ request()->routeIs('supervisor.returns.*') ? 'active' : '' }}">
                    <i class="fas fa-undo"></i> Returns & Refunds
                </a>

                <!-- Customers -->
                <a href="{{ route('supervisor.customers.index') }}" class="nav-link {{ request()->routeIs('supervisor.customers.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Customers
                </a>
                
                <!-- Cash Management -->
                <div class="nav-link {{ request()->routeIs('supervisor.cash.*') ? 'active' : '' }}" onclick="toggleSubmenu('cashSubmenu', this)">
                    <i class="fas fa-money-bill-wave"></i> Cash Management
                    <i class="fas fa-chevron-right arrow" id="cashArrow"></i>
                </div>
                <ul class="submenu" id="cashSubmenu">
                    <li>
                        <a href="{{ route('supervisor.cash.drops') }}" class="{{ request()->routeIs('supervisor.cash.drops') ? 'active' : '' }}">
                            <i class="fas fa-history me-2"></i> Cash Drops
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('supervisor.cash.create-drop') }}" class="{{ request()->routeIs('supervisor.cash.create-drop') ? 'active' : '' }}">
                            <i class="fas fa-plus-circle me-2"></i> New Cash Drop
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('supervisor.cash.shift-reports') }}" class="{{ request()->routeIs('supervisor.cash.shift-reports') ? 'active' : '' }}">
                            <i class="fas fa-chart-line me-2"></i> Shift Reports
                        </a>
                    </li>
                </ul>
                
                <!-- Reports -->
                <div class="nav-link {{ request()->routeIs('supervisor.reports.*') ? 'active' : '' }}" onclick="toggleSubmenu('reportsSubmenu', this)">
                    <i class="fas fa-chart-bar"></i> Reports
                    <i class="fas fa-chevron-right arrow" id="reportsArrow"></i>
                </div>
                <ul class="submenu" id="reportsSubmenu">
                    <li>
                        <a href="{{ route('supervisor.reports.sales') }}" class="{{ request()->routeIs('supervisor.reports.sales') ? 'active' : '' }}">
                            <i class="fas fa-chart-line me-2"></i> Sales Report
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('supervisor.reports.inventory') }}" class="{{ request()->routeIs('supervisor.reports.inventory') ? 'active' : '' }}">
                            <i class="fas fa-boxes me-2"></i> Inventory Report
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('supervisor.reports.profit-loss') }}" class="{{ request()->routeIs('supervisor.reports.profit-loss') ? 'active' : '' }}">
                            <i class="fas fa-dollar-sign me-2"></i> Profit & Loss
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <!-- Top Navbar -->
            <nav class="navbar-top">
                <div class="d-flex align-items-center">
                    <button class="menu-toggle me-3" id="menu-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="page-title">@yield('title', 'Supervisor Dashboard')</h1>
                </div>
                
                <div class="dropdown">
                    <div class="user-dropdown" data-bs-toggle="dropdown">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->full_name ?? 'Supervisor') }}&background=2c3e50&color=fff&size=40" alt="User">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#" onclick="showProfile()"><i class="fas fa-user me-2"></i> Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
            
            <!-- Page Content -->
            <div class="page-content">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle sidebar on mobile
        document.getElementById('menu-toggle')?.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('sidebar-wrapper').classList.toggle('toggled');
        });

        // Toggle submenu function
        function toggleSubmenu(id, element) {
            const submenu = document.getElementById(id);
            const arrow = element.querySelector('.arrow');
            const isShown = submenu.classList.contains('show');
            
            // Close all other open submenus
            document.querySelectorAll('.submenu.show').forEach(s => {
                if (s.id !== id) {
                    s.classList.remove('show');
                    // Find the arrow for this submenu to reset rotation
                    const otherToggle = document.querySelector(`[onclick*="${s.id}"]`);
                    if (otherToggle) {
                        const otherArrow = otherToggle.querySelector('.arrow');
                        if (otherArrow) otherArrow.classList.remove('rotated');
                    }
                }
            });
            
            // Toggle the clicked one
            if (submenu) {
                if (isShown) {
                    submenu.classList.remove('show');
                    if (arrow) arrow.classList.remove('rotated');
                } else {
                    submenu.classList.add('show');
                    if (arrow) arrow.classList.add('rotated');
                }
            }
        }

        // Auto-expand submenu based on current route
        document.addEventListener('DOMContentLoaded', function() {
            @if (request()->routeIs('supervisor.sales.*'))
                const salesSubmenu = document.getElementById('salesSubmenu');
                const salesArrow = document.querySelector('[onclick="toggleSubmenu(\'salesSubmenu\', this)"] .arrow');
                if (salesSubmenu) {
                    salesSubmenu.classList.add('show');
                    if (salesArrow) salesArrow.classList.add('rotated');
                }
            @endif

            @if (request()->routeIs('supervisor.cash.*'))
                const cashSubmenu = document.getElementById('cashSubmenu');
                const cashArrow = document.querySelector('[onclick="toggleSubmenu(\'cashSubmenu\', this)"] .arrow');
                if (cashSubmenu) {
                    cashSubmenu.classList.add('show');
                    if (cashArrow) cashArrow.classList.add('rotated');
                }
            @endif

            @if (request()->routeIs('supervisor.reports.*'))
                const reportsSubmenu = document.getElementById('reportsSubmenu');
                const reportsArrow = document.querySelector('[onclick="toggleSubmenu(\'reportsSubmenu\', this)"] .arrow');
                if (reportsSubmenu) {
                    reportsSubmenu.classList.add('show');
                    if (reportsArrow) reportsArrow.classList.add('rotated');
                }
            @endif
        });

        // Coming Soon notification
        function showComingSoon() {
            Swal.fire({
                title: 'Coming Soon!',
                text: 'This feature is under development.',
                icon: 'info',
                confirmButtonColor: '#2c3e50',
                confirmButtonText: 'Got it'
            });
        }

        // Profile modal
        function showProfile() {
            Swal.fire({
                title: 'Supervisor Profile',
                html: `
                    <div class="text-start">
                        <p><strong>Name:</strong> {{ Auth::user()->full_name ?? 'Supervisor' }}</p>
                        <p><strong>Email:</strong> {{ Auth::user()->email ?? 'supervisor@example.com' }}</p>
                        <p><strong>Role:</strong> Senior Cashier</p>
                        <p><strong>Last Login:</strong> {{ Auth::user()->last_login ? Auth::user()->last_login->format('M d, Y h:i A') : 'N/A' }}</p>
                    </div>
                `,
                icon: 'info',
                confirmButtonColor: '#2c3e50',
                confirmButtonText: 'Close'
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>
