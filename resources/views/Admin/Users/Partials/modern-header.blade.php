<!-- Enhanced Modern Header Component -->
<div class="modern-header-enhanced">
    <div class="header-background"></div>
    <div class="header-content">
        <div class="header-main">
            <div class="header-left">
                <div class="header-icon-wrapper">
                    <div class="header-icon" style="background: {{ $headerGradient ?? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' }};">
                        <i class="fas {{ $headerIcon ?? 'fa-users' }}"></i>
                    </div>
                </div>
                <div class="header-text">
                    <h1 class="header-title">{{ $headerTitle ?? 'User Management' }}</h1>
                    @if (isset($headerSubtitle))
                        <p class="header-subtitle">{{ $headerSubtitle }}</p>
                    @endif
                    @if (isset($breadcrumbs) && $breadcrumbs)
                        <nav aria-label="breadcrumb" class="header-breadcrumb">
                            <ol class="breadcrumb">
                                @foreach ($breadcrumbs as $breadcrumb)
                                    @if ($loop->last)
                                        <li class="breadcrumb-item active">{{ $breadcrumb['label'] }}</li>
                                    @else
                                        <li class="breadcrumb-item">
                                            <a href="{{ $breadcrumb['url'] }}" class="breadcrumb-link">{{ $breadcrumb['label'] }}</a>
                                        </li>
                                    @endif
                                @endforeach
                            </ol>
                        </nav>
                    @endif
                </div>
            </div>
            @if (isset($headerActions) && $headerActions)
                <div class="header-actions">
                    @foreach ($headerActions as $action)
                        @if ($action['type'] === 'link')
                            <a href="{{ $action['url'] }}" class="btn btn-{{ $action['variant'] ?? 'primary' }} header-action-btn" title="{{ $action['title'] ?? '' }}">
                                @if (isset($action['icon']))
                                    <i class="fas {{ $action['icon'] }} me-2"></i>
                                @endif
                                <span class="d-none d-sm-inline">{{ $action['label'] }}</span>
                            </a>
                        @elseif ($action['type'] === 'button')
                            <button type="button" class="btn btn-{{ $action['variant'] ?? 'primary' }} header-action-btn" 
                                    onclick="{{ $action['onclick'] ?? '' }}" title="{{ $action['title'] ?? '' }}">
                                @if (isset($action['icon']))
                                    <i class="fas {{ $action['icon'] }} me-2"></i>
                                @endif
                                <span class="d-none d-sm-inline">{{ $action['label'] }}</span>
                            </button>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
        @if (isset($headerStats) && $headerStats)
            <div class="header-stats">
                @foreach ($headerStats as $stat)
                    <div class="header-stat">
                        <span class="stat-label">{{ $stat['label'] }}</span>
                        <span class="stat-value">{{ $stat['value'] }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<style>
    /* Enhanced Modern Header Styles */
    .modern-header-enhanced {
        background: white;
        border-radius: 16px;
        padding: 28px 32px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
        position: relative;
        overflow: hidden;
        animation: slideDownIn 0.6s ease-out;
        margin-bottom: 28px;
    }

    .header-background {
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        opacity: 0.03;
        border-radius: 50%;
        transform: rotate(25deg);
        pointer-events: none;
    }

    .header-content {
        position: relative;
        z-index: 2;
    }

    .header-main {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 20px;
        flex: 1;
        min-width: 0;
    }

    .header-icon-wrapper {
        flex-shrink: 0;
    }

    .header-icon {
        width: 64px;
        height: 64px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.8rem;
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.25);
        animation: scaleIn 0.6s ease-out;
    }

    .header-text {
        flex: 1;
        min-width: 0;
    }

    .header-title {
        font-size: clamp(1.5rem, 4vw, 2rem);
        font-weight: 800;
        color: #1e1e2d;
        margin: 0 0 8px 0;
        word-break: break-word;
        letter-spacing: -0.5px;
    }

    .header-subtitle {
        font-size: 0.95rem;
        color: #6c757d;
        margin: 0;
        line-height: 1.4;
    }

    .header-breadcrumb {
        margin-top: 12px;
        margin-bottom: 0;
    }

    .header-breadcrumb .breadcrumb {
        margin: 0;
        padding: 0;
        background: transparent;
        font-size: 0.85rem;
    }

    .header-breadcrumb .breadcrumb-item {
        color: #6c757d;
    }

    .header-breadcrumb .breadcrumb-item.active {
        color: #667eea;
        font-weight: 600;
    }

    .breadcrumb-link {
        color: #667eea;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .breadcrumb-link:hover {
        color: #764ba2;
        text-decoration: underline;
    }

    .header-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: center;
    }

    .header-action-btn {
        border-radius: 10px;
        padding: 10px 18px;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        white-space: nowrap;
    }

    .header-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
    }

    .header-action-btn:active {
        transform: translateY(0);
    }

    .header-stats {
        display: flex;
        gap: 24px;
        flex-wrap: wrap;
        padding-top: 16px;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
    }

    .header-stat {
        display: flex;
        flex-direction: column;
        gap: 4px;
        animation: fadeInUp 0.6s ease-out;
    }

    .stat-label {
        font-size: 0.75rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        font-weight: 700;
    }

    .stat-value {
        font-size: 1.4rem;
        font-weight: 800;
        color: #1e1e2d;
    }

    /* Animations */
    @keyframes slideDownIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .modern-header-enhanced {
            padding: 24px 28px;
        }

        .header-main {
            gap: 16px;
        }

        .header-icon {
            width: 56px;
            height: 56px;
            font-size: 1.6rem;
        }

        .header-title {
            font-size: clamp(1.3rem, 3vw, 1.8rem);
        }
    }

    @media (max-width: 768px) {
        .modern-header-enhanced {
            padding: 20px 20px;
            margin-bottom: 20px;
        }

        .header-main {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }

        .header-left {
            width: 100%;
        }

        .header-icon {
            width: 52px;
            height: 52px;
            font-size: 1.4rem;
        }

        .header-title {
            font-size: 1.4rem;
        }

        .header-subtitle {
            font-size: 0.9rem;
        }

        .header-actions {
            width: 100%;
            justify-content: flex-start;
        }

        .header-action-btn {
            flex: 1;
            min-width: 120px;
        }

        .header-stats {
            gap: 16px;
        }
    }

    @media (max-width: 576px) {
        .modern-header-enhanced {
            padding: 16px 16px;
            margin-bottom: 16px;
        }

        .header-icon {
            width: 48px;
            height: 48px;
            font-size: 1.2rem;
        }

        .header-title {
            font-size: 1.2rem;
            font-weight: 700;
        }

        .header-subtitle {
            font-size: 0.85rem;
        }

        .header-breadcrumb .breadcrumb {
            font-size: 0.75rem;
        }

        .header-actions {
            width: 100%;
            gap: 8px;
        }

        .header-action-btn {
            padding: 8px 12px;
            font-size: 0.8rem;
            flex: 1;
        }

        .header-action-btn i {
            margin-right: 0 !important;
        }

        .header-action-btn span {
            display: none;
        }

        .header-stats {
            gap: 12px;
            font-size: 0.9rem;
        }

        .stat-label {
            font-size: 0.7rem;
        }

        .stat-value {
            font-size: 1.2rem;
        }
    }

    /* Accessibility */
    .header-action-btn:focus {
        outline: 2px solid #667eea;
        outline-offset: 2px;
    }

    /* Print Styles */
    @media print {
        .modern-header-enhanced {
            box-shadow: none;
            border: 1px solid #ddd;
        }

        .header-actions {
            display: none;
        }
    }
</style>
