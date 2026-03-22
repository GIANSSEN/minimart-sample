<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CJ's Minimart</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        :root {
            --orange: #f39c12;
            --orange-dark: #e67e22;
            --blue: #3498db;
            --blue-dark: #2980b9;
            --dark: #1a2634;
            --darker: #0f172a;
            --light: #f8fbff;
            --white: #ffffff;
            --gray: #94a3b8;
            --glass: rgba(255, 255, 255, 0.9);
            --glass-border: rgba(255, 255, 255, 0.4);
            --shadow-premium: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
            --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--darker);
            position: relative;
            overflow-x: hidden;
            padding: 15px;
            padding-left: max(15px, env(safe-area-inset-left));
            padding-right: max(15px, env(safe-area-inset-right));
            padding-top: max(15px, env(safe-area-inset-top));
            padding-bottom: max(15px, env(safe-area-inset-bottom));
        }
        
        /* Premium Background Elements */
        .background {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            overflow: hidden;
            z-index: 1;
        }
        
        .gradient-sphere {
            position: absolute;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.6;
        }
        
        .sphere-1 {
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, var(--orange) 0%, transparent 70%);
            top: -200px;
            right: -100px;
            animation: float1 25s infinite alternate ease-in-out;
        }
        
        .sphere-2 {
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, var(--blue) 0%, transparent 70%);
            bottom: -150px;
            left: -100px;
            animation: float2 30s infinite alternate ease-in-out;
        }
        
        @keyframes float1 {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(60px, 80px) scale(1.1); }
        }
        
        @keyframes float2 {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(-60px, -40px) rotate(15deg); }
        }
        
        .grid-overlay {
            position: absolute;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 2px 2px, rgba(255,255,255,0.05) 1px, transparent 0);
            background-size: 40px 40px;
        }
        
        /* Main container */
        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Prevent iOS Safari from zooming on focus (must be >=16px) */
        @media (max-width: 576px) {
            input, select, textarea {
                font-size: 16px !important;
            }
        }
        
        .login-card {
            background: var(--glass);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 24px;
            padding: 30px 28px;
            box-shadow: var(--shadow-premium);
            border: 1px solid var(--glass-border);
            width: 100%;
            position: relative;
            overflow: hidden;
            animation: fadeInScale 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes fadeInScale {
            0% { opacity: 0; transform: scale(0.9) translateY(20px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }
        
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--orange), var(--blue));
        }
        
        .brand {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .brand-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, var(--orange), var(--orange-dark));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            color: white;
            font-size: 24px;
            box-shadow: 0 10px 20px rgba(243, 156, 18, 0.3);
            transform: rotate(-8deg);
            transition: var(--transition);
        }
        
        .login-card:hover .brand-icon {
            transform: rotate(0deg) scale(1.05);
        }
        
        .brand h2 {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--darker);
            margin-bottom: 4px;
            letter-spacing: -0.025em;
        }
        
        .brand p {
            color: var(--gray);
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .form-group {
            margin-bottom: 14px;
            position: relative;
        }
        
        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--darker);
            margin-bottom: 5px;
            padding-left: 2px;
        }
        
        .input-group-custom {
            position: relative;
            transition: var(--transition);
        }
        
        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
            font-size: 0.9rem;
            transition: var(--transition);
            z-index: 5;
        }
        
        .form-control {
            width: 100%;
            padding: 10px 16px 10px 40px;
            background: rgba(241, 245, 249, 0.5);
            border: 2px solid transparent;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--darker);
            transition: var(--transition);
        }
        
        .form-control:focus {
            background: white;
            border-color: var(--orange);
            box-shadow: 0 0 0 5px rgba(243, 156, 18, 0.15);
            outline: none;
        }

        .form-control:focus + .input-icon {
            color: var(--orange);
            transform: translateY(-50%) scale(1.1);
        }
        
        .form-control.error {
            border-color: #ef4444;
            background-color: #fef2f2;
        }
        
        .error-message {
            color: #ef4444;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 4px;
            padding-left: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
            opacity: 0;
            transform: translateY(-5px);
            transition: var(--transition);
            pointer-events: none;
        }
        
        .error-message.show {
            opacity: 1;
            transform: translateY(0);
        }
        
        .password-toggle {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--gray);
            z-index: 10;
            padding: 5px;
            transition: var(--transition);
        }
        
        .password-toggle:hover {
            color: var(--darker);
        }
        
        .remember-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 0 0 16px;
        }
        
        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }
        
        .checkbox-wrapper input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--orange);
            cursor: pointer;
            border-radius: 4px;
        }
        
        .checkbox-wrapper label {
            color: var(--darker);
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            user-select: none;
        }
        
        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, var(--orange), var(--orange-dark));
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 8px 20px -5px rgba(243, 156, 18, 0.4);
            margin-bottom: 18px;
        }
        
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 35px -8px rgba(243, 156, 18, 0.5);
            filter: brightness(1.05);
        }

        .btn-login:active {
            transform: translateY(-1px);
        }
        
        .btn-login.loading {
            opacity: 0.8;
            cursor: not-allowed;
            pointer-events: none;
        }
        
        .spinner {
            display: none;
            width: 22px;
            height: 22px;
            border: 3px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.8s linear infinite;
        }
        
        .btn-login.loading .spinner {
            display: inline-block;
        }
        
        .btn-login.loading .btn-text {
            display: none;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .register-section {
            text-align: center;
            padding-top: 15px;
            border-top: 1px solid rgba(0,0,0,0.08);
        }
        
        .register-section p {
            color: var(--gray);
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 10px;
        }
        
        .register-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--blue);
            text-decoration: none;
            font-weight: 700;
            font-size: 0.88rem;
            padding: 8px 18px;
            background: rgba(52, 152, 219, 0.08);
            border-radius: 12px;
            border: 2px solid transparent;
            transition: var(--transition);
        }
        
        .register-link:hover {
            background: var(--blue);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(52, 152, 219, 0.2);
        }
        
        .back-home {
            text-align: center;
            margin-top: 12px;
        }
        
        .back-home a {
            color: var(--gray);
            text-decoration: none;
            font-size: 0.82rem;
            font-weight: 600;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 12px;
            border-radius: 8px;
        }
        
        .back-home a:hover {
            color: var(--darker);
            background: rgba(0,0,0,0.05);
        }
        
        /* Responsive adjustments */
        @media (max-width: 576px) {
            .login-wrapper {
                max-width: 100%;
            }

            .login-card {
                padding: 22px 18px;
                border-radius: 18px;
            }

            .brand img {
                max-width: 96px !important;
            }

            .brand h2 {
                font-size: 1.35rem;
            }

            .brand p {
                font-size: 0.82rem;
            }

            .remember-section {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .btn-login {
                padding: 13px;
                font-size: 1rem;
                border-radius: 14px;
            }

            .register-link {
                width: 100%;
                justify-content: center;
            }

            .back-home a {
                width: 100%;
                justify-content: center;
            }
        }

        /* Ultra small devices */
        @media (max-width: 360px) {
            .login-card {
                padding: 18px 14px;
            }
        }
        
        /* Custom SweetAlert Styles */
        .premium-swal-popup {
            border-radius: 24px !important;
            padding: 2rem !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
        }
        
        .premium-swal-title {
            font-weight: 800 !important;
            color: var(--darker) !important;
        }
    </style>
</head>
<body>
    <div class="background">
        <div class="gradient-sphere sphere-1"></div>
        <div class="gradient-sphere sphere-2"></div>
        <div class="grid-overlay"></div>
    </div>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="brand animate__animated animate__fadeInDown">
                <div class="brand-logo-container">
                    <img src="{{ asset('images/logo-cjs.png') }}" alt="CJ's Minimart" style="max-width: 120px; height: auto; margin-bottom: 5px;">
                </div>
                <h2>Hey there!</h2>
                <p>Sign in to continue to Dashboard</p>
            </div>

            <form id="loginForm" class="animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">Username or Email</label>
                    <div class="input-group-custom">
                        <i class="far fa-user input-icon"></i>
                        <input type="text" 
                               class="form-control" 
                               id="username" 
                               name="username" 
                               placeholder="e.g. john_doe"
                               autocomplete="username">
                    </div>
                    <div class="error-message" id="username-error"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-group-custom">
                        <i class="fas fa-lock-open input-icon"></i>
                        <input type="password" 
                               class="form-control" 
                               id="password" 
                               name="password" 
                               placeholder="••••••••"
                               autocomplete="current-password">
                        <span class="password-toggle" onclick="togglePassword()">
                            <i class="far fa-eye" id="toggleIcon"></i>
                        </span>
                    </div>
                    <div class="error-message" id="password-error"></div>
                </div>

                <div class="remember-section">
                    <div class="checkbox-wrapper" onclick="document.getElementById('remember').click()">
                        <input type="checkbox" name="remember" id="remember" onclick="event.stopPropagation()">
                        <label for="remember">Keep me logged in</label>
                    </div>
                </div>

                <button type="submit" class="btn-login" id="loginBtn">
                    <span class="spinner"></span>
                    <span class="btn-text">
                        Sign In <i class="fas fa-arrow-right" style="margin-left: 5px;"></i>
                    </span>
                </button>
            </form>

            <div class="register-section animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
                <p>New here?</p>
                <a href="{{ route('register') }}" class="register-link">
                    Create an account
                </a>
            </div>

            <div class="back-home animate__animated animate__fadeInUp" style="animation-delay: 0.5s;">
                <a href="{{ route('home') }}">
                    <i class="fas fa-chevron-left"></i> Return to website
                </a>
            </div>
        </div>
    </div>

    <script>
        // Custom Swal configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        function togglePassword() {
            const password = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            const lockIcon = document.querySelector('.input-group-custom .fa-lock-open, .input-group-custom .fa-lock');
            
            if (password.type === 'password') {
                password.type = 'text';
                toggleIcon.classList.replace('fa-eye', 'fa-eye-slash');
                if(lockIcon) lockIcon.classList.replace('fa-lock-open', 'fa-lock');
            } else {
                password.type = 'password';
                toggleIcon.classList.replace('fa-eye-slash', 'fa-eye');
                if(lockIcon) lockIcon.classList.replace('fa-lock', 'fa-lock-open');
            }
        }

        function clearFieldError(fieldId) {
            const field = document.getElementById(fieldId);
            const errorDiv = document.getElementById(`${fieldId}-error`);
            if (field && errorDiv) {
                field.classList.remove('error');
                errorDiv.classList.remove('show');
                setTimeout(() => {
                    if(!errorDiv.classList.contains('show')) errorDiv.innerHTML = '';
                }, 400);
            }
        }

        function showFieldError(fieldId, message) {
            const field = document.getElementById(fieldId);
            const errorDiv = document.getElementById(`${fieldId}-error`);
            if (field && errorDiv) {
                field.classList.add('error');
                errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
                errorDiv.classList.add('show');
            }
        }

        function clearAllErrors() {
            document.querySelectorAll('.form-control').forEach(field => field.classList.remove('error'));
            document.querySelectorAll('.error-message').forEach(div => div.classList.remove('show'));
        }

        document.getElementById('username').addEventListener('input', () => clearFieldError('username'));
        document.getElementById('password').addEventListener('input', () => clearFieldError('password'));

        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const btn = document.getElementById('loginBtn');
            const btnText = btn.querySelector('.btn-text');
            
            btn.classList.add('loading');
            clearAllErrors();

            const formData = new FormData(this);

            try {
                const response = await fetch('{{ route("login") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Signed in successfully'
                    });
                    
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1000);
                } else {
                    btn.classList.remove('loading');
                    
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            showFieldError(field, data.errors[field][0]);
                        });
                        
                        Toast.fire({
                            icon: 'error',
                            title: 'Fullfill all fields correctly'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Authentification Failed',
                            text: data.message || 'The credentials provided do not match our records.',
                            confirmButtonColor: '#f39c12',
                            customClass: {
                                popup: 'premium-swal-popup',
                                title: 'premium-swal-title'
                            }
                        });
                    }
                }
            } catch (error) {
                btn.classList.remove('loading');
                Swal.fire({
                    icon: 'error',
                    title: 'System Error',
                    text: 'Unable to connect to the server. Please try again later.',
                    confirmButtonColor: '#f39c12',
                    customClass: {
                        popup: 'premium-swal-popup',
                        title: 'premium-swal-title'
                    }
                });
            }
        });
    </script>
</body>
</html>
