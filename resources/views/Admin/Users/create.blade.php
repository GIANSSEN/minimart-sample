@extends('layouts.admin')

@section('title', 'Add New User - CJ\'s Minimart')

@section('content')
<div class="user-form-page">
    {{-- Page Header --}}
    <div class="page-header-premium">
        <div class="header-left">
            <div class="header-icon-box users-header-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Add New User</h1>
                <p class="page-subtitle">Create a new system user account</p>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.users.index') }}" class="btn-header-action btn-header-secondary" title="Back to Users">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Users</span>
            </a>
        </div>
    </div>

    {{-- Form --}}
    <div class="form-container">
        <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data" class="premium-form" id="userForm" novalidate>
            @csrf

            <div class="form-grid-modern">
                {{-- Account Section --}}
                <div class="form-card-enhanced">
                    <div class="card-title-enhanced">
                        <i class="fas fa-id-card"></i>
                        <span>Account Information</span>
                    </div>
                    
                    <div class="form-group-modern">
                        <label for="employee_id" class="label-modern">Employee ID <span class="text-danger">*</span></label>
                        <div class="input-wrapper-modern">
                            <i class="fas fa-hashtag"></i>
                            <input type="text" id="employee_id" name="employee_id" 
                                   class="input-modern @error('employee_id') is-invalid @enderror"
                                   value="{{ old('employee_id') }}" placeholder="Ex: EMP-001" required>
                        </div>
                        @error('employee_id') <div class="feedback-modern">{{ $message }}</div> @enderror
                        <div class="js-validation-msg" id="employee_id_error"></div>
                    </div>

                    <div class="form-group-modern">
                        <label for="full_name" class="label-modern">Full Name <span class="text-danger">*</span></label>
                        <div class="input-wrapper-modern">
                            <i class="fas fa-user-tag"></i>
                            <input type="text" id="full_name" name="full_name" 
                                   class="input-modern @error('full_name') is-invalid @enderror"
                                   value="{{ old('full_name') }}" placeholder="Enter full name" required>
                        </div>
                        @error('full_name') <div class="feedback-modern">{{ $message }}</div> @enderror
                        <div class="js-validation-msg" id="full_name_error"></div>
                    </div>

                    <div class="form-group-modern">
                        <label for="username" class="label-modern">Username <span class="text-danger">*</span></label>
                        <div class="input-wrapper-modern">
                            <i class="fas fa-at"></i>
                            <input type="text" id="username" name="username" 
                                   class="input-modern @error('username') is-invalid @enderror"
                                   value="{{ old('username') }}" placeholder="Choose username" required>
                        </div>
                        @error('username') <div class="feedback-modern">{{ $message }}</div> @enderror
                        <div class="js-validation-msg" id="username_error"></div>
                    </div>

                    <div class="form-group-modern">
                        <label for="email" class="label-modern">Email Address <span class="text-danger">*</span></label>
                        <div class="input-wrapper-modern">
                            <i class="fas fa-envelope-open-text"></i>
                            <input type="email" id="email" name="email" 
                                   class="input-modern @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" placeholder="name@example.com" required>
                        </div>
                        @error('email') <div class="feedback-modern">{{ $message }}</div> @enderror
                        <div class="js-validation-msg" id="email_error"></div>
                    </div>
                </div>

                {{-- Access Section --}}
                <div class="form-card-enhanced">
                    <div class="card-title-enhanced">
                        <i class="fas fa-user-shield"></i>
                        <span>Security & Access</span>
                    </div>

                    <div class="form-group-modern">
                        <label for="role" class="label-modern">System Role <span class="text-danger">*</span></label>
                        <div class="input-wrapper-modern no-icon">
                            <select id="role" name="role" class="select-modern @error('role') is-invalid @enderror" required>
                                <option value="">-- Choose Account Level --</option>
                                @foreach ($roles as $roleOption)
                                    <option value="{{ $roleOption->slug }}" {{ old('role') == $roleOption->slug ? 'selected' : '' }}>
                                        {{ $roleOption->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('role') <div class="feedback-modern">{{ $message }}</div> @enderror
                        <div class="js-validation-msg" id="role_error"></div>
                    </div>

                    <div class="form-group-modern">
                        <label for="password" class="label-modern">Secure Password <span class="text-danger">*</span></label>
                        <div class="input-wrapper-modern">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="password" name="password" 
                                   class="input-modern @error('password') is-invalid @enderror"
                                   placeholder="Min. 8 characters" required>
                            <button type="button" class="pw-toggle" onclick="togglePassword('password')">
                                <i class="fas fa-eye-slash"></i>
                            </button>
                        </div>
                        @error('password') <div class="feedback-modern">{{ $message }}</div> @enderror
                        <div class="js-validation-msg" id="password_error"></div>
                        <div class="progress mt-2" style="height: 4px; display: none;" id="pwStrengthProgress">
                            <div class="progress-bar" role="progressbar" id="pwStrengthBar"></div>
                        </div>
                    </div>

                    <div class="form-group-modern">
                        <label for="password_confirmation" class="label-modern">Confirm Password <span class="text-danger">*</span></label>
                        <div class="input-wrapper-modern">
                            <i class="fas fa-check-double"></i>
                            <input type="password" id="password_confirmation" name="password_confirmation" 
                                   class="input-modern" placeholder="Verify password" required>
                        </div>
                        <div class="js-validation-msg" id="password_confirmation_error"></div>
                    </div>

                    <div class="form-group-modern">
                        <label for="phone" class="label-modern">Contact Number</label>
                        <div class="input-wrapper-modern">
                            <i class="fas fa-mobile-alt"></i>
                            <input type="text" id="phone" name="phone" 
                                   class="input-modern @error('phone') is-invalid @enderror"
                                   value="{{ old('phone') }}" placeholder="+63 9XX XXX XXXX">
                        </div>
                        @error('phone') <div class="feedback-modern">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Full width Address --}}
                <div class="form-card-enhanced span-2">
                    <div class="card-title-enhanced">
                        <i class="fas fa-map-marked-alt"></i>
                        <span>Contact Address</span>
                    </div>
                    <div class="form-group-modern mb-0">
                        <textarea id="address" name="address" class="textarea-modern @error('address') is-invalid @enderror"
                                  rows="2" placeholder="Full residential address (Optional)">{{ old('address') }}</textarea>
                        @error('address') <div class="feedback-modern">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            {{-- Action Bar --}}
            <div class="form-action-bar-modern">
                <div class="action-info">
                    <i class="fas fa-info-circle"></i>
                    <span>All fields marked with <span class="text-danger">*</span> are mandatory.</span>
                </div>
                <div class="action-btns">
                    <a href="{{ route('admin.users.index') }}" class="btn-cancel-modern">Cancel</a>
                    <button type="submit" class="btn-save-modern" id="submitBtn">
                        <i class="fas fa-plus-circle"></i>
                        <span>Register User Account</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Modern Minimalist User Form */
.user-form-page {
    padding: clamp(0.75rem, 2vw, 1.5rem);
    animation: fadeIn 0.4s ease-out;
}

@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

.form-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fff;
    padding: 1.25rem 1.5rem;
    border-radius: 16px;
    border: 1px solid #edf2f7;
    margin-bottom: 1.25rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.03);
}

.form-header-left { display: flex; align-items: center; gap: 1rem; }
.form-header-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.25rem;
}

.form-title { font-size: 1.3rem; font-weight: 800; color: #1e293b; margin: 0; }
.form-subtitle { font-size: 0.85rem; color: #64748b; margin: 0; }

.btn-back {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1.2rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    color: #475569;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s;
}
.btn-back:hover { background: #f1f5f9; transform: translateX(-4px); }

.form-container { width: 100%; }

.form-grid-modern {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.25rem;
    margin-bottom: 1.25rem;
}

.form-card-enhanced {
    background: #fff;
    border-radius: 20px;
    border: 1px solid #edf2f7;
    padding: 1.5rem;
    box-shadow: 0 10px 25px rgba(0,0,0,0.02);
}

.card-title-enhanced {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #f1f5f9;
}
.card-title-enhanced i { color: #3B82F6; font-size: 1.1rem; }
.card-title-enhanced span { font-weight: 700; color: #334155; font-size: 1rem; }

.form-group-modern { margin-bottom: 1.25rem; }
.label-modern { display: block; font-weight: 600; color: #475569; font-size: 0.85rem; margin-bottom: 0.5rem; }

.input-wrapper-modern {
    position: relative;
    display: flex;
    align-items: center;
}
.input-wrapper-modern i:first-child {
    position: absolute;
    left: 1rem;
    color: #94a3b8;
    font-size: 0.9rem;
}
.input-modern, .select-modern, .textarea-modern {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 2.8rem;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    color: #1e293b;
    font-size: 0.95rem;
    transition: all 0.2s ease;
}
.input-wrapper-modern.no-icon .select-modern { padding-left: 1rem; }
.textarea-modern { padding-left: 1rem; min-height: 80px; resize: none; }

.input-modern:focus, .select-modern:focus, .textarea-modern:focus {
    background: #fff;
    border-color: #3B82F6;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    outline: none;
}

.input-modern.is-invalid { border-color: #ef4444; background: #fff1f2; }

.feedback-modern, .js-validation-msg {
    color: #ef4444;
    font-size: 0.75rem;
    margin-top: 0.4rem;
    font-weight: 500;
}

.pw-toggle {
    position: absolute;
    right: 0.75rem;
    background: none;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    padding: 0.5rem;
    transition: color 0.2s;
}
.pw-toggle:hover { color: #3B82F6; }

.span-2 { grid-column: span 2; }

.form-action-bar-modern {
    background: #fff;
    padding: 1.25rem 1.5rem;
    border-radius: 16px;
    border: 1px solid #edf2f7;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 12px rgba(0,0,0,0.03);
}

.action-info { color: #64748b; font-size: 0.85rem; display: flex; align-items: center; gap: 0.5rem; }

.action-btns { display: flex; gap: 0.75rem; }

.btn-cancel-modern {
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    color: #64748b;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s;
}
.btn-cancel-modern:hover { background: #f1f5f9; color: #1e293b; }

.btn-save-modern {
    padding: 0.75rem 1.75rem;
    background: #3B82F6;
    color: #fff;
    border: none;
    border-radius: 12px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
}
.btn-save-modern:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(59, 130, 246, 0.3); background: #2563EB; }
.btn-save-modern:disabled { opacity: 0.7; transform: none; cursor: not-allowed; }

@media (max-width: 992px) {
    .form-grid-modern { grid-template-columns: 1fr; }
    .span-2 { grid-column: auto; }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    const btn = input.nextElementSibling;
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye-slash';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('userForm');
    const fields = ['employee_id', 'full_name', 'username', 'email', 'role', 'password'];
    
    // Real-time validation
    fields.forEach(fieldId => {
        const input = document.getElementById(fieldId);
        if (!input) return;
        input.addEventListener('input', () => validateField(fieldId));
    });

    const pwInput = document.getElementById('password');
    const pwBar = document.getElementById('pwStrengthBar');
    const pwProgress = document.getElementById('pwStrengthProgress');

    pwInput.addEventListener('input', function() {
        const val = this.value;
        if (val.length > 0) {
            pwProgress.style.display = 'flex';
            let strength = 0;
            if (val.length >= 8) strength += 40;
            if (/[A-Z]/.test(val)) strength += 20;
            if (/[0-9]/.test(val)) strength += 20;
            if (/[^A-Za-z0-9]/.test(val)) strength += 20;

            pwBar.style.width = strength + '%';
            if (strength <= 40) pwBar.className = 'progress-bar bg-danger';
            else if (strength <= 60) pwBar.className = 'progress-bar bg-warning';
            else pwBar.className = 'progress-bar bg-success';
        } else {
            pwProgress.style.display = 'none';
        }
    });

    function validateField(id) {
        const input = document.getElementById(id);
        const error = document.getElementById(id + '_error');
        let isValid = true;
        let msg = '';

        if (!input.value.trim()) {
            isValid = false;
            msg = 'This field is required';
        } else if (id === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value)) {
            isValid = false;
            msg = 'Please enter a valid email';
        } else if (id === 'password' && input.value.length < 8) {
            isValid = false;
            msg = 'Password must be at least 8 characters';
        } else if (id === 'username' && input.value.length < 4) {
            isValid = false;
            msg = 'Username must be at least 4 characters';
        }

        if (!isValid) {
            input.classList.add('is-invalid');
            if (error) error.innerText = msg;
        } else {
            input.classList.remove('is-invalid');
            if (error) error.innerText = '';
        }
        return isValid;
    }

    form.addEventListener('submit', function(e) {
        let formValid = true;
        fields.forEach(id => {
            if (!validateField(id)) formValid = false;
        });

        const pw = document.getElementById('password').value;
        const cpw = document.getElementById('password_confirmation').value;
        const cpwError = document.getElementById('password_confirmation_error');

        if (pw !== cpw) {
            formValid = false;
            document.getElementById('password_confirmation').classList.add('is-invalid');
            cpwError.innerText = 'Passwords do not match';
        }

        if (!formValid) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Check your input',
                text: 'Please clear all errors before submitting.',
                confirmButtonColor: '#3B82F6'
            });
        } else {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> CREATING ACCOUNT...';
        }
    });
});
</script>
@endpush
