@extends('layouts.admin')

@section('title', 'Edit User - CJ\'s Minimart')

@section('content')
<div class="user-form-page">
    {{-- Page Header --}}
    <div class="page-header-premium">
        <div class="header-left">
            <div class="header-icon-box user-header-icon edit-mode">
                <i class="fas fa-user-edit text-warning"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Edit User</h1>
                <p class="page-subtitle">Editing: <span class="fw-semibold">{{ $user->full_name }}</span></p>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.users.show', $user->id) }}" class="btn-header-action btn-header-info" aria-label="View user">
                <i class="fas fa-eye"></i>
                <span class="d-none d-sm-inline">View</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn-header-action btn-header-secondary" aria-label="Back to users">
                <i class="fas fa-arrow-left"></i>
                <span class="d-none d-sm-inline">Back</span>
            </a>
        </div>
    </div>

    {{-- Form --}}
    <div class="form-container">
        <form method="POST" action="{{ route('admin.users.update', $user->id) }}" enctype="multipart/form-data" class="premium-form" id="userEditForm" novalidate>
            @csrf @method('PUT')

            <div class="form-grid">
                {{-- Left Column --}}
                <div class="form-column">
                    <div class="form-section">
                        <h5 class="section-title"><i class="fas fa-id-badge"></i> Account Details</h5>

                        <div class="form-group">
                            <label for="employee_id" class="premium-label">Employee ID <span class="text-danger">*</span></label>
                            <div class="input-with-icon">
                                <i class="fas fa-hashtag"></i>
                                <input type="text" id="employee_id" name="employee_id"
                                       class="premium-input @error('employee_id') is-invalid @enderror"
                                       value="{{ old('employee_id', $user->employee_id) }}" required>
                            </div>
                            @error('employee_id') <div class="premium-feedback">{{ $message }}</div> @enderror
                            <div class="validation-msg" id="employee_id_error"></div>
                        </div>

                        <div class="form-group">
                            <label for="username" class="premium-label">Username <span class="text-danger">*</span></label>
                            <div class="input-with-icon">
                                <i class="fas fa-at"></i>
                                <input type="text" id="username" name="username"
                                       class="premium-input @error('username') is-invalid @enderror"
                                       value="{{ old('username', $user->username) }}" required>
                            </div>
                            @error('username') <div class="premium-feedback">{{ $message }}</div> @enderror
                            <div class="validation-msg" id="username_error"></div>
                        </div>

                        <div class="form-group">
                            <label for="full_name" class="premium-label">Full Name <span class="text-danger">*</span></label>
                            <div class="input-with-icon">
                                <i class="fas fa-user"></i>
                                <input type="text" id="full_name" name="full_name"
                                       class="premium-input @error('full_name') is-invalid @enderror"
                                       value="{{ old('full_name', $user->full_name) }}" required>
                            </div>
                            @error('full_name') <div class="premium-feedback">{{ $message }}</div> @enderror
                            <div class="validation-msg" id="full_name_error"></div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="premium-label">Email Address <span class="text-danger">*</span></label>
                            <div class="input-with-icon">
                                <i class="fas fa-envelope"></i>
                                <input type="email" id="email" name="email"
                                       class="premium-input @error('email') is-invalid @enderror"
                                       value="{{ old('email', $user->email) }}" required>
                            </div>
                            @error('email') <div class="premium-feedback">{{ $message }}</div> @enderror
                            <div class="validation-msg" id="email_error"></div>
                        </div>
                    </div>
                </div>

                {{-- Right Column --}}
                <div class="form-column">
                    <div class="form-section">
                        <h5 class="section-title"><i class="fas fa-lock"></i> Security & Role</h5>

                        <div class="info-alert">
                            <i class="fas fa-info-circle"></i>
                            <span>Leave password fields empty to keep the current password.</span>
                        </div>

                        <div class="form-group">
                            <label for="password" class="premium-label">New Password <span class="text-muted">(Optional)</span></label>
                            <div class="input-with-icon">
                                <i class="fas fa-key"></i>
                                <input type="password" id="password" name="password"
                                       class="premium-input @error('password') is-invalid @enderror"
                                       placeholder="Min. 6 characters">
                            </div>
                            @error('password') <div class="premium-feedback">{{ $message }}</div> @enderror
                            <div class="validation-msg" id="password_error"></div>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="premium-label">Confirm Password</label>
                            <div class="input-with-icon">
                                <i class="fas fa-lock"></i>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="premium-input" placeholder="Re-enter password">
                            </div>
                            <div class="validation-msg" id="password_confirmation_error"></div>
                        </div>

                        <div class="form-group">
                            <label for="role" class="premium-label">Role <span class="text-danger">*</span></label>
                            <select id="role" name="role" class="premium-select @error('role') is-invalid @enderror" required>
                                <option value="">Select Role</option>
                                @foreach ($roles as $roleOption)
                                    <option value="{{ $roleOption->slug }}" {{ old('role', $user->role) == $roleOption->slug ? 'selected' : '' }}>
                                        {{ $roleOption->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role') <div class="premium-feedback">{{ $message }}</div> @enderror
                            <div class="validation-msg" id="role_error"></div>
                        </div>

                        <div class="form-group">
                            <label for="phone" class="premium-label">Phone Number</label>
                            <div class="input-with-icon">
                                <i class="fas fa-phone"></i>
                                <input type="text" id="phone" name="phone"
                                       class="premium-input @error('phone') is-invalid @enderror"
                                       value="{{ old('phone', $user->phone) }}" placeholder="+63 9XX XXX XXXX">
                            </div>
                            @error('phone') <div class="premium-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                {{-- Full Width --}}
                <div class="form-column span-full">
                    <div class="form-section">
                        <h5 class="section-title"><i class="fas fa-map-marker-alt"></i> Additional Info</h5>
                        <div class="form-group">
                            <label for="address" class="premium-label">Address</label>
                            <textarea id="address" name="address" class="premium-textarea @error('address') is-invalid @enderror"
                                      rows="3" placeholder="Full address...">{{ old('address', $user->address) }}</textarea>
                            @error('address') <div class="premium-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="form-footer">
                <div class="footer-left">
                    <span class="text-muted small">
                        <i class="fas fa-clock me-1"></i> Last updated: {{ $user->updated_at->diffForHumans() }}
                    </span>
                </div>
                <div class="footer-right">
                    <button type="button" class="btn-delete-user" onclick="confirmDeleteUser()">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn-cancel">Cancel</a>
                    <button type="submit" class="btn-submit btn-update" id="submitBtn">
                        <i class="fas fa-save"></i>
                        <span>Save Changes</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Hidden delete form --}}
<form id="deleteForm" method="POST" action="{{ route('admin.users.destroy', $user->id) }}" class="d-none">
    @csrf @method('DELETE')
</form>
@endsection

@push('styles')
<style>
.user-form-page { padding: 1.5rem; max-width: 1000px; margin: 0 auto; display: flex; flex-direction: column; gap: 1.5rem; }
.form-header { display: flex; align-items: center; justify-content: space-between; gap: 1.5rem; padding: 1.5rem 2rem; background: #fff; border-radius: 16px; border: 1px solid #e9ecef; box-shadow: 0 4px 15px rgba(0,0,0,0.03); flex-wrap: wrap; }
.form-header-left { display: flex; align-items: center; gap: 1.25rem; }
.form-header-icon { width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.75rem; color: #fff; box-shadow: 0 4px 12px rgba(102,126,234,0.3); }
.form-header-icon.edit-mode { background: linear-gradient(135deg, #f59e0b, #d97706); box-shadow: 0 4px 12px rgba(245,158,11,0.3); }
.form-title { font-size: 1.6rem; font-weight: 800; color: #1a1f2e; margin: 0 0 0.2rem; }
.form-subtitle { color: #64748b; margin: 0; font-size: 0.95rem; }
.btn-back { display: inline-flex; align-items: center; gap: 0.6rem; padding: 0.65rem 1.25rem; border: 1px solid #e2e8f0; color: #475569; border-radius: 10px; font-weight: 600; font-size: 0.9rem; text-decoration: none; transition: all 0.2s; background: #fff; }
.btn-back:hover { background: #f8fafc; color: #1e293b; border-color: #cbd5e1; transform: translateX(-3px); }
.form-container { background: #fff; border-radius: 20px; border: 1px solid #e9ecef; box-shadow: 0 10px 30px rgba(0,0,0,0.04); overflow: hidden; }
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; padding: 2.5rem; }
.span-full { grid-column: 1 / -1; }
.form-section { display: flex; flex-direction: column; gap: 1.5rem; margin-bottom: 0.5rem; }
.section-title { font-size: 1rem; font-weight: 700; color: #64748b; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.6rem; text-transform: uppercase; letter-spacing: 0.5px; }
.form-group { display: flex; flex-direction: column; gap: 0.4rem; }
.premium-label { font-size: 0.95rem; font-weight: 600; color: #334155; }
.premium-input, .premium-textarea, .premium-select { width: 100%; padding: 0.75rem 1rem; border: 1.5px solid #e2e8f0; border-radius: 12px; font-size: 1rem; background: #f8fafc; transition: all 0.2s ease; color: #1e293b; }
.premium-select { appearance: none; background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 16px 12px; padding-right: 2.5rem; }
.premium-input:focus, .premium-textarea:focus, .premium-select:focus { outline: none; border-color: #667eea; background: #fff; box-shadow: 0 0 0 4px rgba(102,126,234,0.1); }
.premium-input.is-invalid { border-color: #ef4444; background: #fef2f2; }
.premium-feedback { color: #ef4444; font-size: 0.85rem; font-weight: 500; }
.validation-msg { color: #ef4444; font-size: 0.82rem; font-weight: 500; min-height: 0; transition: all 0.2s; }
.validation-msg.show { min-height: 1.2rem; }
.input-with-icon { position: relative; }
.input-with-icon i { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.9rem; }
.input-with-icon .premium-input { padding-left: 2.75rem; }
.info-alert { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px; color: #1d4ed8; font-size: 0.88rem; font-weight: 500; }
.form-footer { display: flex; align-items: center; justify-content: space-between; padding: 1.5rem 2.5rem; background: #f8fafc; border-top: 1px solid #e9ecef; }
.footer-right { display: flex; gap: 1rem; align-items: center; }
.btn-cancel { padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 600; color: #64748b; text-decoration: none; transition: all 0.2s; }
.btn-cancel:hover { background: #e2e8f0; color: #1e293b; }
.btn-submit { padding: 0.75rem 2rem; background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; border: none; border-radius: 12px; font-weight: 700; display: flex; align-items: center; gap: 0.6rem; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 12px rgba(245,158,11,0.3); }
.btn-submit:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(245,158,11,0.4); filter: brightness(1.1); }
.btn-submit:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
.btn-delete-user { width: 42px; height: 42px; border-radius: 10px; border: 1.5px solid #fecaca; background: #fef2f2; color: #ef4444; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; font-size: 0.9rem; }
.btn-delete-user:hover { background: #ef4444; color: #fff; border-color: #ef4444; transform: translateY(-2px); }
@media (max-width: 768px) {
    .user-form-page { padding: 1rem; }
    .form-header { padding: 1.25rem; flex-direction: column; align-items: flex-start; }
    .btn-back { width: 100%; justify-content: center; }
    .form-grid { grid-template-columns: 1fr; padding: 1.5rem; gap: 1.5rem; }
    .form-footer { flex-direction: column; gap: 1.25rem; padding: 1.5rem; text-align: center; }
    .footer-right { width: 100%; flex-direction: column; }
    .btn-submit { order: -1; width: 100%; justify-content: center; }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('userEditForm');
    const fields = {
        employee_id: { required: true, minLength: 1, message: 'Employee ID is required.' },
        username:    { required: true, minLength: 3, message: 'Username must be at least 3 characters.' },
        full_name:   { required: true, minLength: 2, message: 'Full name is required.' },
        email:       { required: true, pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/, message: 'Enter a valid email address.' },
        role:        { required: true, message: 'Please select a role.' }
    };

    // Real-time validation
    Object.keys(fields).forEach(name => {
        const input = document.getElementById(name);
        if (!input) return;
        input.addEventListener('input', () => validateField(name));
        input.addEventListener('blur', () => validateField(name));
    });

    // Password match validation on edit (only if password is entered)
    const pw = document.getElementById('password');
    const confirmPw = document.getElementById('password_confirmation');
    if (pw && confirmPw) {
        pw.addEventListener('input', function() {
            if (this.value.length > 0 && this.value.length < 6) {
                showFieldError('password', 'Password must be at least 6 characters.');
            } else {
                clearFieldError('password');
            }
        });
        confirmPw.addEventListener('input', validatePasswordMatch);
        confirmPw.addEventListener('blur', validatePasswordMatch);
    }

    function validateField(name) {
        const input = document.getElementById(name);
        const errorEl = document.getElementById(name + '_error');
        const rule = fields[name];
        if (!input || !errorEl || !rule) return true;

        let valid = true, msg = '';
        if (rule.required && !input.value.trim()) { valid = false; msg = rule.message; }
        else if (rule.minLength && input.value.trim().length < rule.minLength) { valid = false; msg = rule.message; }
        else if (rule.pattern && !rule.pattern.test(input.value.trim())) { valid = false; msg = rule.message; }

        if (!valid) { input.classList.add('is-invalid'); errorEl.textContent = msg; errorEl.classList.add('show'); }
        else { input.classList.remove('is-invalid'); errorEl.textContent = ''; errorEl.classList.remove('show'); }
        return valid;
    }

    function showFieldError(name, msg) {
        const el = document.getElementById(name + '_error');
        const input = document.getElementById(name);
        if (el) { el.textContent = msg; el.classList.add('show'); }
        if (input) input.classList.add('is-invalid');
    }
    function clearFieldError(name) {
        const el = document.getElementById(name + '_error');
        const input = document.getElementById(name);
        if (el) { el.textContent = ''; el.classList.remove('show'); }
        if (input) input.classList.remove('is-invalid');
    }

    function validatePasswordMatch() {
        if (!pw.value) { clearFieldError('password_confirmation'); return true; }
        if (confirmPw.value && pw.value !== confirmPw.value) {
            confirmPw.classList.add('is-invalid');
            document.getElementById('password_confirmation_error').textContent = 'Passwords do not match.';
            return false;
        } else {
            confirmPw.classList.remove('is-invalid');
            document.getElementById('password_confirmation_error').textContent = '';
            return true;
        }
    }

    form.addEventListener('submit', function(e) {
        let allValid = true;
        Object.keys(fields).forEach(name => { if (!validateField(name)) allValid = false; });
        if (pw.value && !validatePasswordMatch()) allValid = false;
        if (pw.value && pw.value.length < 6) { showFieldError('password', 'Password must be at least 6 characters.'); allValid = false; }

        if (!allValid) {
            e.preventDefault();
            Swal.fire({ icon: 'error', title: 'Validation Error', text: 'Please fix the highlighted errors.', confirmButtonColor: '#667eea' });
            return;
        }
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Saving...</span>';
    });
});

function confirmDeleteUser() {
    Swal.fire({
        title: 'Delete User?',
        html: 'Are you sure you want to delete <strong>{{ addslashes($user->full_name) }}</strong>?<br><small class="text-muted">This action cannot be undone.</small>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-trash-alt me-1"></i> Yes, delete',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteForm').submit();
        }
    });
}
</script>
@endpush
