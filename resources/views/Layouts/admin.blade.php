<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - CJ's Minimart</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --primary-blue: #3B82F6;
            --primary-orange: #F97316;
            --sidebar-bg: #0F172A; /* Professional Dark Blue */
            --sidebar-text: #94A3B8;
            --sidebar-hover: #1E293B;
            --sidebar-active: #1E293B;
            --sidebar-active-text: #F8FAFC;
            --bg-color: #F8FAFC;
            --content-bg: #FFFFFF;
            --text-color: #0F172A;
            --card-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px 0 rgba(0, 0, 0, 0.03);
            --smooth-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            --sidebar-width: 280px;
        }

        [data-theme="dark"] {
            --sidebar-bg: #030712;
            --sidebar-text: #64748B;
            --sidebar-hover: #111827;
            --sidebar-active: #111827;
            --sidebar-active-text: #F97316;
            --bg-color: #020617;
            --content-bg: #0F172A;
            --text-color: #F8FAFC;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
            --smooth-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.4);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-color);
            color: var(--text-color);
            display: flex;
            min-height: 100vh;
            width: 100%;
            overflow-x: hidden;
            transition: background 0.3s ease;
        }

        #wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        #sidebar-wrapper {
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            display: flex;
            flex-direction: column;
            position: fixed;
            left: 0;
            top: 0;
            box-shadow: var(--smooth-shadow);
            overflow-y: auto;
            border-right: none;
            transition: transform 0.3s ease;
            z-index: 1050;
            padding: 24px 16px;
        }


        .sidebar-header { padding: 0 8px 32px 8px; }

        .brand-logo {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: var(--primary-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            font-weight: 700;
            margin-right: 12px;
        }

        .brand-title { font-size: 1.1rem; font-weight: 700; color: #F8FAFC; }
        .brand-subtitle { font-size: 0.75rem; color: #94A3B8; font-weight: 500; }

        .search-container { padding: 0 8px 24px 8px; }
        .search-box {
            background: var(--sidebar-hover);
            border: 1px solid transparent;
            border-radius: 12px;
            padding: 10px 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: var(--transition);
        }

        .search-box:focus-within {
            border-color: var(--primary-blue);
            background: var(--sidebar-bg);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .search-box input { background: none; border: none; color: var(--sidebar-text); outline: none; width: 100%; font-size: 0.85rem; }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 14px 18px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            border-radius: 12px;
            margin-bottom: 6px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            border: 1px solid transparent;
        }

        .nav-link:hover { 
            background: rgba(255, 255, 255, 0.05); 
            color: #F8FAFC; 
            border-color: rgba(255, 255, 255, 0.05);
            transform: translateX(4px);
        }
        .nav-link.active { 
            background: var(--primary-blue); 
            color: #FFFFFF; 
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        .nav-link i { font-size: 1.1rem; margin-right: 12px; width: 24px; text-align: center; }

        .theme-toggle-btn {
            background: var(--sidebar-hover);
            color: var(--sidebar-text);
            border-radius: 10px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            border: 1px solid transparent;
        }
        .theme-toggle-btn:hover { background: var(--sidebar-bg); border-color: var(--sidebar-hover); color: var(--primary-orange); }

        .user-info { display: none; } /* Superseded by Navbar profile */


        /* Navigation Menu Reset */
        .nav-menu, .nav-menu ul, .nav-menu li {
            list-style: none !important;
            padding: 0;
            margin: 0;
            text-decoration: none !important;
        }
        .nav-menu { padding: 8px 0; }
        .nav-item { list-style: none; margin-bottom: 2px; }

        .arrow { margin-left: auto; font-size: 0.75rem; transition: transform 0.3s ease; opacity: 0.5; }
        .arrow.rotated { transform: rotate(90deg); }

        .badge-count {
            background: var(--primary-orange);
            color: white;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 0.65rem;
            font-weight: 700;
            margin-left: auto;
            margin-right: 8px;
            box-shadow: 0 2px 4px rgba(249, 115, 22, 0.2);
        }

        .submenu {
            list-style: none;
            padding: 0;
            margin: 0;
            max-height: 0;
            overflow: hidden;
            transition: var(--transition);
            position: relative;
        }

        .submenu.show { 
            max-height: 1000px; 
            margin-bottom: 8px; 
            padding-top: 4px;
            padding-bottom: 8px;
        }

        .submenu li {
            position: relative;
        }

        .submenu li::before {
            content: "";
            position: absolute;
            left: 27.5px;
            top: -100px;
            bottom: 50%;
            width: 14px;
            border-left: 1.5px solid rgba(226, 232, 240, 0.15);
            border-bottom: 1.5px solid rgba(226, 232, 240, 0.15);
            border-bottom-left-radius: 8px;
            z-index: 1;
        }

        .submenu li a {
            display: flex;
            align-items: center;
            padding: 10px 16px 10px 52px;
            color: #94A3B8;
            text-decoration: none !important;
            font-size: 0.88rem;
            font-weight: 500;
            border-radius: 10px;
            margin-bottom: 3px;
            transition: all 0.25s ease;
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
            color: var(--primary-blue);
            padding-left: 56px;
        }

        .submenu li a.active {
            color: var(--primary-blue);
            background: rgba(59, 130, 246, 0.08);
            font-weight: 600;
        }


        /* Global Content Dark Mode Compatibility */
        [data-theme="dark"] .dashboard-header-container,
        [data-theme="dark"] .metric-card-glass,
        [data-theme="dark"] .chart-container-glass,
        [data-theme="dark"] .dashboard-card-glass,
        [data-theme="dark"] .transactions-table-card,
        [data-theme="dark"] .card {
            background: rgba(30, 41, 59, 0.7) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: var(--text-color) !important;
        }

        [data-theme="dark"] .welcome-text,
        [data-theme="dark"] .page-title,
        [data-theme="dark"] .card-info h2,
        [data-theme="dark"] .title-box h3,
        [data-theme="dark"] .card-header-premium h3 {
            color: #F8FAFC !important;
        }

        [data-theme="dark"] .premium-dashboard-table thead th {
            background: rgba(15, 23, 42, 0.8);
            border-bottom-color: rgba(255, 255, 255, 0.1);
            color: #cbd5e1;
        }

        [data-theme="dark"] .premium-dashboard-table td {
            border-bottom-color: rgba(255, 255, 255, 0.05);
            color: #cbd5e1;
        }

        [data-theme="dark"] .search-box input {
            color: var(--sidebar-text);
        }
        
        [data-theme="dark"] #sidebar-wrapper {
            --sidebar-bg: #0F172A;
            --sidebar-text: #94A3B8;
            --sidebar-hover: #1E293B;
            --sidebar-active: #1E293B;
        }


        /* Page Content */
        #page-content-wrapper {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            min-width: 0;
            background: var(--bg-color);
            transition: margin-left 0.3s ease;
            width: 100%;
        }

        /* Responsive Sidebar Toggling */
        body.toggled-sidebar #sidebar-wrapper {
            transform: translateX(-100%);
        }

        body.toggled-sidebar #page-content-wrapper {
            margin-left: 0;
        }

        @media (max-width: 991.98px) {
            #sidebar-wrapper {
                transform: translateX(-100%);
            }
            #page-content-wrapper {
                margin-left: 0 !important;
            }
            body.show-sidebar #sidebar-wrapper {
                transform: translateX(0);
            }
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                backdrop-filter: blur(4px);
                z-index: 1040;
                display: none;
            }
            body.show-sidebar .sidebar-overlay {
                display: block;
            }
        }


        .navbar-top {
            background: var(--content-bg);
            padding: 1rem 1.5rem;
            box-shadow: var(--card-shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            position: sticky;
            top: 0;
            z-index: 999;
            transition: var(--transition);
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
            min-width: 0;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            flex-shrink: 0;
        }

        .page-title {
            font-size: clamp(1rem, 2.5vw, 1.25rem);
            font-weight: 700;
            color: var(--text-color);
            margin: 0;
            letter-spacing: -0.5px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        @media (max-width: 768px) {
            .navbar-top {
                padding: 0.75rem 1rem;
            }
            .navbar-right .user-details {
                display: none !important;
            }
            .user-dropdown img {
                width: 36px;
                height: 36px;
            }
        }

        .menu-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--content-bg);
            border: 1px solid rgba(0,0,0,0.05);
            color: var(--text-color);
            width: 40px;
            height: 40px;
            border-radius: 10px;
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: var(--card-shadow);
            flex-shrink: 0;
        }
        .menu-toggle:hover {
            background: var(--sidebar-bg);
            color: #F8FAFC;
            transform: translateY(-2px);
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
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid var(--bg-color);
            box-shadow: var(--card-shadow);
        }

        .page-content {
            padding: clamp(1rem, 3vw, 2rem);
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        /* Responsive - zoom and mobile */
        @media (max-width: 576px) {
            .page-content {
                padding: 0.75rem;
            }
            .navbar-top {
                gap: 0.5rem;
            }
        }
        
        /* Zoom protection & Text safety */
        * {
            overflow-wrap: break-word;
            word-wrap: break-word;
            hyphens: auto;
        }
        
        a, button, span, p, h1, h2, h3, h4, h5, h6 {
            min-width: 0;
        }
        
        .table-responsive {
            border-radius: 12px;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            width: 100%;
            margin-bottom: 1rem;
        }

        /* Prevent image overflow */
        img {
            max-width: 100%;
            height: auto;
        }

        /* Custom scrollbar for better zoom experience */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(0,0,0,0.05);
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.2);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(0,0,0,0.3);
        }

        /* Breakpoint helpers */
        @media (max-width: 576px) {
            .btn span.d-none.d-sm-inline {
                display: none !important;
            }
        }

        /* Brand Colors for Blue Sidebar */
        .brand-logo-container {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 5px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: var(--transition);
        }
        
        .brand-logo-container:hover {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(255, 255, 255, 0.1);
        }

        .brand-box {
            width: 48px;
            height: 48px;
            background: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 6px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(255, 255, 255, 0.1);
            transition: var(--transition);
        }

        .brand-logo-container:hover .brand-box {
            transform: scale(1.05) rotate(-2deg);
        }

        .brand-box img {
            max-width: 100%;
            height: auto;
            object-fit: contain;
        }

        .brand-text-wrapper {
            display: flex;
            flex-direction: column;
        }

        .brand-name {
            font-size: 1.25rem;
            font-weight: 900;
            color: #F8FAFC;
            letter-spacing: -1px;
            line-height: 0.9;
            text-transform: uppercase;
        }

        .brand-tagline {
            font-size: 0.7rem;
            color: var(--primary-orange);
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-top: 2px;
        }

        /* --- UNIFIED PREMIUM HEADER DESIGN --- */
        .page-header-premium {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            padding: 1.25rem 1.5rem;
            border-radius: 16px;
            border: 1px solid #edf2f7;
            margin-bottom: 1.25rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            animation: fadeInHeader 0.4s ease-out;
        }

        [data-theme="dark"] .page-header-premium {
            background: #1e293b;
            border-color: #334155;
        }

        @keyframes fadeInHeader { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

        .header-left { display: flex; align-items: center; gap: 1rem; }
        .header-icon-box {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.25rem;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        }

        .pending-header-icon { background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); }
        .roles-header-icon { background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%); }
        .users-header-icon { background: linear-gradient(135deg, #10B981 0%, #059669 100%); }

        .header-text { display: flex; flex-direction: column; }
        .header-text .page-title { font-size: 1.3rem; font-weight: 800; color: #1e293b; margin: 0; line-height: 1.2; }
        .header-text .page-subtitle { font-size: 0.85rem; color: #64748b; margin: 0; }
        
        [data-theme="dark"] .header-text .page-title { color: #f8fafc; }
        [data-theme="dark"] .header-text .page-subtitle { color: #94a3b8; }

        .header-actions { display: flex; align-items: center; gap: 1rem; }

        .status-indicator-glass {
            background: #F0F9FF;
            border: 1px solid #E0F2FE;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            color: #0369A1;
            font-weight: 700;
            font-size: 0.85rem;
        }

        .pending-indicator { background: #FFFBEB; border-color: #FEF3C7; color: #92400E; }

        .pulse-dot {
            width: 8px; height: 8px;
            background: currentColor;
            border-radius: 50%;
            animation: pulse-dot 2s infinite;
        }

        @keyframes pulse-dot {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(0, 0, 0, 0.2); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(0, 0, 0, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(0, 0, 0, 0); }
        }

        .btn-header-action {
            padding: 0.6rem 1.2rem;
            border-radius: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
            font-size: 0.85rem;
            text-decoration: none;
        }

        .btn-header-primary { background: #3B82F6; color: #fff; border: none; }
        .btn-header-primary:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); color: #fff; }
        
        .btn-header-secondary { background: #F8FAFC; color: #64748B; border: 1px solid #E2E8F0; }
        .btn-header-secondary:hover { background: #F1F5F9; color: #1E293B; }

        /* Unified Submenu UI (Supplier-Style Reference) */
        .page-header-premium {
            background: #fff !important;
            border: 1px solid #eaf0f6 !important;
            border-radius: 20px !important;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.04) !important;
            padding: 1rem 1.25rem !important;
            margin-bottom: 1rem !important;
        }

        .header-icon-box {
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2) !important;
        }

        .filter-card,
        .chart-card,
        .table-card,
        .detail-card,
        .details-card,
        .kpi-card,
        .stat-card {
            background: #fff !important;
            border: 1px solid #eaf0f6 !important;
            border-radius: 18px !important;
            box-shadow: 0 8px 22px rgba(15, 23, 42, 0.03) !important;
        }

        .table-card,
        .detail-card,
        .details-card {
            overflow: hidden;
        }

        .table th {
            background: #f8fafc !important;
            border-bottom: 1px solid #edf2f7 !important;
            color: #475569 !important;
            font-weight: 700 !important;
            letter-spacing: 0.04em;
        }

        .table td {
            border-bottom: 1px solid #f1f5f9 !important;
        }

        .table tbody tr:hover {
            background: #f8fbff !important;
        }

        .search-input-group input,
        .form-control,
        .form-select {
            background: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 12px !important;
        }

        .search-input-group input:focus,
        .form-control:focus,
        .form-select:focus {
            background: #fff !important;
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12) !important;
        }

        .btn-filter,
        .btn-add {
            border-radius: 12px !important;
            font-weight: 700 !important;
            border: none !important;
            box-shadow: 0 8px 16px rgba(37, 99, 235, 0.18);
        }

        /* Premium CRUD Action Buttons */
        .btn-view,
        .btn-edit,
        .btn-del,
        .btn-process,
        .btn-cancel-r {
            width: 38px !important;
            height: 38px !important;
            border-radius: 10px !important;
            border: 1.5px solid #e2e8f0 !important;
            background: #fff !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0 !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05) !important;
            color: #64748b !important;
        }

        .btn-view { border-color: #0dcaf0 !important; color: #0dcaf0 !important; }
        .btn-view:hover { 
            background: #0dcaf0 !important; 
            color: #fff !important; 
            transform: translateY(-2px); 
            box-shadow: 0 5px 15px rgba(13, 202, 240, 0.3) !important;
        }

        .btn-edit, .btn-process { border-color: #0d6efd !important; color: #0d6efd !important; }
        .btn-edit:hover, .btn-process:hover { 
            background: #0d6efd !important; 
            color: #fff !important; 
            transform: translateY(-2px); 
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3) !important;
        }

        .btn-del, .btn-cancel-r { border-color: #dc3545 !important; color: #dc3545 !important; }
        .btn-del:hover, .btn-cancel-r:hover { 
            background: #dc3545 !important; 
            color: #fff !important; 
            transform: translateY(-2px); 
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3) !important;
        }

        [data-theme="dark"] .btn-view,
        [data-theme="dark"] .btn-edit,
        [data-theme="dark"] .btn-del,
        [data-theme="dark"] .btn-process,
        [data-theme="dark"] .btn-cancel-r {
            background: #1e293b !important;
        }

        .kpi-card:hover,
        .stat-card:hover,
        .chart-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08) !important;
        }

        [data-theme="dark"] .page-header-premium,
        [data-theme="dark"] .filter-card,
        [data-theme="dark"] .table-card,
        [data-theme="dark"] .chart-card,
        [data-theme="dark"] .detail-card,
        [data-theme="dark"] .details-card,
        [data-theme="dark"] .kpi-card,
        [data-theme="dark"] .stat-card {
            background: rgba(15, 23, 42, 0.9) !important;
            border-color: rgba(148, 163, 184, 0.2) !important;
        }

        [data-theme="dark"] .table th {
            background: rgba(30, 41, 59, 0.9) !important;
            color: #cbd5e1 !important;
        }

        [data-theme="dark"] .search-input-group input,
        [data-theme="dark"] .form-control,
        [data-theme="dark"] .form-select {
            background: #0b1220 !important;
            border-color: #334155 !important;
            color: #e2e8f0 !important;
        }

        /* --- LOGOUT PREMIUM UI --- */
        .sidebar-footer {
            padding: 0 8px 24px 8px;
        }

        .sidebar-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.08), transparent);
            width: 100%;
        }

        .logout-wrapper {
            padding: 4px;
            background: rgba(255, 255, 255, 0.02);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: var(--transition);
        }

        .logout-wrapper:hover {
            background: rgba(239, 68, 68, 0.05);
            border-color: rgba(239, 68, 68, 0.2);
        }

        .logout-btn {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            background: transparent;
            border: none;
            border-radius: 12px;
            color: #94A3B8;
            text-decoration: none;
            transition: var(--transition);
            text-align: left;
        }

        .logout-icon-box {
            width: 38px;
            height: 38px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #EF4444;
            transition: var(--transition);
        }

        .logout-btn:hover .logout-icon-box {
            background: #EF4444;
            color: white;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
            transform: scale(1.05);
        }

        .logout-text-wrapper {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .logout-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: #F8FAFC;
            transition: var(--transition);
        }

        .logout-subtitle {
            font-size: 0.7rem;
            color: #64748B;
            font-weight: 500;
        }

        /* ==========================================================================
           ELITE PREMIUM UI UTILITIES
           ========================================================================== */
        :root {
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.4);
            --glass-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
            --elite-gradient-primary: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            --elite-gradient-success: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            --elite-gradient-warning: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
            --elite-gradient-danger: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
            --elite-gradient-info: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
            --elite-radius: 18px;
            --transition-premium: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        [data-theme="dark"] {
            --glass-bg: rgba(15, 23, 42, 0.7);
            --glass-border: rgba(255, 255, 255, 0.05);
            --glass-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            box-shadow: var(--glass-shadow);
            border-radius: var(--elite-radius);
            transition: var(--transition-premium);
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.12);
        }

        .text-gradient-primary {
            background: var(--elite-gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-elite-primary {
            background: var(--elite-gradient-primary);
            color: white !important;
            border: none;
            padding: 8px 20px;
            border-radius: 12px;
            font-weight: 600;
            transition: var(--transition-premium);
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }

        .btn-elite-primary:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        }

        .elite-table {
            border-collapse: separate;
            border-spacing: 0 8px;
        }

        .elite-table thead th {
            border: none;
            background: transparent;
            color: #64748b;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 12px 20px;
        }

        .elite-table tbody tr {
            background: var(--glass-bg);
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
            border-radius: 12px;
            transition: var(--transition-premium);
        }

        .elite-table tbody tr td {
            border: none;
            padding: 16px 20px;
            vertical-align: middle;
        }

        .elite-table tbody tr td:first-child { border-top-left-radius: 12px; border-bottom-left-radius: 12px; }
        .elite-table tbody tr td:last-child { border-top-right-radius: 12px; border-bottom-right-radius: 12px; }

        .elite-table tbody tr:hover {
            transform: scale(1.01) translateX(5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            z-index: 10;
            position: relative;
        }

        .stat-icon-elite {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-bottom: 0;
            flex-shrink: 0;
        }

        .badge-elite {
            padding: 5px 12px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        /* --- Animations --- */
        @keyframes fadeInUpElite {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in-up {
            animation: fadeInUpElite 0.6s ease-out forwards;
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>
    <div id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <div class="sidebar-header">
                <div class="brand-logo-container">
                    <div class="brand-box">
                        <img src="{{ asset('images/logo-cjs.png') }}" alt="Logo">
                    </div>
                    <div class="brand-text-wrapper">
                        <span class="brand-name">CJ'S</span>
                        <span class="brand-tagline">Minimart</span>
                    </div>
                </div>
            </div>

            <!-- Compact Actions -->
            <div class="search-container">
                <div class="d-flex gap-2">
                    <div class="search-box flex-grow-1">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Filter..." id="menuSearch">
                    </div>
                    <div class="theme-toggle-btn" id="themeToggle" title="Toggle Mode">
                        <i class="fas fa-moon"></i>
                    </div>
                </div>
            </div>

            @php
                $currentUser = Auth::user();
                $isSupervisorLayout = $currentUser && $currentUser->isSupervisor() && !$currentUser->isAdmin();
                $canViewDashboard = $currentUser && $currentUser->hasPermission('view-dashboard');
                $canManageUsers = $currentUser && $currentUser->hasPermission('manage-users');
                $canManageProducts = $currentUser && $currentUser->hasPermission('manage-products');
                $canManageSuppliers = $currentUser && $currentUser->hasPermission('manage-suppliers');
                $canManageInventory = $currentUser && $currentUser->hasPermission('manage-inventory');
                $canManageSales = $currentUser && $currentUser->hasPermission('manage-sales');
                $canViewReports = $currentUser && $currentUser->hasPermission('view-reports');
                $canManageSystem = $currentUser && $currentUser->hasPermission('manage-system');
            @endphp

            <!-- Navigation Menu -->
            <div class="nav-menu">
                @if ($isSupervisorLayout)
                    @if ($canViewDashboard)
                    <ul class="nav-item">
                        <li>
                            <a href="{{ route('supervisor.dashboard') }}" class="nav-link {{ request()->routeIs('supervisor.dashboard') || request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="fas fa-chart-pie"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                    </ul>
                    @endif

                    @if ($canManageProducts)
                    <ul class="nav-item">
                        <li>
                            <div class="nav-link {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.categories.*') || request()->routeIs('admin.brands.*') || request()->routeIs('admin.uom.*') || request()->routeIs('admin.variations.*') ? 'active' : '' }}" onclick="toggleSubmenu('productSubmenu', this)">
                                <i class="fas fa-boxes"></i>
                                <span>Product Maintenance</span>
                                <i class="fas fa-chevron-right arrow" id="productArrow"></i>
                            </div>
                            <ul class="submenu" id="productSubmenu">
                                <li>
                                    <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                                        <i class="fas fa-list me-2"></i> Product List
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.products.create') }}" class="{{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
                                        <i class="fas fa-plus me-2"></i> Add Product
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                                        <i class="fas fa-tags me-2"></i> Categories
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.brands.index') }}" class="{{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                                        <i class="fas fa-trademark me-2"></i> Brands
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    @endif

                    @if ($canManageSuppliers)
                    <ul class="nav-item">
                        <li>
                            <div class="nav-link {{ request()->routeIs('admin.suppliers.*') || request()->routeIs('admin.purchase-history.*') || request()->routeIs('admin.supplier-returns.*') || request()->routeIs('admin.payment-terms.*') ? 'active' : '' }}" onclick="toggleSubmenu('supplierSubmenu', this)">
                                <i class="fas fa-truck"></i>
                                <span>Supplier Maintenance</span>
                                <i class="fas fa-chevron-right arrow" id="supplierArrow"></i>
                            </div>
                            <ul class="submenu" id="supplierSubmenu">
                                <li>
                                    <a href="{{ route('admin.suppliers.index') }}" class="{{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}">
                                        <i class="fas fa-users me-2"></i> Supplier List
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.suppliers.create') }}" class="{{ request()->routeIs('admin.suppliers.create') ? 'active' : '' }}">
                                        <i class="fas fa-plus me-2"></i> Add Supplier
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.purchase-history.index') }}" class="{{ request()->routeIs('admin.purchase-history.*') ? 'active' : '' }}">
                                        <i class="fas fa-history me-2"></i> Purchase History
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.supplier-returns.index') }}" class="{{ request()->routeIs('admin.supplier-returns.*') ? 'active' : '' }}">
                                        <i class="fas fa-undo me-2"></i> Supplier Returns
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    @endif

                    @if ($canManageInventory)
                    <ul class="nav-item">
                        <li>
                            <div class="nav-link {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}" onclick="toggleSubmenu('inventorySubmenu', this)">
                                <i class="fas fa-warehouse"></i>
                                <span>Inventory</span>
                                <i class="fas fa-chevron-right arrow" id="inventoryArrow"></i>
                            </div>
                            <ul class="submenu" id="inventorySubmenu">
                                <li>
                                    <a href="{{ route('admin.inventory.stock-in') }}" class="{{ request()->routeIs('admin.inventory.stock-in') ? 'active' : '' }}">
                                        <i class="fas fa-arrow-down me-2"></i> Stock In
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.inventory.stock-out') }}" class="{{ request()->routeIs('admin.inventory.stock-out') ? 'active' : '' }}">
                                        <i class="fas fa-arrow-up me-2"></i> Stock Out
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.inventory.alerts') }}" class="{{ request()->routeIs('admin.inventory.alerts') ? 'active' : '' }}">
                                        <i class="fas fa-exclamation-triangle me-2"></i> Alerts
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.inventory.all-history') }}" class="{{ request()->routeIs('admin.inventory.all-history') || request()->routeIs('admin.inventory.history') ? 'active' : '' }}">
                                        <i class="fas fa-history me-2"></i> Stock History
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    @endif

                    @if ($canManageSales)
                    <ul class="nav-item">
                        <li>
                            <div class="nav-link {{ request()->routeIs('supervisor.sales.*') || request()->routeIs('supervisor.transactions.*') || request()->routeIs('supervisor.returns.*') || request()->routeIs('supervisor.customers.*') || request()->routeIs('cashier.pos.*') ? 'active' : '' }}" onclick="toggleSubmenu('salesSubmenu', this)">
                                <i class="fas fa-shopping-cart"></i>
                                <span>Sales</span>
                                <i class="fas fa-chevron-right arrow" id="salesArrow"></i>
                            </div>
                            <ul class="submenu" id="salesSubmenu">
                                <li>
                                    <a href="{{ route('supervisor.transactions.index') }}" class="{{ request()->routeIs('supervisor.transactions.*') ? 'active' : '' }}">
                                        <i class="fas fa-receipt me-2"></i> Transactions
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('supervisor.returns.index') }}" class="{{ request()->routeIs('supervisor.returns.*') ? 'active' : '' }}">
                                        <i class="fas fa-undo-alt me-2"></i> Returns & Refunds
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('supervisor.sales.index', ['status' => 'pending_void']) }}" class="{{ request()->input('status') === 'pending_void' ? 'active' : '' }}">
                                        <i class="fas fa-ban me-2"></i> Pending Voids
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('supervisor.sales.index', ['status' => 'pending_refund']) }}" class="{{ request()->input('status') === 'pending_refund' ? 'active' : '' }}">
                                        <i class="fas fa-undo-alt me-2"></i> Pending Refunds
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('cashier.pos.index') }}" class="{{ request()->routeIs('cashier.pos.*') ? 'active' : '' }}">
                                        <i class="fas fa-cash-register me-2"></i> Point of Sale
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    @endif

                    @if ($canViewReports)
                    <ul class="nav-item">
                        <li>
                            <div class="nav-link {{ request()->routeIs('supervisor.reports.*') ? 'active' : '' }}" onclick="toggleSubmenu('reportsSubmenu', this)">
                                <i class="fas fa-chart-bar"></i>
                                <span>Reports</span>
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
                                        <i class="fas fa-coins me-2"></i> Profit & Loss
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    @endif
                @else
                <!-- DASHBOARD -->
                @if ($canViewDashboard)
                <ul class="nav-item">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-chart-pie"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                </ul>
                @endif

                <!-- USER MANAGEMENT -->
                @if ($canManageUsers)
                <ul class="nav-item">
                    <li>
                        <div class="nav-link {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*') || request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}" onclick="toggleSubmenu('userSubmenu', this)">
                            <i class="fas fa-users"></i>
                            <span>User Maintenance</span>
                            @php $pendingCount = \App\Models\User::where('approval_status', 'pending')->count(); @endphp
                            @if ($pendingCount > 0)
                                <span class="badge-count">{{ $pendingCount }}</span>
                            @endif
                            <i class="fas fa-chevron-right arrow" id="userArrow"></i>
                        </div>
                        <ul class="submenu" id="userSubmenu">
                            <li>
                                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                                    <i class="fas fa-list me-2"></i> User list
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.users.create') }}" class="{{ request()->routeIs('admin.users.create') ? 'active' : '' }}">
                                    <i class="fas fa-plus me-2"></i> Add User
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.users.pending') }}" class="{{ request()->routeIs('admin.users.pending') ? 'active' : '' }}">
                                    <i class="fas fa-clock me-2"></i> Pending Approvals
                                    @if ($pendingCount > 0)
                                        <span class="badge bg-warning text-dark ms-2">{{ $pendingCount }}</span>
                                    @endif
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.roles.index') }}" class="{{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                                    <i class="fas fa-shield-alt me-2"></i> Roles & Permissions
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.activity-logs.index') }}" class="{{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}">
                                    <i class="fas fa-history me-2"></i> Activity Logs
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
                @endif

                <!-- PRODUCT MAINTENANCE -->
                @if ($canManageProducts)
                <ul class="nav-item">
                    <li>
                        <div class="nav-link {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.categories.*') || request()->routeIs('admin.brands.*') ? 'active' : '' }}" onclick="toggleSubmenu('productSubmenu', this)">
                            <i class="fas fa-boxes"></i>
                            <span>Product Maintenance</span>
                            <i class="fas fa-chevron-right arrow" id="productArrow"></i>
                        </div>
                        <ul class="submenu" id="productSubmenu">
                            <li>
                                <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
                                    <i class="fas fa-list me-2"></i> Product list
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.products.create') }}" class="{{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
                                    <i class="fas fa-plus me-2"></i> Add Product
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                                    <i class="fas fa-tags me-2"></i> Categories
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.brands.index') }}" class="{{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                                    <i class="fas fa-trademark me-2"></i> Brands
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
                @endif

                <!-- SUPPLIER MAINTENANCE -->
                @if ($canManageSuppliers)
                <ul class="nav-item">
                    <li>
                        <div class="nav-link {{ request()->routeIs('admin.suppliers.*') || request()->routeIs('admin.purchase-history.*') || request()->routeIs('admin.supplier-returns.*') || request()->routeIs('admin.payment-terms.*') ? 'active' : '' }}" onclick="toggleSubmenu('supplierSubmenu', this)">
                            <i class="fas fa-truck"></i>
                            <span>Supplier Maintenance</span>
                            <i class="fas fa-chevron-right arrow" id="supplierArrow"></i>
                        </div>
                        <ul class="submenu" id="supplierSubmenu">
                            <li>
                                <a href="{{ route('admin.suppliers.index') }}" class="{{ request()->routeIs('admin.suppliers.index') ? 'active' : '' }}">
                                    <i class="fas fa-users me-2"></i> Supplier list
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.suppliers.create') }}" class="{{ request()->routeIs('admin.suppliers.create') ? 'active' : '' }}">
                                    <i class="fas fa-plus me-2"></i> Add Supplier
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.purchase-history.index') }}" class="{{ request()->routeIs('admin.purchase-history.*') ? 'active' : '' }}">
                                    <i class="fas fa-history me-2"></i> Purchase History
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.supplier-returns.index') }}" class="{{ request()->routeIs('admin.supplier-returns.*') ? 'active' : '' }}">
                                    <i class="fas fa-undo me-2"></i> Supplier Returns
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
                @endif

                <!-- INVENTORY -->
                @if ($canManageInventory)
                <ul class="nav-item">
                    <li>
                        <div class="nav-link {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}" onclick="toggleSubmenu('inventorySubmenu', this)">
                            <i class="fas fa-warehouse"></i>
                            <span>Inventory</span>
                            @php 
                                $lowStockInventory = \App\Models\Stock::whereRaw('quantity <= min_quantity')->count();
                                $expiredCount = \App\Models\Product::where('has_expiry', true)
                                                 ->where('expiry_date', '<', now())->count();
                                $nearExpiryCount = \App\Models\Product::where('has_expiry', true)
                                                  ->where('expiry_date', '>', now())
                                                  ->where('expiry_date', '<=', now()->addDays(30))->count();
                                $totalAlerts = $lowStockInventory + $expiredCount + $nearExpiryCount;
                            @endphp
                            @if ($totalAlerts > 0)
                                <span class="badge-count">{{ $totalAlerts }}</span>
                            @endif
                            <i class="fas fa-chevron-right arrow" id="inventoryArrow"></i>
                        </div>
                        <ul class="submenu" id="inventorySubmenu">
                            <li>
                                <a href="{{ route('admin.inventory.stock-in') }}" class="{{ request()->routeIs('admin.inventory.stock-in') ? 'active' : '' }}">
                                    <i class="fas fa-arrow-down me-2"></i> Stock In
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.inventory.stock-out') }}" class="{{ request()->routeIs('admin.inventory.stock-out') ? 'active' : '' }}">
                                    <i class="fas fa-arrow-up me-2"></i> Stock Out
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.inventory.alerts') }}" class="{{ request()->routeIs('admin.inventory.alerts') ? 'active' : '' }}">
                                    <i class="fas fa-exclamation-triangle me-2"></i> Low Stock Alerts
                                    @if ($totalAlerts > 0)
                                        <span class="badge bg-warning text-dark ms-2">{{ $totalAlerts }}</span>
                                    @endif
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.inventory.all-history') }}" class="{{ request()->routeIs('admin.inventory.all-history') || request()->routeIs('admin.inventory.history') ? 'active' : '' }}">
                                    <i class="fas fa-history me-2"></i> Stock History
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
                @endif

                <!-- SALES -->
                @if ($canManageSales)
                <ul class="nav-item">
                    <li>
                        <div class="nav-link {{ request()->routeIs('cashier.pos.*') || request()->routeIs('cashier.sales.*') || request()->routeIs('supervisor.transactions.*') || request()->routeIs('supervisor.returns.*') || request()->routeIs('supervisor.customers.*') ? 'active' : '' }}" onclick="toggleSubmenu('salesSubmenu', this)">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Sales</span>
                            <i class="fas fa-chevron-right arrow" id="salesArrow"></i>
                        </div>
                        <ul class="submenu" id="salesSubmenu">
                            <li>
                                <a href="{{ route('cashier.pos.index') }}" class="{{ request()->routeIs('cashier.pos.index') ? 'active' : '' }}">
                                    <i class="fas fa-shopping-cart me-2"></i> Point of Sale
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('supervisor.transactions.index') }}" class="{{ request()->routeIs('supervisor.transactions.*') ? 'active' : '' }}">
                                    <i class="fas fa-receipt me-2"></i> Transactions
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('supervisor.returns.index') }}" class="{{ request()->routeIs('supervisor.returns.*') ? 'active' : '' }}">
                                    <i class="fas fa-undo-alt me-2"></i> Returns & Refunds
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
                @endif

                <!-- REPORTS -->
                @if ($canViewReports)
                <ul class="nav-item">
                    <li>
                        <div class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" onclick="toggleSubmenu('reportsSubmenu', this)">
                            <i class="fas fa-chart-bar"></i>
                            <span>Reports</span>
                            <i class="fas fa-chevron-right arrow" id="reportsArrow"></i>
                        </div>
                        <ul class="submenu" id="reportsSubmenu">
                            <li>
                                <a href="{{ route('admin.reports.sales') }}" class="{{ request()->routeIs('admin.reports.sales') ? 'active' : '' }}">
                                    <i class="fas fa-chart-line me-2"></i> Sales Report
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.reports.inventory') }}" class="{{ request()->routeIs('admin.reports.inventory') ? 'active' : '' }}">
                                    <i class="fas fa-boxes me-2"></i> Inventory Report
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.reports.profit-loss') }}" class="{{ request()->routeIs('admin.reports.profit-loss') ? 'active' : '' }}">
                                    <i class="fas fa-coins me-2"></i> Profit & Loss
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
                @endif

                <!-- SYSTEM -->
                @if ($canManageSystem)
                <ul class="nav-item">
                    <li>
                        <div class="nav-link {{ request()->routeIs('admin.labels.*') || request()->routeIs('admin.settings.*') ? 'active' : '' }}" onclick="toggleSubmenu('systemSubmenu', this)">
                            <i class="fas fa-cog"></i>
                            <span>System</span>
                            <i class="fas fa-chevron-right arrow" id="systemArrow"></i>
                        </div>
                        <ul class="submenu" id="systemSubmenu">
                            <li>
                                <a href="{{ route('admin.labels.index') }}" class="{{ request()->routeIs('admin.labels.*') ? 'active' : '' }}">
                                    <i class="fas fa-tag me-2"></i> Product Labels
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                                    <i class="fas fa-cog me-2"></i> Settings
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
                @endif
                @endif
            </div>

            <!-- Logout Section -->
            <div class="sidebar-footer mt-auto">
                <div class="sidebar-divider mb-3"></div>
                <div class="logout-wrapper">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout-btn">
                            <div class="logout-icon-box">
                                <i class="fas fa-sign-out-alt"></i>
                            </div>
                            <div class="logout-text-wrapper">
                                <span class="logout-title">Sign Out</span>
                                <span class="logout-subtitle">End session</span>
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <!-- Top Navbar -->
            <nav class="navbar-top">
                <div class="navbar-left">
                    <button class="menu-toggle" id="menu-toggle">
                        <i class="fas fa-bars-staggered"></i>
                    </button>
                    <h1 class="page-title">@yield('title', 'Dashboard')</h1>
                </div>

                <div class="navbar-right">
                    <div class="user-details d-none d-md-flex flex-column text-end">
                        <span class="fw-semibold small text-primary-dark">{{ Auth::user()->full_name ?? 'Admin' }}</span>
                        <span class="text-muted" style="font-size: 0.7rem;">{{ Auth::user()->role ?? 'Administrator' }}</span>
                    </div>
                    <div class="dropdown">
                        <div class="user-dropdown" data-bs-toggle="dropdown">
                            <img src="{{ Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->full_name ?? 'Admin') . '&background=6366f1&color=fff&size=40' }}" alt="User">
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end p-2 border-0 shadow-lg" style="border-radius: 12px; min-width: 200px;">
                            <li class="d-md-none p-3 border-bottom mb-2">
                                <div class="fw-bold">{{ Auth::user()->full_name ?? 'Admin' }}</div>
                                <div class="small text-muted">{{ Auth::user()->role ?? 'Administrator' }}</div>
                            </li>
                            <li><a class="dropdown-item rounded-2" href="#" onclick="showProfile()"><i class="fas fa-user-circle me-2"></i> Profile</a></li>
                            <li><a class="dropdown-item rounded-2" href="{{ route('admin.settings.index') }}"><i class="fas fa-cog me-2"></i> Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item rounded-2 text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
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
        // Toggle sidebar logic
        function toggleSidebar() {
            if (window.innerWidth >= 992) {
                document.body.classList.toggle('toggled-sidebar');
            } else {
                document.body.classList.toggle('show-sidebar');
            }
        }

        document.getElementById('menu-toggle')?.addEventListener('click', function(e) {
            e.preventDefault();
            toggleSidebar();
        });

        // Close mobile sidebar when clicking on overlay
        document.querySelector('.sidebar-overlay')?.addEventListener('click', function() {
            document.body.classList.remove('show-sidebar');
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
            // User Management submenu
            @if (request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*') || request()->routeIs('admin.activity-logs.*'))
                const userSubmenu = document.getElementById('userSubmenu');
                const userArrow = document.querySelector('[onclick="toggleSubmenu(\'userSubmenu\', this)"] .arrow');
                if (userSubmenu) {
                    userSubmenu.classList.add('show');
                    if (userArrow) userArrow.classList.add('rotated');
                }
            @endif

            // Product Management submenu
            @if (request()->routeIs('admin.products.*') || request()->routeIs('admin.categories.*') || request()->routeIs('admin.brands.*') || request()->routeIs('admin.uom.*') || request()->routeIs('admin.variations.*'))
                const productSubmenu = document.getElementById('productSubmenu');
                const productArrow = document.querySelector('[onclick="toggleSubmenu(\'productSubmenu\', this)"] .arrow');
                if (productSubmenu) {
                    productSubmenu.classList.add('show');
                    if (productArrow) productArrow.classList.add('rotated');
                }
            @endif

            // Supplier submenu
            @if (request()->routeIs('admin.suppliers.*') || request()->routeIs('admin.purchase-history.*') || request()->routeIs('admin.supplier-returns.*') || request()->routeIs('admin.payment-terms.*'))
                const supplierSubmenu = document.getElementById('supplierSubmenu');
                const supplierArrow = document.querySelector('[onclick="toggleSubmenu(\'supplierSubmenu\', this)"] .arrow');
                if (supplierSubmenu) {
                    supplierSubmenu.classList.add('show');
                    if (supplierArrow) supplierArrow.classList.add('rotated');
                }
            @endif

            // Inventory submenu
            @if (request()->routeIs('admin.inventory.*'))
                const inventorySubmenu = document.getElementById('inventorySubmenu');
                const inventoryArrow = document.querySelector('[onclick="toggleSubmenu(\'inventorySubmenu\', this)"] .arrow');
                if (inventorySubmenu) {
                    inventorySubmenu.classList.add('show');
                    if (inventoryArrow) inventoryArrow.classList.add('rotated');
                }
            @endif

            // Sales submenu
            @if (request()->routeIs('cashier.pos.*') || request()->routeIs('cashier.sales.*') || request()->routeIs('supervisor.sales.*') || request()->routeIs('supervisor.transactions.*') || request()->routeIs('supervisor.returns.*') || request()->routeIs('supervisor.customers.*'))
                const salesSubmenu = document.getElementById('salesSubmenu');
                const salesArrow = document.querySelector('[onclick="toggleSubmenu(\'salesSubmenu\', this)"] .arrow');
                if (salesSubmenu) {
                    salesSubmenu.classList.add('show');
                    if (salesArrow) salesArrow.classList.add('rotated');
                }
            @endif

            // Supervisor cash submenu
            @if (request()->routeIs('supervisor.cash.*'))
                const cashSubmenu = document.getElementById('cashSubmenu');
                const cashArrow = document.querySelector('[onclick="toggleSubmenu(\'cashSubmenu\', this)"] .arrow');
                if (cashSubmenu) {
                    cashSubmenu.classList.add('show');
                    if (cashArrow) cashArrow.classList.add('rotated');
                }
            @endif

            // Reports submenu
            @if (request()->routeIs('admin.reports.*') || request()->routeIs('supervisor.reports.*'))
                const reportsSubmenu = document.getElementById('reportsSubmenu');
                const reportsArrow = document.querySelector('[onclick="toggleSubmenu(\'reportsSubmenu\', this)"] .arrow');
                if (reportsSubmenu) {
                    reportsSubmenu.classList.add('show');
                    if (reportsArrow) reportsArrow.classList.add('rotated');
                }
            @endif

            // System submenu
            @if (request()->routeIs('admin.import.*') || request()->routeIs('admin.labels.*') || request()->routeIs('admin.taxes.*') || request()->routeIs('admin.settings.*'))
                const systemSubmenu = document.getElementById('systemSubmenu');
                const systemArrow = document.querySelector('[onclick="toggleSubmenu(\'systemSubmenu\', this)"] .arrow');
                if (systemSubmenu) {
                    systemSubmenu.classList.add('show');
                    if (systemArrow) systemArrow.classList.add('rotated');
                }
            @endif
        });

        // Coming Soon notification
        function showComingSoon() {
            Swal.fire({
                title: 'Coming Soon!',
                text: 'This feature is under development.',
                icon: 'info',
                confirmButtonColor: '#FF8C42',
                confirmButtonText: 'Got it'
            });
        }

        // Profile modal
        function showProfile() {
            Swal.fire({
                title: 'User Profile',
                html: `
                    <div class="text-start">
                        <p><strong>Name:</strong> {{ Auth::user()->full_name ?? 'Admin' }}</p>
                        <p><strong>Email:</strong> {{ Auth::user()->email ?? 'admin@example.com' }}</p>
                        <p><strong>Role:</strong> {{ Auth::user()->role ?? 'Admin' }}</p>
                    </div>
                `,
                icon: 'info',
                confirmButtonColor: '#FF8C42',
                confirmButtonText: 'Close'
            });
        }

        // Improved Menu Search for Minimalist Sidebar
        document.getElementById('menuSearch')?.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const navItems = document.querySelectorAll('.nav-item');
            
            navItems.forEach(item => {
                const links = item.querySelectorAll('.nav-link, .submenu li a');
                let foundMatch = false;
                
                links.forEach(link => {
                    const text = (link.querySelector('span')?.textContent || link.textContent).toLowerCase();
                    if (text.includes(searchTerm)) {
                        foundMatch = true;
                        // Highlight match Color
                        link.style.color = 'var(--primary-blue)';
                    } else {
                        link.style.color = '';
                    }
                });

                item.style.display = foundMatch || searchTerm === '' ? '' : 'none';
                
                // Auto-expand submenus if match found
                if (foundMatch && searchTerm !== '') {
                    const submenu = item.querySelector('.submenu');
                    if (submenu) submenu.classList.add('show');
                }
            });
        });

        // Theme Toggle Persistent Logic
        const themeToggle = document.getElementById('themeToggle');
        if (themeToggle) {
            const themeIcon = themeToggle.querySelector('i');
            
            themeToggle.addEventListener('click', () => {
                const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
                const newTheme = isDark ? 'light' : 'dark';
                
                document.documentElement.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                
                themeIcon.className = newTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
            });
            
            // Set initial icon
            if (document.documentElement.getAttribute('data-theme') === 'dark') {
                themeIcon.className = 'fas fa-sun';
            }
        }
    </script>

    @stack('scripts')
</body>
</html>
