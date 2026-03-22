<?php
/**
 * Helper Functions for Minimart Design System
 * File: app/Helpers/FormHelper.php or app/Helpers/UIHelper.php
 * 
 * Usage in Blade: Call these as functions in your blade templates
 */

/**
 * Get the color for a user status
 * @param string $status
 * @return string hex color code
 */
function getStatusColor($status)
{
    $colors = [
        'active'   => '#10b981',   // Green
        'inactive' => '#ef4444',   // Red
        'pending'  => '#f59e0b',   // Amber
    ];
    
    return $colors[strtolower($status)] ?? '#6b7280';
}

/**
 * Get the color for an approval status
 * @param string $status
 * @return string hex color code
 */
function getApprovalStatusColor($status)
{
    $colors = [
        'approved' => '#10b981',   // Green
        'pending'  => '#f59e0b',   // Amber
        'rejected' => '#ef4444',   // Red
    ];
    
    return $colors[strtolower($status ?? 'approved')] ?? '#667eea';
}

/**
 * Get the icon for an approval status
 * @param string $status
 * @return string Font Awesome class
 */
function getApprovalStatusIcon($status)
{
    $icons = [
        'approved' => 'fa-check-circle',
        'pending'  => 'fa-clock',
        'rejected' => 'fa-times-circle',
    ];
    
    return $icons[strtolower($status ?? 'approved')] ?? 'fa-info-circle';
}

/**
 * Get the color for a user role
 * @param string $role
 * @return string hex color code
 */
function getRoleColor($role)
{
    $colors = [
        'admin'         => '#667eea',
        'manager'       => '#f59e0b',
        'supervisor'    => '#10b981',
        'staff'         => '#3b82f6',
        'cashier'       => '#8b5cf6',
        'merchandiser'  => '#ec4899',
    ];
    
    return $colors[strtolower($role)] ?? '#667eea';
}

/**
 * Get the badge class for a role
 * @param string $role
 * @return string Bootstrap badge class
 */
function getRoleBadgeClass($role)
{
    $role = strtolower($role);
    
    $classes = [
        'admin'         => 'badge-primary',
        'manager'       => 'badge-warning',
        'supervisor'    => 'badge-success',
        'staff'         => 'badge-info',
        'cashier'       => 'badge-secondary',
        'merchandiser'  => 'badge-danger',
    ];
    
    return $classes[$role] ?? 'badge-secondary';
}

/**
 * Format a date for display
 * @param \DateTime|string $date
 * @param string $format
 * @return string formatted date
 */
function formatDate($date, $format = 'M d, Y')
{
    if (is_string($date)) {
        $date = \Carbon\Carbon::parse($date);
    }
    
    return $date->format($format);
}

/**
 * Get relative time (e.g., "2 hours ago")
 * @param \DateTime|string $date
 * @return string relative time
 */
function getRelativeTime($date)
{
    if (is_string($date)) {
        $date = \Carbon\Carbon::parse($date);
    }
    
    return $date->diffForHumans();
}

/**
 * Format a phone number
 * @param string $phone
 * @return string formatted phone
 */
function formatPhone($phone)
{
    if (!$phone) return 'N/A';
    
    // Remove non-numeric characters
    $phone = preg_replace('/[^0-9]/', '', $phone);
    
    // Format as (XXX) XXX-XXXX if 10 digits
    if (strlen($phone) == 10) {
        return '(' . substr($phone, 0, 3) . ') ' . substr($phone, 3, 3) . '-' . substr($phone, 6);
    }
    
    return $phone;
}

/**
 * Get initials from a name
 * @param string $name
 * @return string initials (2-3 characters)
 */
function getInitials($name)
{
    $parts = explode(' ', trim($name));
    $initials = '';
    
    foreach (array_slice($parts, 0, 2) as $part) {
        $initials .= strtoupper(substr($part, 0, 1));
    }
    
    return $initials ?: 'U';
}

/**
 * Get user avatar URL or generate placeholder
 * @param string|null $url
 * @param string|null $name
 * @param int $size
 * @return string avatar URL
 */
function getAvatarUrl($url = null, $name = null, $size = 40)
{
    if ($url && filter_var($url, FILTER_VALIDATE_URL)) {
        return $url;
    }
    
    if ($name) {
        return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=667eea&color=fff&size=' . $size;
    }
    
    return 'https://ui-avatars.com/api/?name=User&background=667eea&color=fff&size=' . $size;
}

/**
 * Render a status badge
 * @param string $status
 * @param array $options
 * @return string HTML badge
 */
function renderStatusBadge($status, $options = [])
{
    $color = getStatusColor($status);
    $class = $options['class'] ?? 'badge';
    
    return sprintf(
        '<span class="%s" style="background-color: %s;"><i class="fas fa-circle me-1" style="font-size: 0.6em;"></i>%s</span>',
        $class,
        $color,
        ucfirst($status)
    );
}

/**
 * Render a role badge
 * @param string $role
 * @param array $options
 * @return string HTML badge
 */
function renderRoleBadge($role, $options = [])
{
    $color = getRoleColor($role);
    $class = $options['class'] ?? 'badge';
    
    return sprintf(
        '<span class="%s" style="background-color: %s20; color: %s; border: 1px solid %s33;"><i class="fas fa-shield-alt me-1"></i>%s</span>',
        $class,
        $color,
        $color,
        $color,
        ucfirst($role)
    );
}

/**
 * Check if a value is displayed
 * @param mixed $value
 * @param string $default
 * @return string
 */
function displayOrDefault($value, $default = 'N/A')
{
    return !empty($value) ? $value : $default;
}

/**
 * Truncate text to a maximum length
 * @param string $text
 * @param int $length
 * @param string $suffix
 * @return string
 */
function truncateText($text, $length = 50, $suffix = '...')
{
    if (strlen($text) <= $length) {
        return $text;
    }
    
    return substr($text, 0, $length) . $suffix;
}

/**
 * Get status class for styling
 * @param string $status
 * @return string CSS class
 */
function getStatusClass($status)
{
    $classes = [
        'active'   => 'badge-success',
        'inactive' => 'badge-danger',
        'pending'  => 'badge-warning',
    ];
    
    return $classes[strtolower($status)] ?? 'badge-secondary';
}

/**
 * Check if user has permission
 * @param string $permission
 * @param \App\Models\User|null $user
 * @return bool
 */
function userHasPermission($permission, $user = null)
{
    $user = $user ?? auth()->user();
    
    if (!$user) {
        return false;
    }
    
    return $user->hasPermission($permission);
}

/**
 * Get action button HTML
 * @param string $action
 * @param string $route
 * @param string $label
 * @param array $options
 * @return string HTML
 */
function renderActionButton($action, $route, $label, $options = [])
{
    $classes = [
        'edit'   => 'btn-primary',
        'view'   => 'btn-info',
        'delete' => 'btn-danger',
        'approve' => 'btn-success',
        'reject' => 'btn-danger',
    ];
    
    $icons = [
        'edit'    => 'fa-edit',
        'view'    => 'fa-eye',
        'delete'  => 'fa-trash',
        'approve' => 'fa-check-circle',
        'reject'  => 'fa-times-circle',
    ];
    
    $btnClass = $classes[$action] ?? 'btn-secondary';
    $icon = $icons[$action] ?? 'fa-info-circle';
    
    return sprintf(
        '<a href="%s" class="btn btn-sm %s" title="%s"><i class="fas %s me-1"></i>%s</a>',
        $route,
        $btnClass,
        $label,
        $icon,
        $label
    );
}

/**
 * Validate email format
 * @param string $email
 * @return bool
 */
function isValidEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate phone format
 * @param string $phone
 * @return bool
 */
function isValidPhone($phone)
{
    $phone = preg_replace('/[^0-9]/', '', $phone);
    return strlen($phone) >= 10 && strlen($phone) <= 15;
}

/**
 * Get form section template
 * @param string $title
 * @param string $icon
 * @return string HTML start tag
 */
function formSectionStart($title, $icon = 'fas fa-circle')
{
    return sprintf(
        '<div class="form-section mb-5"><h6 class="form-section-title fw-bold mb-4 pb-3" style="border-bottom: 2px solid #f0f1f5;"><i class="%s text-gradient-secondary me-2"></i>%s</h6><div class="row g-4">',
        $icon,
        $title
    );
}

/**
 * Get form section template end
 * @return string HTML end tag
 */
function formSectionEnd()
{
    return '</div></div>';
}
