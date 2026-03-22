<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'POS') - {{ config('app.name') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --primary-blue: #3B82F6;
            --primary-orange: #F97316;
            --dark-blue: #3b82f6;
            --bg-color: #F8FAFC;
        }
        
        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: var(--bg-color);
            color: #1e293b;
        }
        
        .navbar-cashier {
            background: var(--dark-blue);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 0.75rem 2rem;
        }

        .brand-logo-container {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .brand-box {
            width: 42px;
            height: 42px;
            background: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 5px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .brand-box img {
            max-width: 100%;
            height: auto;
            object-fit: contain;
        }

        .brand-name {
            font-size: 1.25rem;
            font-weight: 800;
            color: #F8FAFC;
            letter-spacing: -0.5px;
            text-transform: uppercase;
            margin: 0;
        }

        .nav-user-info {
            background: rgba(255, 255, 255, 0.1);
            padding: 5px 15px;
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s;
        }

        .nav-user-info:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .page-content {
            padding: 1.5rem;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-cashier sticky-top">
        <div class="container-fluid">
            <a href="{{ route('cashier.pos.index') }}" class="brand-logo-container">
                <div class="brand-box">
                    @if(isset($settings['store_logo']) && $settings['store_logo'])
                        <img src="{{ asset('uploads/settings/' . $settings['store_logo']) }}" alt="Logo">
                    @else
                        <i class="fas fa-shopping-basket text-primary"></i>
                    @endif
                </div>
                <h1 class="brand-name">{{ $settings['store_name'] ?? config('app.name') }}</h1>
            </a>
            
            <div class="d-flex align-items-center gap-3">
                <div class="d-none d-md-flex align-items-center me-3">
                    <span class="badge bg-success rounded-pill px-3 py-2">
                        <i class="fas fa-check-circle me-1"></i> POS System
                    </span>
                </div>

                <div class="dropdown">
                    <button class="btn nav-user-info dropdown-toggle text-white border-0 d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->full_name ?? 'Cashier') }}&background=3B82F6&color=fff&size=32" 
                             class="rounded-circle" width="30" height="30">
                        <span class="d-none d-sm-inline fw-semibold">{{ Auth::user()->full_name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2">
                        <li class="px-3 py-2 border-bottom">
                            <small class="text-muted d-block">Signed in as</small>
                            <span class="fw-bold">{{ Auth::user()->email }}</span>
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('cashier.sales.index') }}">
                                <i class="fas fa-history me-2 text-primary"></i> Sales History
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger py-2">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    
    <div class="page-content">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        
        @yield('content')
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
