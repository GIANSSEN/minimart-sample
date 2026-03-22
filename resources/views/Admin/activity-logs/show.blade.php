@extends('layouts.admin')

@section('title', 'Activity Log Details - CJ\'s Minimart')

@section('content')
<div class="activity-log-container">
    <!-- Enhanced Header Section -->
    <div class="log-header-enhanced mb-4">
        <div class="log-header-background"></div>
        <div class="log-header-content">
            <div class="log-header-top">
                <div class="log-header-left">
                    <div class="log-header-icon-large">
                        @php
                            $action = $log->description;
                            $icon = 'fa-circle';
                            $bgColor = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
                            if (str_contains($action, 'create')) {
                                $icon = 'fa-plus-circle';
                                $bgColor = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
                            } elseif (str_contains($action, 'update')) {
                                $icon = 'fa-edit';
                                $bgColor = 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)';
                            } elseif (str_contains($action, 'delete')) {
                                $icon = 'fa-trash';
                                $bgColor = 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)';
                            } elseif (str_contains($action, 'login')) {
                                $icon = 'fa-sign-in-alt';
                                $bgColor = 'linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%)';
                            } elseif (str_contains($action, 'logout')) {
                                $icon = 'fa-sign-out-alt';
                                $bgColor = 'linear-gradient(135deg, #6c757d 0%, #495057 100%)';
                            }
                        @endphp
                        <i class="fas {{ $icon }}" style="background: {{ $bgColor }}; -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"></i>
                    </div>
                    <div class="log-header-info-enhanced">
                        <h1 class="log-header-title-enhanced">Activity Log #{{ $log->id }}</h1>
                        <p class="log-header-subtitle-enhanced">{{ $log->description }}</p>
                        <div class="log-header-meta">
                            <span class="meta-badge">
                                <i class="fas fa-calendar-alt"></i>
                                {{ $log->created_at ? $log->created_at->format('M d, Y h:i A') : 'N/A' }}
                            </span>
                            <span class="meta-badge">
                                <i class="fas fa-network-wired"></i>
                                {{ $log->ip_address ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="log-header-actions-enhanced">
                    <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-light btn-icon-sm" title="Back">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <button onclick="printLog()" class="btn btn-light btn-icon-sm" title="Print">
                        <i class="fas fa-print"></i>
                    </button>
                    <button onclick="copyToClipboard()" class="btn btn-light btn-icon-sm" title="Copy Details">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline Info - Enhanced -->
    <div class="timeline-cards-enhanced mb-4">
        <div class="timeline-card-enhanced">
            <div class="timeline-icon-enhanced created">
                <i class="fas fa-plus-circle"></i>
            </div>
            <div class="timeline-content-enhanced">
                <span class="timeline-label-enhanced">Created</span>
                <span class="timeline-value-enhanced">{{ $log->created_at ? $log->created_at->format('M d, Y') : 'N/A' }}</span>
                <span class="timeline-time-enhanced">{{ $log->created_at ? $log->created_at->format('h:i:s A') : '' }}</span>
            </div>
        </div>

        @if ($log->created_at != $log->updated_at)
        <div class="timeline-card-enhanced">
            <div class="timeline-icon-enhanced updated">
                <i class="fas fa-pen-alt"></i>
            </div>
            <div class="timeline-content-enhanced">
                <span class="timeline-label-enhanced">Last Updated</span>
                <span class="timeline-value-enhanced">{{ $log->updated_at ? $log->updated_at->format('M d, Y') : 'N/A' }}</span>
                <span class="timeline-time-enhanced">{{ $log->updated_at ? $log->updated_at->format('h:i:s A') : '' }}</span>
            </div>
        </div>
        @endif

        <div class="timeline-card-enhanced">
            <div class="timeline-icon-enhanced age">
                <i class="fas fa-clock"></i>
            </div>
            <div class="timeline-content-enhanced">
                <span class="timeline-label-enhanced">Age</span>
                <span class="timeline-value-enhanced">{{ $log->created_at ? $log->created_at->diffForHumans() : 'N/A' }}</span>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="log-content-grid-enhanced">
        <!-- Left Column -->
        <div class="log-column-left-enhanced">
            <!-- User Card (Enhanced) -->
            <div class="log-card-enhanced">
                <div class="log-card-header-enhanced">
                    <div class="card-header-icon">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <h3>User Information</h3>
                </div>
                <div class="log-card-body-enhanced">
                    <div class="user-profile-enhanced">
                        <div class="user-avatar-wrapper">
                            <img src="{{ $log->user ? $log->user->avatar_url : 'https://ui-avatars.com/api/?name=System&background=6c757d&color=fff&size=100' }}" 
                                 alt="User Avatar" class="user-avatar-enhanced">
                            @if ($log->user)
                                <span class="user-status-badge">
                                    <i class="fas fa-check-circle"></i>
                                </span>
                            @endif
                        </div>
                        <div class="user-details-enhanced">
                            <h4 class="user-name-enhanced">{{ $log->user ? $log->user->full_name : 'System' }}</h4>
                            @if ($log->user)
                                <p class="user-role-enhanced">
                                    <span class="role-badge">{{ $log->user->role }}</span>
                                </p>
                                <p class="user-email-enhanced">
                                    <i class="fas fa-envelope"></i> {{ $log->user->email }}
                                </p>
                            @else
                                <p class="user-system-enhanced">
                                    <i class="fas fa-cog"></i> System Generated Activity
                                </p>
                            @endif
                        </div>
                    </div>

                    @if ($log->user)
                    <div class="user-meta-enhanced">
                        <div class="meta-item-enhanced">
                            <span class="meta-label-enhanced">Employee ID</span>
                            <span class="meta-value-enhanced">{{ $log->user->employee_id ?? 'N/A' }}</span>
                        </div>
                        <div class="meta-item-enhanced">
                            <span class="meta-label-enhanced">Last Login</span>
                            <span class="meta-value-enhanced">{{ $log->user->last_login_at ? $log->user->last_login_at->format('M d, Y') : 'Never' }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Technical Details Card -->
            <div class="log-card-enhanced">
                <div class="log-card-header-enhanced">
                    <div class="card-header-icon">
                        <i class="fas fa-microchip"></i>
                    </div>
                    <h3>Technical Details</h3>
                </div>
                <div class="log-card-body-enhanced">
                    <div class="tech-details-enhanced">
                        <div class="tech-row-enhanced">
                            <span class="tech-label-enhanced">Log ID</span>
                            <span class="tech-value-enhanced">
                                <code>#{{ $log->id }}</code>
                            </span>
                        </div>
                        <div class="tech-row-enhanced">
                            <span class="tech-label-enhanced">Action Type</span>
                            <span class="tech-value-enhanced">
                                @php
                                    $type = $log->log_name ?? 'N/A';
                                    $typeColors = ['CREATE'=>'success','UPDATE'=>'warning','DELETE'=>'danger','LOGIN'=>'info','LOGOUT'=>'secondary'];
                                    $typeColor = $typeColors[$type] ?? 'primary';
                                @endphp
                                <span class="badge bg-{{ $typeColor }}">{{ $type }}</span>
                            </span>
                        </div>
                        <div class="tech-row-enhanced">
                            <span class="tech-label-enhanced">Model Type</span>
                            <span class="tech-value-enhanced">
                                @if ($log->subject_type)
                                    <span class="badge bg-info">{{ class_basename($log->subject_type) }}</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </span>
                        </div>
                        @if ($log->subject_id)
                        <div class="tech-row-enhanced">
                            <span class="tech-label-enhanced">Model ID</span>
                            <span class="tech-value-enhanced">
                                <span class="badge bg-secondary">#{{ $log->subject_id }}</span>
                            </span>
                        </div>
                        @endif
                        <div class="tech-row-enhanced">
                            <span class="tech-label-enhanced">IP Address</span>
                            <span class="tech-value-enhanced">
                                <code>{{ $log->ip_address ?? 'N/A' }}</code>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="log-column-right-enhanced">
            <!-- Action Details Card -->
            <div class="log-card-enhanced">
                <div class="log-card-header-enhanced">
                    <div class="card-header-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3>Action Details</h3>
                </div>
                <div class="log-card-body-enhanced">
                    @php
                        $action = $log->description;
                        $color = 'primary';
                        $icon = 'circle';
                        if (str_contains($action, 'create')) {
                            $color = 'success';
                            $icon = 'plus-circle';
                        } elseif (str_contains($action, 'update')) {
                            $color = 'warning';
                            $icon = 'edit';
                        } elseif (str_contains($action, 'delete')) {
                            $color = 'danger';
                            $icon = 'trash';
                        } elseif (str_contains($action, 'login')) {
                            $color = 'info';
                            $icon = 'sign-in-alt';
                        } elseif (str_contains($action, 'logout')) {
                            $color = 'secondary';
                            $icon = 'sign-out-alt';
                        }
                    @endphp
                    
                    <div class="action-badge-enhanced">
                        <span class="badge bg-{{ $color }} badge-lg">
                            <i class="fas fa-{{ $icon }}"></i>
                            {{ strtoupper($log->log_name ?? 'ACTION') }}
                        </span>
                    </div>

                    <div class="action-description-enhanced">
                        <h5>Description</h5>
                        <p>{{ $log->description }}</p>
                    </div>

                    @if ($log->user_agent)
                    <div class="action-useragent-enhanced">
                        <h5>User Agent</h5>
                        <p class="text-break user-agent-text">{{ $log->user_agent }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Data Changes Card -->
            @if (isset($oldData) || isset($newData))
            <div class="log-card-enhanced">
                <div class="log-card-header-enhanced">
                    <div class="card-header-icon">
                        <i class="fas fa-code-branch"></i>
                    </div>
                    <h3>Data Changes</h3>
                </div>
                <div class="log-card-body-enhanced">
                    <ul class="nav nav-tabs log-tabs-enhanced" role="tablist">
                        @if (isset($oldData) && !empty($oldData))
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ isset($newData) ? '' : 'active' }}" id="old-tab" data-bs-toggle="tab" data-bs-target="#old" type="button" role="tab">
                                <i class="fas fa-history me-2"></i>Old Values
                            </button>
                        </li>
                        @endif
                        @if (isset($newData) && !empty($newData))
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ !isset($oldData) ? 'active' : '' }}" id="new-tab" data-bs-toggle="tab" data-bs-target="#new" type="button" role="tab">
                                <i class="fas fa-star me-2"></i>New Values
                            </button>
                        </li>
                        @endif
                        @if (isset($oldData) && isset($newData) && !empty($oldData) && !empty($newData))
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="diff-tab" data-bs-toggle="tab" data-bs-target="#diff" type="button" role="tab">
                                <i class="fas fa-exchange-alt me-2"></i>Difference
                            </button>
                        </li>
                        @endif
                    </ul>
                    
                    <div class="tab-content log-tab-content-enhanced">
                        @if (isset($oldData) && !empty($oldData))
                        <div class="tab-pane fade {{ isset($newData) ? '' : 'show active' }}" id="old" role="tabpanel">
                            <pre class="code-block-enhanced"><code>{{ json_encode($oldData, JSON_PRETTY_PRINT) }}</code></pre>
                        </div>
                        @endif
                        @if (isset($newData) && !empty($newData))
                        <div class="tab-pane fade {{ !isset($oldData) ? 'show active' : '' }}" id="new" role="tabpanel">
                            <pre class="code-block-enhanced"><code>{{ json_encode($newData, JSON_PRETTY_PRINT) }}</code></pre>
                        </div>
                        @endif
                        @if (isset($oldData) && isset($newData) && !empty($oldData) && !empty($newData))
                        <div class="tab-pane fade" id="diff" role="tabpanel">
                            <div class="diff-container-enhanced">
                                @php $allKeys = array_keys(array_merge($oldData, $newData)); @endphp
                                @foreach ($allKeys as $key)
                                    @php
                                        $oldVal = $oldData[$key] ?? null;
                                        $newVal = $newData[$key] ?? null;
                                        if (json_encode($oldVal) !== json_encode($newVal)):
                                    @endphp
                                    <div class="diff-item-enhanced">
                                        <div class="diff-key-enhanced">{{ $key }}</div>
                                        <div class="diff-values-enhanced">
                                            <div class="diff-old-enhanced">
                                                <span class="diff-label-enhanced">Old</span>
                                                <code>{{ is_array($oldVal) ? json_encode($oldVal) : ($oldVal ?? 'null') }}</code>
                                            </div>
                                            <div class="diff-new-enhanced">
                                                <span class="diff-label-enhanced">New</span>
                                                <code>{{ is_array($newVal) ? json_encode($newVal) : ($newVal ?? 'null') }}</code>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Root Variables */
    :root {
        --primary-color: #667eea;
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --info-color: #3b82f6;
        --light-bg: #f8f9fa;
        --border-color: #e5e7eb;
        --text-dark: #1e1e2d;
        --text-muted: #6c757d;
    }

    /* Container */
    .activity-log-container {
        width: 100%;
        max-width: 100%;
        padding: 0;
        margin: 0;
        animation: fadeInUp 0.6s ease-out;
    }

    /* Enhanced Header */
    .log-header-enhanced {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid var(--border-color);
        position: relative;
    }

    .log-header-background {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 120px;
        background: var(--primary-gradient);
        opacity: 0.05;
    }

    .log-header-content {
        position: relative;
        z-index: 2;
        padding: 32px;
    }

    .log-header-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 24px;
        flex-wrap: wrap;
    }

    .log-header-left {
        display: flex;
        align-items: flex-start;
        gap: 24px;
        flex: 1;
        min-width: 0;
    }

    .log-header-icon-large {
        width: 80px;
        height: 80px;
        border-radius: 16px;
        background: var(--primary-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.5rem;
        flex-shrink: 0;
        box-shadow: 0 8px 24px rgba(102,126,234,0.3);
        animation: scaleIn 0.6s ease-out;
    }

    .log-header-info-enhanced {
        flex: 1;
        min-width: 0;
    }

    .log-header-title-enhanced {
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--text-dark);
        margin: 0 0 8px 0;
        word-break: break-word;
    }

    .log-header-subtitle-enhanced {
        font-size: 1rem;
        color: var(--text-muted);
        margin: 0 0 16px 0;
        line-height: 1.5;
    }

    .log-header-meta {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .meta-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--light-bg);
        color: var(--text-dark);
        padding: 8px 14px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }

    .meta-badge:hover {
        background: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .meta-badge i {
        color: var(--primary-color);
    }

    .log-header-actions-enhanced {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn-icon-sm {
        width: 40px;
        height: 40px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        transition: all 0.3s ease;
        background: var(--light-bg);
        border: 1px solid var(--border-color);
        color: var(--text-dark);
    }

    .btn-icon-sm:hover {
        background: var(--primary-gradient);
        color: white;
        border-color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102,126,234,0.3);
    }

    /* Timeline Cards Enhanced */
    .timeline-cards-enhanced {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
    }

    .timeline-card-enhanced {
        background: white;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid var(--border-color);
        display: flex;
        align-items: flex-start;
        gap: 16px;
        transition: all 0.3s ease;
        animation: slideInUp 0.6s ease-out;
    }

    .timeline-card-enhanced:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        border-color: var(--primary-color);
        transform: translateY(-4px);
    }

    .timeline-icon-enhanced {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.4rem;
        flex-shrink: 0;
    }

    .timeline-icon-enhanced.created {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .timeline-icon-enhanced.updated {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }

    .timeline-icon-enhanced.age {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    }

    .timeline-content-enhanced {
        flex: 1;
        min-width: 0;
    }

    .timeline-label-enhanced {
        display: block;
        font-size: 0.75rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin-bottom: 6px;
        font-weight: 700;
    }

    .timeline-value-enhanced {
        display: block;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 4px;
    }

    .timeline-time-enhanced {
        display: block;
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    /* Content Grid Enhanced */
    .log-content-grid-enhanced {
        display: grid;
        grid-template-columns: 1fr 1.2fr;
        gap: 24px;
    }

    /* Cards Enhanced */
    .log-card-enhanced {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
        margin-bottom: 24px;
        animation: fadeInUp 0.6s ease-out;
    }

    .log-card-enhanced:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        border-color: var(--primary-color);
    }

    .log-card-header-enhanced {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        padding: 20px;
        border-bottom: 2px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .card-header-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: var(--primary-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .log-card-header-enhanced h3 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-dark);
    }

    .log-card-body-enhanced {
        padding: 24px;
    }

    /* User Profile Enhanced */
    .user-profile-enhanced {
        display: flex;
        gap: 20px;
        margin-bottom: 24px;
        align-items: flex-start;
    }

    .user-avatar-wrapper {
        position: relative;
        flex-shrink: 0;
    }

    .user-avatar-enhanced {
        width: 100px;
        height: 100px;
        border-radius: 14px;
        object-fit: cover;
        border: 3px solid var(--border-color);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        transition: all 0.3s ease;
    }

    .user-avatar-enhanced:hover {
        transform: scale(1.05);
        box-shadow: 0 12px 28px rgba(0,0,0,0.15);
    }

    .user-status-badge {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 32px;
        height: 32px;
        background: var(--success-color);
        border: 3px solid white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.9rem;
        box-shadow: 0 4px 12px rgba(16,185,129,0.3);
    }

    .user-details-enhanced {
        flex: 1;
        min-width: 0;
    }

    .user-name-enhanced {
        font-size: 1.3rem;
        font-weight: 800;
        color: var(--text-dark);
        margin: 0 0 8px 0;
        word-break: break-word;
    }

    .user-role-enhanced {
        margin: 8px 0;
    }

    .role-badge {
        display: inline-block;
        background: var(--primary-gradient);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .user-email-enhanced,
    .user-system-enhanced {
        font-size: 0.95rem;
        color: var(--text-muted);
        margin: 8px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .user-email-enhanced i,
    .user-system-enhanced i {
        color: var(--primary-color);
        width: 18px;
    }

    /* User Meta Enhanced */
    .user-meta-enhanced {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 16px;
        padding: 20px 0 0 0;
        border-top: 2px solid var(--border-color);
    }

    .meta-item-enhanced {
        background: var(--light-bg);
        padding: 16px;
        border-radius: 10px;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .meta-item-enhanced:hover {
        background: white;
        border-color: var(--primary-color);
        box-shadow: 0 4px 12px rgba(102,126,234,0.1);
    }

    .meta-label-enhanced {
        font-size: 0.75rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.6px;
        font-weight: 700;
        display: block;
        margin-bottom: 6px;
    }

    .meta-value-enhanced {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-dark);
        word-break: break-word;
    }

    /* Technical Details Enhanced */
    .tech-details-enhanced {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .tech-row-enhanced {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px;
        background: var(--light-bg);
        border-radius: 10px;
        gap: 12px;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .tech-row-enhanced:hover {
        background: white;
        border-color: var(--primary-color);
        box-shadow: 0 4px 12px rgba(102,126,234,0.1);
    }

    .tech-label-enhanced {
        font-size: 0.9rem;
        color: var(--text-muted);
        font-weight: 600;
        min-width: 120px;
    }

    .tech-value-enhanced {
        flex: 1;
        text-align: right;
    }

    .tech-value-enhanced code {
        background: white;
        padding: 4px 8px;
        border-radius: 6px;
        font-family: 'Courier New', monospace;
        font-size: 0.85rem;
        color: var(--text-dark);
        border: 1px solid var(--border-color);
    }

    .tech-value-enhanced .badge {
        font-weight: 600;
        padding: 6px 12px;
    }

    /* Action Badge Enhanced */
    .action-badge-enhanced {
        text-align: center;
        margin-bottom: 24px;
        padding: 20px;
        background: var(--light-bg);
        border-radius: 12px;
        border: 2px solid var(--border-color);
    }

    .badge-lg {
        font-size: 1.1rem;
        padding: 12px 24px !important;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    /* Action Description Enhanced */
    .action-description-enhanced,
    .action-useragent-enhanced {
        margin-bottom: 20px;
        padding: 20px;
        background: var(--light-bg);
        border-radius: 10px;
        border-left: 4px solid var(--primary-color);
    }

    .action-description-enhanced h5,
    .action-useragent-enhanced h5 {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin: 0 0 12px 0;
    }

    .action-description-enhanced p,
    .action-useragent-enhanced p {
        margin: 0;
        color: var(--text-dark);
        font-size: 0.95rem;
        line-height: 1.6;
    }

    .user-agent-text {
        font-family: 'Courier New', monospace;
        font-size: 0.85rem;
        background: white;
        padding: 12px;
        border-radius: 6px;
        border: 1px solid var(--border-color);
    }

    /* Tabs Enhanced */
    .log-tabs-enhanced {
        border-bottom: 2px solid var(--border-color);
        margin-bottom: 20px;
    }

    .log-tabs-enhanced .nav-link {
        border: none;
        color: var(--text-muted);
        padding: 14px 18px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        position: relative;
    }

    .log-tabs-enhanced .nav-link:hover {
        color: var(--primary-color);
    }

    .log-tabs-enhanced .nav-link.active {
        color: var(--primary-color);
    }

    .log-tabs-enhanced .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--primary-gradient);
        border-radius: 2px;
    }

    /* Tab Content Enhanced */
    .log-tab-content-enhanced {
        margin-top: 20px;
    }

    /* Code Block Enhanced */
    .code-block-enhanced {
        background: #1e1e2d;
        color: #e0e0e0;
        padding: 20px;
        border-radius: 10px;
        font-size: 0.85rem;
        overflow-x: auto;
        max-height: 450px;
        overflow-y: auto;
        margin: 0;
        line-height: 1.6;
        border: 1px solid rgba(255,255,255,0.1);
    }

    .code-block-enhanced code {
        font-family: 'Courier New', monospace;
        color: #e0e0e0;
    }

    /* Diff Container Enhanced */
    .diff-container-enhanced {
        display: flex;
        flex-direction: column;
        gap: 16px;
        max-height: 550px;
        overflow-y: auto;
    }

    .diff-item-enhanced {
        padding: 16px;
        background: var(--light-bg);
        border-radius: 10px;
        border-left: 4px solid var(--primary-color);
        transition: all 0.3s ease;
    }

    .diff-item-enhanced:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .diff-key-enhanced {
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 12px;
        font-size: 0.95rem;
        padding: 8px 12px;
        background: white;
        border-radius: 6px;
        border-left: 3px solid var(--primary-color);
    }

    .diff-values-enhanced {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .diff-old-enhanced,
    .diff-new-enhanced {
        padding: 12px;
        border-radius: 8px;
        font-size: 0.85rem;
    }

    .diff-old-enhanced {
        background: rgba(239, 68, 68, 0.1);
        border-left: 3px solid #ef4444;
    }

    .diff-new-enhanced {
        background: rgba(16, 185, 129, 0.1);
        border-left: 3px solid #10b981;
    }

    .diff-label-enhanced {
        display: block;
        font-weight: 700;
        margin-bottom: 6px;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .diff-old-enhanced .diff-label-enhanced {
        color: #ef4444;
    }

    .diff-new-enhanced .diff-label-enhanced {
        color: #10b981;
    }

    .diff-old-enhanced code,
    .diff-new-enhanced code {
        color: var(--text-dark);
        font-family: 'Courier New', monospace;
        word-break: break-all;
        background: white;
        padding: 6px;
        border-radius: 4px;
        display: block;
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(15px);
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

    /* Responsive Design */
    @media (max-width: 1024px) {
        .log-content-grid-enhanced {
            grid-template-columns: 1fr;
        }

        .timeline-cards-enhanced {
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .log-header-content {
            padding: 24px;
        }

        .log-header-top {
            flex-direction: column;
        }

        .log-header-icon-large {
            width: 70px;
            height: 70px;
            font-size: 2rem;
        }

        .log-header-title-enhanced {
            font-size: 1.5rem;
        }

        .log-header-actions-enhanced {
            width: 100%;
            justify-content: flex-start;
        }

        .timeline-cards-enhanced {
            grid-template-columns: 1fr;
        }

        .user-profile-enhanced {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .user-details-enhanced {
            width: 100%;
        }

        .user-meta-enhanced {
            grid-template-columns: 1fr;
        }

        .tech-row-enhanced {
            flex-direction: column;
            align-items: flex-start;
        }

        .tech-value-enhanced {
            text-align: left;
            width: 100%;
        }

        .diff-values-enhanced {
            grid-template-columns: 1fr;
        }

        .log-card-body-enhanced {
            padding: 16px;
        }

        .log-card-header-enhanced {
            padding: 16px;
        }
    }

    @media (max-width: 576px) {
        .log-header-content {
            padding: 16px;
        }

        .log-header-icon-large {
            width: 60px;
            height: 60px;
            font-size: 1.6rem;
        }

        .log-header-title-enhanced {
            font-size: 1.2rem;
        }

        .log-header-subtitle-enhanced {
            font-size: 0.9rem;
        }

        .log-header-meta {
            flex-direction: column;
            width: 100%;
        }

        .meta-badge {
            width: 100%;
            justify-content: center;
        }

        .log-header-actions-enhanced {
            width: 100%;
        }

        .btn-icon-sm {
            flex: 1;
        }

        .timeline-icon-enhanced {
            width: 44px;
            height: 44px;
            font-size: 1.2rem;
        }

        .user-avatar-enhanced {
            width: 80px;
            height: 80px;
        }

        .user-name-enhanced {
            font-size: 1.1rem;
        }

        .log-tabs-enhanced .nav-link {
            padding: 12px 10px;
            font-size: 0.8rem;
        }

        .log-tabs-enhanced .nav-link i {
            display: none;
        }

        .code-block-enhanced {
            font-size: 0.75rem;
            padding: 12px;
        }
    }

    /* Print Styles */
    @media print {
        .log-header-actions-enhanced {
            display: none;
        }

        .log-card-enhanced {
            page-break-inside: avoid;
            box-shadow: none;
            border: 1px solid #ddd;
        }

        body {
            background: white;
        }

        .activity-log-container {
            padding: 0;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function printLog() {
        window.print();
    }

    function copyToClipboard() {
        const logId = '{{ $log->id }}';
        const description = '{{ $log->description }}';
        const ipAddress = '{{ $log->ip_address }}';
        const createdAt = '{{ $log->created_at }}';
        
        const text = `Activity Log #${logId}\nDescription: ${description}\nIP Address: ${ipAddress}\nCreated: ${createdAt}`;
        
        navigator.clipboard.writeText(text).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Copied!',
                text: 'Activity log details copied to clipboard',
                timer: 2000,
                showConfirmButton: false
            });
        }).catch(() => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to copy to clipboard'
            });
        });
    }

    // Initialize Bootstrap tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
