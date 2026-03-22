<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CJ's Minimart - Welcome</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #3B82F6;
            --secondary: #F97316;
            --background: #F8FAFC;
            --text-main: #0F172A;
            --text-muted: #64748B;
            --white: #FFFFFF;
            --card-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--background);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            position: relative;
        }

        /* Subtle Background Elements */
        .bg-glow {
            position: fixed;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.05) 0%, transparent 70%);
            top: -200px;
            right: -100px;
            z-index: -1;
        }

        .bg-glow-bottom {
            position: fixed;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(249, 115, 22, 0.03) 0%, transparent 70%);
            bottom: -150px;
            left: -100px;
            z-index: -1;
        }

        .container {
            max-width: 500px;
            padding: 20px;
            text-align: center;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .welcome-card {
            background: var(--white);
            padding: 60px 40px;
            border-radius: 32px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255, 255, 255, 0.8);
        }

        .logo-container {
            margin-bottom: 40px;
        }

        .logo-img {
            max-width: 180px;
            height: auto;
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .logo-img:hover {
            transform: scale(1.05);
        }

        .brand-name {
            font-size: 2.25rem;
            font-weight: 800;
            letter-spacing: -0.025em;
            color: var(--text-main);
            margin-bottom: 8px;
        }

        .brand-tagline {
            font-size: 1rem;
            color: var(--text-muted);
            font-weight: 500;
            margin-bottom: 40px;
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .btn-custom {
            padding: 16px 32px;
            border-radius: 16px;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-primary-custom {
            background-color: var(--primary);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.2);
        }

        .btn-primary-custom:hover {
            background-color: #2563EB;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3);
            color: white;
        }

        .btn-outline-custom {
            background-color: transparent;
            color: var(--primary);
            border: 2px solid #E2E8F0;
        }

        .btn-outline-custom:hover {
            border-color: var(--primary);
            background-color: rgba(59, 130, 246, 0.05);
            transform: translateY(-2px);
        }

        .footer {
            margin-top: 40px;
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .footer-links {
            margin-bottom: 15px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .footer-links a {
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: var(--primary);
        }

        @media (max-width: 480px) {
            .welcome-card {
                padding: 40px 24px;
            }
            .brand-name {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="bg-glow"></div>
    <div class="bg-glow-bottom"></div>

    <div class="container">
        <div class="welcome-card">
            <div class="logo-container">
                <img src="{{ asset('images/logo-cjs.png') }}" alt="CJ's Minimart" class="logo-img">
                <h1 class="brand-name">Welcome</h1>
                <p class="brand-tagline">The complete POS & Inventory experience</p>
            </div>

            <div class="action-buttons">
                <a href="{{ route('login') }}" class="btn-custom btn-primary-custom">
                    <i class="fas fa-sign-in-alt"></i> Login to Dashboard
                </a>
                <a href="{{ route('register') }}" class="btn-custom btn-outline-custom">
                    <i class="fas fa-user-plus"></i> Create Account
                </a>
            </div>
        </div>

        <div class="footer">
            <div class="footer-links">
                <a href="#">About</a>
                <a href="#">Privacy</a>
                <a href="#">Contact</a>
            </div>
            <p>&copy; 2026 CJ's Minimart. Designed for speed.</p>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
