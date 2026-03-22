@extends('Layouts.admin')

@section('title', 'System Settings')

@push('styles')
<style>
    :root {
        --settings-primary: #3b82f6;
        --settings-primary-soft: rgba(59, 130, 246, 0.1);
        --settings-bg-card: #ffffff;
        --settings-border: #e2e8f0;
        --settings-text-muted: #64748b;
        --settings-sidebar-bg: #f8fafc;
    }
    [data-theme="dark"] {
        --settings-bg-card: #1e293b;
        --settings-border: #334155;
        --settings-text-muted: #94a3b8;
        --settings-sidebar-bg: #0f172a;
    }

    .settings-container { display: flex; gap: 1.5rem; min-height: 70vh; }
    
    /* Sidebar Styling */
    .settings-sidebar {
        width: 280px; flex-shrink: 0;
        background: var(--settings-sidebar-bg);
        border: 1px solid var(--settings-border);
        border-radius: 20px; padding: 1rem;
        height: fit-content; position: sticky; top: 1.5rem;
    }
    .s-nav-item {
        display: flex; align-items: center; gap: 12px;
        padding: 0.85rem 1.25rem; border-radius: 12px;
        color: var(--settings-text-muted); font-weight: 600; font-size: 0.92rem;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer; border: none; background: transparent; width: 100%; text-align: left;
        margin-bottom: 4px;
    }
    .s-nav-item:hover { background: var(--settings-primary-soft); color: var(--settings-primary); }
    .s-nav-item.active { background: var(--settings-primary); color: #fff; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); }
    .s-nav-item i { width: 20px; font-size: 1rem; }

    /* Content Area */
    .settings-main { flex-grow: 1; min-width: 0; }
    .settings-panel { display: none; animation: fadeInSlide 0.4s ease-out; }
    .settings-panel.active { display: block; }

    @keyframes fadeInSlide {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .card-settings {
        background: var(--settings-bg-card);
        border: 1px solid var(--settings-border);
        border-radius: 20px; padding: 1.75rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03); margin-bottom: 1.5rem;
    }
    .card-settings-title {
        font-size: 1.15rem; font-weight: 800; color: var(--text-color);
        margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px;
    }

    /* Logo Upload Styles */
    .logo-preview-box {
        width: 140px; height: 140px; border-radius: 18px;
        border: 2px dashed var(--settings-border);
        background: var(--settings-sidebar-bg);
        display: flex; align-items: center; justify-content: center;
        overflow: hidden; cursor: pointer; position: relative;
        transition: border-color 0.2s;
    }
    .logo-preview-box:hover { border-color: var(--settings-primary); }
    .logo-preview-box img { width: 100%; height: 100%; object-fit: contain; }
    .logo-overlay {
        position: absolute; inset: 0; background: rgba(0,0,0,0.4);
        display: flex; align-items: center; justify-content: center;
        color: #fff; opacity: 0; transition: 0.2s;
    }
    .logo-preview-box:hover .logo-overlay { opacity: 1; }

    /* Input Styling */
    .form-settings-label {
        font-size: 0.8rem; font-weight: 700; color: var(--settings-text-muted);
        text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem; display: block;
    }
    .form-control-settings {
        border: 1px solid var(--settings-border); border-radius: 12px;
        padding: 0.75rem 1rem; font-size: 0.95rem; transition: 0.2s;
        background: var(--settings-bg-card); color: var(--text-color);
    }
    .form-control-settings:focus { border-color: var(--settings-primary); box-shadow: 0 0 0 4px var(--settings-primary-soft); outline: none; }

    /* Toggle Switches */
    .switch-modern {
        display: flex; align-items: center; gap: 12px; cursor: pointer;
    }
    .switch-modern input { display: none; }
    .switch-slider {
        width: 48px; height: 26px; background: #cbd5e1; border-radius: 100px;
        position: relative; transition: 0.3s;
    }
    .switch-slider::after {
        content: ''; position: absolute; width: 20px; height: 20px;
        top: 3px; left: 3px; background: #fff; border-radius: 50%;
        transition: 0.3s; box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .switch-modern input:checked + .switch-slider { background: var(--settings-primary); }
    .switch-modern input:checked + .switch-slider::after { transform: translateX(22px); }

    /* Receipt Preview */
    .receipt-container {
        background: #f1f5f9; border-radius: 20px; padding: 1.5rem;
        display: flex; justify-content: center;
    }
    .receipt-paper {
        width: 280px; background: #fff; border-radius: 2px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        padding: 1.5rem 1rem; font-family: 'Courier New', Courier, monospace;
        color: #333; position: relative;
    }
    .receipt-paper::after {
        content: ''; position: absolute; bottom: -10px; left: 0; width: 100%; height: 10px;
        background: radial-gradient(circle, #fff 40%, transparent 41%);
        background-size: 10px 20px; background-position: 0 -10px;
    }

    /* Save Bar */
    .settings-footer {
        position: sticky; bottom: 0; z-index: 50;
        background: rgba(var(--settings-bg-card-rgb), 0.8);
        backdrop-filter: blur(14px);
        border-top: 1px solid var(--settings-border);
        padding: 1rem 1.5rem; margin-top: 2rem;
        display: flex; justify-content: flex-end; gap: 1rem;
    }

    @media (max-width: 991px) {
        .settings-container { flex-direction: column; }
        .settings-sidebar { width: 100%; position: static; display: flex; overflow-x: auto; padding: 0.5rem; }
        .s-nav-item { margin-bottom: 0; white-space: nowrap; }
    }
</style>
@endpush

@section('content')
<div class="mb-4">
    <h1 class="h3 font-weight-bold text-dark mb-1">System Configuration</h1>
    <p class="text-muted small">Customize your store preferences, localization, and system maintenance.</p>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-15 mb-4" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" id="settingsForm">
    @csrf
    <input type="hidden" name="active_tab" id="activeTabInput" value="{{ session('active_tab', 'general') }}">

    <div class="settings-container">
        <!-- Sidebar Navigation -->
        <div class="settings-sidebar shadow-sm">
            <button type="button" class="s-nav-item active" onclick="switchTab('general')" data-tab="general">
                <i class="fas fa-store"></i> Store General
            </button>
            <button type="button" class="s-nav-item" onclick="switchTab('localization')" data-tab="localization">
                <i class="fas fa-globe-asia"></i> Localization
            </button>
            <button type="button" class="s-nav-item" onclick="switchTab('currency')" data-tab="currency">
                <i class="fas fa-wallet"></i> Currency & Tax
            </button>
            <button type="button" class="s-nav-item" onclick="switchTab('receipt')" data-tab="receipt">
                <i class="fas fa-file-invoice"></i> Receipt Layout
            </button>
            <button type="button" class="s-nav-item" onclick="switchTab('inventory')" data-tab="inventory">
                <i class="fas fa-boxes"></i> Inventory Rules
            </button>
            <button type="button" class="s-nav-item" onclick="switchTab('system')" data-tab="system">
                <i class="fas fa-microchip"></i> System Metrics
            </button>
            <button type="button" class="s-nav-item text-danger" onclick="switchTab('maintenance')" data-tab="maintenance">
                <i class="fas fa-tools"></i> Maintenance
            </button>
        </div>

        <!-- Main Content area -->
        <div class="settings-main">
            
            <!-- PANEL: GENERAL -->
            <div class="settings-panel active" id="panel-general">
                <div class="card-settings">
                    <div class="card-settings-title"><i class="fas fa-store text-primary"></i> Store Identity</div>
                    <div class="row align-items-center mb-4">
                        <div class="col-auto">
                            <label class="form-settings-label">Store Logo</label>
                            <div class="logo-preview-box" onclick="document.getElementById('logoInput').click()">
                                <img id="logoPreview" src="{{ $settings['store_logo'] ? asset('uploads/settings/' . $settings['store_logo']) : 'https://placehold.co/200x200?text=LOGO' }}" alt="Logo">
                                <div class="logo-overlay"><i class="fas fa-camera fa-2x"></i></div>
                            </div>
                            <input type="file" name="store_logo" id="logoInput" hidden accept="image/*">
                        </div>
                        <div class="col">
                            <h5 class="fw-bold mb-1">Company Branding</h5>
                            <p class="text-muted small mb-0">Upload your store logo. Recommended size 512x512px (PNG/JPG).</p>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-settings-label">Store Name</label>
                            <input type="text" name="store_name" class="form-control form-control-settings" value="{{ $settings['store_name'] ?? '' }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-settings-label">Business Email</label>
                            <input type="email" name="store_email" class="form-control form-control-settings" value="{{ $settings['store_email'] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-settings-label">Contact Phone</label>
                            <input type="text" name="store_phone" class="form-control form-control-settings" value="{{ $settings['store_phone'] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-settings-label">Official Website</label>
                            <input type="url" name="store_website" class="form-control form-control-settings" value="{{ $settings['store_website'] ?? '' }}">
                        </div>
                        <div class="col-12">
                            <label class="form-settings-label">Store Address</label>
                            <textarea name="store_address" class="form-control form-control-settings" rows="3">{{ $settings['store_address'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PANEL: LOCALIZATION -->
            <div class="settings-panel" id="panel-localization">
                <div class="card-settings">
                    <div class="card-settings-title"><i class="fas fa-globe-asia text-info"></i> Regional Settings</div>
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="form-settings-label">Timezone</label>
                            <select name="timezone" class="form-select form-control-settings">
                                @foreach ($timezones as $tz)
                                    <option value="{{ $tz }}" {{ ($settings['timezone'] ?? '') == $tz ? 'selected' : '' }}>{{ $tz }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-settings-label">Date Format</label>
                            <select name="date_format" class="form-select form-control-settings">
                                @foreach ($dateFormats as $key => $val)
                                    <option value="{{ $key }}" {{ ($settings['date_format'] ?? '') == $key ? 'selected' : '' }}>{{ $val }} ({{ $key }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-settings-label">Time Format</label>
                            <select name="time_format" class="form-select form-control-settings">
                                @foreach ($timeFormats as $key => $val)
                                    <option value="{{ $key }}" {{ ($settings['time_format'] ?? '') == $key ? 'selected' : '' }}>{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PANEL: CURRENCY -->
            <div class="settings-panel" id="panel-currency">
                <div class="card-settings">
                    <div class="card-settings-title"><i class="fas fa-wallet text-success"></i> Financial Setup</div>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-settings-label">Currency Symbol</label>
                            <input type="text" name="currency_symbol" class="form-control form-control-settings" value="{{ $settings['currency_symbol'] ?? '₱' }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-settings-label">Currency Code</label>
                            <input type="text" name="currency_code" class="form-control form-control-settings" value="{{ $settings['currency_code'] ?? 'PHP' }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-settings-label">Decimal Places</label>
                            <select name="decimal_places" class="form-select form-control-settings">
                                <option value="0" {{ ($settings['decimal_places'] ?? '') == '0' ? 'selected' : '' }}>0</option>
                                <option value="1" {{ ($settings['decimal_places'] ?? '') == '1' ? 'selected' : '' }}>1</option>
                                <option value="2" {{ ($settings['decimal_places'] ?? '') == '2' ? 'selected' : '' }}>2</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-settings-label">Tax Calculation Mode</label>
                            <select name="tax_mode" class="form-select form-control-settings">
                                <option value="exclusive" {{ ($settings['tax_mode'] ?? '') == 'exclusive' ? 'selected' : '' }}>Exclusive (Tax added at checkout)</option>
                                <option value="inclusive" {{ ($settings['tax_mode'] ?? '') == 'inclusive' ? 'selected' : '' }}>Inclusive (Tax already in price)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-settings-label">Default Tax Rate (%)</label>
                            <div class="input-group">
                                <input type="number" step="0.01" name="default_tax_rate" class="form-control form-control-settings" value="{{ $settings['default_tax_rate'] ?? '12' }}">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-percent"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PANEL: RECEIPT -->
            <div class="settings-panel" id="panel-receipt">
                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="card-settings">
                            <div class="card-settings-title"><i class="fas fa-receipt text-warning"></i> Receipt Content</div>
                            <div class="mb-4">
                                <label class="form-settings-label">Receipt Header Message</label>
                                <input type="text" name="receipt_header" class="form-control form-control-settings" value="{{ $settings['receipt_header'] ?? '' }}" id="receiptHeaderInput">
                            </div>
                            <div class="mb-4">
                                <label class="form-settings-label">Receipt Footer Message</label>
                                <input type="text" name="receipt_footer" class="form-control form-control-settings" value="{{ $settings['receipt_footer'] ?? '' }}" id="receiptFooterInput">
                            </div>
                            <div class="mb-3">
                                <label class="switch-modern">
                                    <input type="checkbox" name="receipt_show_tax" value="1" {{ ($settings['receipt_show_tax'] ?? '1') == '1' ? 'checked' : '' }} id="showTaxToggle">
                                    <span class="switch-slider"></span>
                                    <span class="fw-bold small text-muted">Show Tax Breakdown</span>
                                </label>
                            </div>
                            <div class="mb-0">
                                <label class="switch-modern">
                                    <input type="checkbox" name="receipt_show_barcode" value="1" {{ ($settings['receipt_show_barcode'] ?? '0') == '1' ? 'checked' : '' }}>
                                    <span class="switch-slider"></span>
                                    <span class="fw-bold small text-muted">Include Receipt Barcode</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="receipt-container shadow-sm">
                            <div class="receipt-paper">
                                <div class="text-center fw-bold mb-1" id="previewHeader">{{ $settings['receipt_header'] ?? 'CJ Minimart' }}</div>
                                <div class="text-center small mb-3">123 Street, City Name</div>
                                <div style="border-top: 1px dashed #ccc;" class="my-2"></div>
                                <div class="d-flex justify-content-between small"><span>Item One</span> <span>150.00</span></div>
                                <div class="d-flex justify-content-between small"><span>Item Two</span> <span>250.00</span></div>
                                <div style="border-top: 1px dashed #ccc;" class="my-2"></div>
                                <div class="d-flex justify-content-between fw-bold mb-1"><span>Subtotal</span> <span>400.00</span></div>
                                <div class="d-flex justify-content-between small" id="taxRow" style="{{ ($settings['receipt_show_tax'] ?? '1') == '1' ? '' : 'display:none' }}"><span>VAT (12%)</span> <span>48.00</span></div>
                                <div class="d-flex justify-content-between h6 fw-bold mt-1"><span>TOTAL</span> <span>448.00</span></div>
                                <div style="border-top: 1px dashed #ccc;" class="my-2"></div>
                                <div class="text-center small mt-3" id="previewFooter">{{ $settings['receipt_footer'] ?? 'Thank you!' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PANEL: INVENTORY -->
            <div class="settings-panel" id="panel-inventory">
                <div class="card-settings">
                    <div class="card-settings-title"><i class="fas fa-boxes text-danger"></i> Stock Alerts</div>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-settings-label">Low Stock Warning Level</label>
                            <div class="input-group">
                                <input type="number" name="low_stock_threshold" class="form-control form-control-settings" value="{{ $settings['low_stock_threshold'] ?? 10 }}">
                                <span class="input-group-text bg-light border-0 text-muted small">Units</span>
                            </div>
                            <p class="text-muted small mt-2">Get notified when stock levels fall below this number.</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-settings-label">Near-Expiry Warning</label>
                            <div class="input-group">
                                <input type="number" name="near_expiry_days" class="form-control form-control-settings" value="{{ $settings['near_expiry_days'] ?? 30 }}">
                                <span class="input-group-text bg-light border-0 text-muted small">Days Out</span>
                            </div>
                            <p class="text-muted small mt-2">Alert system will flag items expiring within this timeframe.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PANEL: SYSTEM -->
            <div class="settings-panel" id="panel-system">
                <div class="card-settings">
                    <div class="card-settings-title"><i class="fas fa-microchip text-primary"></i> Environment Snapshot</div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="p-3 border rounded-15 bg-light text-center">
                                <div class="text-muted small fw-bold">LARAVEL</div>
                                <div class="h5 fw-bold mb-0">{{ $systemInfo['laravel_version'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 border rounded-15 bg-light text-center">
                                <div class="text-muted small fw-bold">PHP ENGINE</div>
                                <div class="h5 fw-bold mb-0">{{ $systemInfo['php_version'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 border rounded-15 bg-light text-center">
                                <div class="text-muted small fw-bold">DATABASE</div>
                                <div class="h5 fw-bold mb-0">{{ strtoupper($systemInfo['db_driver']) }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 border rounded-15 bg-light">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small fw-bold">DISK USAGE</span>
                                    <span class="fw-bold">{{ $systemInfo['disk_used_pct'] ?? '0' }}%</span>
                                </div>
                                <div class="progress" style="height: 8px; border-radius: 10px;">
                                    <div class="progress-bar bg-{{ ($systemInfo['disk_used_pct'] ?? 0) > 80 ? 'danger' : 'primary' }}" style="width:{{ $systemInfo['disk_used_pct'] ?? 0 }}%"></div>
                                </div>
                                <div class="mt-2 small text-muted">{{ $systemInfo['disk_free'] }} available of {{ $systemInfo['disk_total'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 border rounded-15 bg-light h-100">
                                <div class="text-muted small fw-bold mb-1">SERVER SOFTWARE</div>
                                <div class="small fw-bold">{{ $systemInfo['server_software'] }}</div>
                                <div class="small text-muted mt-1">Host: {{ $systemInfo['server_name'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PANEL: MAINTENANCE -->
            <div class="settings-panel" id="panel-maintenance">
                <div class="card-settings border-danger">
                    <div class="card-settings-title text-danger"><i class="fas fa-tools"></i> Dangerous Actions</div>
                    <p class="text-muted small">These actions affect the entire application performance and state.</p>
                    
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-0">Rebuild Application Cache</h6>
                                <p class="text-muted xx-small mb-0">Last ran: {{ $systemInfo['last_cache_clear'] }}</p>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3" onclick="doClearCache()" id="btnCache">
                                <i class="fas fa-sync-alt me-1"></i> Run Now
                            </button>
                        </div>
                        <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-0">Database Connectivity Test</h6>
                                <p class="text-muted xx-small mb-0">Ensure the POS system can talk to the server.</p>
                            </div>
                            <button type="button" class="btn btn-outline-info btn-sm rounded-pill px-3" onclick="doTestDb()" id="btnDbTest">
                                <i class="fas fa-plug me-1"></i> Test Link
                            </button>
                        </div>
                        <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center border-bottom-0">
                            <div>
                                <h6 class="fw-bold mb-0">Debug Mode</h6>
                                <p class="text-muted xx-small mb-0">Currently: {{ $systemInfo['app_debug'] }}</p>
                            </div>
                            <span class="badge bg-{{ $systemInfo['app_debug'] == 'Enabled' ? 'warning' : 'success' }} rounded-pill">
                                {{ $systemInfo['app_debug'] }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sticky Footer for Save -->
            <div class="settings-footer rounded-20 shadow">
                <a href="{{ route('admin.settings.index') }}" class="btn btn-light rounded-pill px-4 fw-bold border">Discard Changes</a>
                <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                    <i class="fas fa-save me-2"></i> Save Configuration
                </button>
            </div>

        </div>
    </div>
</form>

<div id="toastContainer" style="position:fixed; top:20px; right:20px; z-index:1060;"></div>
@endsection

@push('scripts')
<script>
    function switchTab(tabId) {
        // Update Buttons
        document.querySelectorAll('.s-nav-item').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.tab === tabId);
        });
        
        // Update Panels
        document.querySelectorAll('.settings-panel').forEach(panel => {
            panel.classList.toggle('active', panel.id === 'panel-' + tabId);
        });

        // Update Hidden Input
        document.getElementById('activeTabInput').value = tabId;
        
        // Save current tab to local storage to persist through refresh
        localStorage.setItem('settings_active_tab', tabId);
    }

    // Auto-restore tab or handle session tab
    window.addEventListener('DOMContentLoaded', () => {
        const sessionTab = '{{ session("active_tab") }}';
        const lastTab = localStorage.getItem('settings_active_tab');
        const targetTab = sessionTab || lastTab || 'general';
        switchTab(targetTab);
    });

    // Logo Preview Logic
    document.getElementById('logoInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                document.getElementById('logoPreview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Receipt Live Update
    const headerInput = document.getElementById('receiptHeaderInput');
    const footerInput = document.getElementById('receiptFooterInput');
    const previewHeader = document.getElementById('previewHeader');
    const previewFooter = document.getElementById('previewFooter');
    const showTaxToggle = document.getElementById('showTaxToggle');
    const taxRow = document.getElementById('taxRow');

    headerInput.addEventListener('input', () => previewHeader.innerText = headerInput.value || 'CJ Minimart');
    footerInput.addEventListener('input', () => previewFooter.innerText = footerInput.value || 'Thank you!');
    showTaxToggle.addEventListener('change', () => taxRow.style.display = showTaxToggle.checked ? 'flex' : 'none');

    // AJAX Maintenance Tasks
    function showToast(msg, type = 'primary') {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type} shadow-lg py-2 px-4 rounded-pill mb-2`;
        toast.style.cssText = 'animation: slideIn 0.3s ease-out;';
        toast.innerHTML = msg;
        document.getElementById('toastContainer').appendChild(toast);
        setTimeout(() => toast.remove(), 4000);
    }

    function doClearCache() {
        const btn = document.getElementById('btnCache');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Working...';
        
        fetch("{{ route('admin.settings.clear-cache') }}", {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            showToast(data.message, data.success ? 'success' : 'danger');
            btn.innerHTML = '<i class="fas fa-sync-alt me-1"></i> Run Now';
            btn.disabled = false;
        })
        .catch(() => {
            showToast('Something went wrong!', 'danger');
            btn.innerHTML = '<i class="fas fa-sync-alt me-1"></i> Run Now';
            btn.disabled = false;
        });
    }

    function doTestDb() {
        const btn = document.getElementById('btnDbTest');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Testing...';
        
        fetch("{{ route('admin.settings.test-db') }}", {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            showToast(`${data.message} (${data.response_time})`, data.success ? 'info' : 'danger');
            btn.innerHTML = '<i class="fas fa-plug me-1"></i> Test Link';
            btn.disabled = false;
        })
        .catch(() => {
            showToast('DB Connection Failed!', 'danger');
            btn.innerHTML = '<i class="fas fa-plug me-1"></i> Test Link';
            btn.disabled = false;
        });
    }
</script>

<style>
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    .rounded-15 { border-radius: 15px; }
    .rounded-20 { border-radius: 20px; }
    .xx-small { font-size: 0.7rem; }
</style>
@endpush
