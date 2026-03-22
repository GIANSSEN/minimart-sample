<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - CJ's Minimart</title>
    
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
        
        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: var(--darker);
        }
        ::-webkit-scrollbar-thumb {
            background: var(--orange);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--orange-dark);
        }

        /* Main container */
        .register-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 620px;
            margin: 0 auto;
        }

        /* Prevent iOS Safari from zooming on focus (must be >=16px) */
        @media (max-width: 640px) {
            input, select, textarea {
                font-size: 16px !important;
            }
        }
        
        .register-card {
            background: var(--glass);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 24px;
            padding: 28px 30px;
            box-shadow: var(--shadow-premium);
            border: 1px solid var(--glass-border);
            width: 100%;
            position: relative;
            animation: fadeInScale 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes fadeInScale {
            0% { opacity: 0; transform: scale(0.9) translateY(20px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }
        
        .register-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--orange), var(--blue));
            border-radius: 32px 32px 0 0;
        }
        
        .brand {
            text-align: center;
            margin-bottom: 15px;
        }
        
        .brand-icon {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, var(--orange), var(--orange-dark));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            color: white;
            font-size: 22px;
            box-shadow: 0 10px 20px rgba(243, 156, 18, 0.3);
            transform: rotate(-8deg);
            transition: var(--transition);
        }
        
        .register-card:hover .brand-icon {
            transform: rotate(0deg) scale(1.05);
        }
        
        .brand h2 {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--darker);
            margin-bottom: 3px;
            letter-spacing: -0.025em;
        }
        
        .brand p {
            color: var(--gray);
            font-size: 0.82rem;
            font-weight: 500;
        }
        
        /* Info Notice */
        .info-notice {
            background: rgba(243, 156, 18, 0.08);
            border-left: 3px solid var(--orange);
            padding: 8px 12px;
            border-radius: 10px;
            margin-bottom: 16px;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }
        
        .info-notice i {
            color: var(--orange);
            font-size: 0.95rem;
            margin-top: 2px;
        }
        
        .info-notice p {
            margin: 0;
            font-size: 0.78rem;
            color: var(--dark);
            font-weight: 600;
            line-height: 1.4;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        
        .form-group {
            margin-bottom: 12px;
            position: relative;
        }
        
        .form-group.full-width {
            grid-column: span 2;
        }
        
        .form-label {
            display: block;
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--darker);
            margin-bottom: 4px;
            padding-left: 2px;
        }
        
        .input-group-custom {
            position: relative;
            transition: var(--transition);
        }
        
        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
            font-size: 0.85rem;
            transition: var(--transition);
            z-index: 5;
        }
        
        .form-control {
            width: 100%;
            padding: 9px 16px 9px 36px;
            background: rgba(241, 245, 249, 0.5);
            border: 2px solid transparent;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--darker);
            transition: var(--transition);
        }
        
        .form-control:focus {
            background: white;
            border-color: var(--orange);
            box-shadow: 0 0 0 5px rgba(243, 156, 18, 0.12);
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
            font-size: 0.72rem;
            font-weight: 600;
            margin-top: 3px;
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
        
        .btn-register {
            width: 100%;
            padding: 11px;
            background: linear-gradient(135deg, var(--orange), var(--orange-dark));
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 0.92rem;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 8px 20px -5px rgba(243, 156, 18, 0.4);
            margin: 8px 0 16px;
        }
        
        .btn-register:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 35px -8px rgba(243, 156, 18, 0.5);
            filter: brightness(1.05);
        }

        .btn-register.loading {
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
        
        .btn-register.loading .spinner {
            display: inline-block;
        }
        
        .btn-register.loading .btn-text {
            display: none;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .login-section {
            text-align: center;
            padding-top: 14px;
            border-top: 1px solid rgba(0,0,0,0.08);
        }
        
        .login-section p {
            color: var(--gray);
            font-size: 0.82rem;
            font-weight: 500;
            margin-bottom: 8px;
        }
        
        .login-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--blue);
            text-decoration: none;
            font-weight: 700;
            font-size: 0.85rem;
            padding: 7px 16px;
            background: rgba(52, 152, 219, 0.08);
            border-radius: 10px;
            border: 2px solid transparent;
            transition: var(--transition);
        }
        
        .login-link:hover {
            background: var(--blue);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(52, 152, 219, 0.2);
        }
        
        .back-home {
            text-align: center;
            margin-top: 10px;
        }
        
        .back-home a {
            color: var(--gray);
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 600;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 10px;
            border-radius: 8px;
        }
        
        .back-home a:hover {
            color: var(--darker);
            background: rgba(0,0,0,0.05);
        }
        /* Responsive adjustments */
        @media (max-width: 640px) {
            .register-container {
                max-width: 100%;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
            
            .form-group.full-width {
                grid-column: span 1;
            }
            
            .register-card {
                padding: 20px 16px;
                border-radius: 18px;
            }

            .brand img {
                max-width: 96px !important;
            }

            .brand h2 {
                font-size: 1.25rem;
            }

            .brand p {
                font-size: 0.8rem;
            }

            .btn-register {
                padding: 13px;
                font-size: 1rem;
                border-radius: 14px;
            }

            .login-link {
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
            .register-card {
                padding: 18px 14px;
            }

            .info-notice {
                padding: 8px 10px;
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

    <div class="register-container">
        <div class="register-card">
            <div class="brand animate__animated animate__fadeInDown">
                <div class="brand-logo-container">
                    <img src="{{ asset('images/logo-cjs.png') }}" alt="CJ's Minimart" style="max-width: 110px; height: auto; margin-bottom: 5px;">
                </div>
                <h2>Create Account</h2>
                <p>Join CJ's Minimart family today</p>
            </div>

            <!-- Info Notice about Approval -->
            <div class="info-notice animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                <i class="fas fa-shield-alt"></i>
                <p>Safety first! Your account will be reviewed by our team for approval after submission.</p>
            </div>

            <form id="registerForm" class="animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                @csrf
                
                <div class="form-row">
                    <!-- Username -->
                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <div class="input-group-custom">
                            <i class="fas fa-at input-icon"></i>
                            <input type="text" 
                                   class="form-control" 
                                   id="username" 
                                   name="username" 
                                   placeholder="Your username">
                        </div>
                        <div class="error-message" id="username-error"></div>
                    </div>

                    <!-- Full Name -->
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <div class="input-group-custom">
                            <i class="fas fa-id-card input-icon"></i>
                            <input type="text" 
                                   class="form-control" 
                                   id="full_name" 
                                   name="full_name" 
                                   placeholder="Your complete name">
                        </div>
                        <div class="error-message" id="full_name-error"></div>
                    </div>
                </div>

                <!-- Email -->
                <div class="form-group full-width">
                    <label class="form-label">Email Address</label>
                    <div class="input-group-custom">
                        <i class="fas fa-envelope-open input-icon"></i>
                        <input type="email" 
                               class="form-control" 
                               id="email" 
                               name="email" 
                               placeholder="your@email.com">
                    </div>
                    <div class="error-message" id="email-error"></div>
                </div>

                <!-- Address -->
                <div class="form-group full-width">
                    <label class="form-label">Complete Address</label>
                    <div class="input-group-custom">
                        <i class="fas fa-location-dot input-icon"></i>
                        <input type="text" 
                               class="form-control" 
                               id="address" 
                               name="address" 
                               placeholder="Street, City, Province, Zip Code">
                    </div>
                    <div class="error-message" id="address-error"></div>
                </div>

                <div class="form-row">
                    <!-- Password -->
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="input-group-custom">
                            <i class="fas fa-key input-icon"></i>
                            <input type="password" 
                                   class="form-control" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Create a strong password">
                            <span class="password-toggle" onclick="togglePassword('password', 'toggleIcon')">
                                <i class="far fa-eye" id="toggleIcon"></i>
                            </span>
                        </div>
                        <div class="error-message" id="password-error"></div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <div class="input-group-custom">
                            <i class="fas fa-check-double input-icon"></i>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   placeholder="Repeat your password">
                            <span class="password-toggle" onclick="togglePassword('password_confirmation', 'toggleIconConfirm')">
                                <i class="far fa-eye" id="toggleIconConfirm"></i>
                            </span>
                        </div>
                        <div class="error-message" id="password_confirmation-error"></div>
                    </div>
                </div>

                <!-- Phone (Optional) -->
                <div class="form-group full-width">
                    <label class="form-label">Phone Number (Optional)</label>
                    <div class="input-group-custom">
                        <i class="fas fa-phone-volume input-icon"></i>
                        <input type="text" 
                               class="form-control" 
                               id="phone" 
                               name="phone" 
                               placeholder="e.g. +63 9xx xxx xxxx">
                    </div>
                    <div class="error-message" id="phone-error"></div>
                </div>

                <button type="submit" class="btn-register" id="registerBtn">
                    <span class="spinner"></span>
                    <span class="btn-text">
                        Create My Account <i class="fas fa-chevron-right" style="margin-left: 5px;"></i>
                    </span>
                </button>
            </form>

            <div class="login-section animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
                <p>Already joined us?</p>
                <a href="{{ route('login') }}" class="login-link">
                    Sign in to your account
                </a>
            </div>

            <div class="back-home animate__animated animate__fadeInUp" style="animation-delay: 0.5s;">
                <a href="{{ route('home') }}">
                    <i class="fas fa-house"></i> Back to Homepage
                </a>
            </div>
        </div>
    </div>

    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        function togglePassword(inputId, iconId) {
            const password = document.getElementById(inputId);
            const toggleIcon = document.getElementById(iconId);
            
            if (password.type === 'password') {
                password.type = 'text';
                toggleIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                password.type = 'password';
                toggleIcon.classList.replace('fa-eye-slash', 'fa-eye');
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
                errorDiv.innerHTML = `<i class="fas fa-circle-exclamation"></i> ${message}`;
                errorDiv.classList.add('show');
            }
        }

        function clearAllErrors() {
            document.querySelectorAll('.form-control').forEach(field => field.classList.remove('error'));
            document.querySelectorAll('.error-message').forEach(div => div.classList.remove('show'));
        }

        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirmation').value;
            
            if (confirm && password !== confirm) {
                showFieldError('password_confirmation', 'Passwords do not match');
                return false;
            } else if (confirm) {
                clearFieldError('password_confirmation');
                return true;
            }
            return true;
        }

        // Real-time validation enhancements
        const inputs = ['username', 'full_name', 'email', 'address', 'password', 'password_confirmation', 'phone'];
        inputs.forEach(id => {
            const element = document.getElementById(id);
            if(element) {
                element.addEventListener('input', () => {
                    clearFieldError(id);
                    if(id === 'password' || id === 'password_confirmation') checkPasswordMatch();
                });
            }
        });

        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (!checkPasswordMatch()) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Check password confirmation'
                });
                return;
            }

            const btn = document.getElementById('registerBtn');
            btn.classList.add('loading');
            clearAllErrors();

            const formData = new FormData(this);

            try {
                const response = await fetch('{{ route("register") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Account Registered!',
                        html: `
                            <div style="text-align: center; padding: 10px;">
                                <div style="font-size: 3rem; color: #f39c12; margin-bottom: 15px;">⏳</div>
                                <h4 style="font-weight: 800; margin-bottom: 10px;">Awaiting Approval</h4>
                                <p style="font-size: 0.95rem; color: #64748b;">Your account has been created successfully. Please wait for an administrator to approve your access.</p>
                            </div>
                        `,
                        confirmButtonText: 'Great, I\'ll Wait!',
                        confirmButtonColor: '#f39c12',
                        customClass: {
                            popup: 'premium-swal-popup',
                            title: 'premium-swal-title'
                        }
                    });
                    
                    window.location.href = data.redirect;
                } else {
                    btn.classList.remove('loading');
                    
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            showFieldError(field, data.errors[field][0]);
                        });
                        
                        Toast.fire({
                            icon: 'error',
                            title: 'Validation failed'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Registration Failed',
                            text: data.message || 'Something went wrong during registration.',
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
                    text: 'Unable to process registration. Please check your connection.',
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
