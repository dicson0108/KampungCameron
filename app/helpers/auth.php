<?php
// Authentication & Security Helpers

function auth_user(): ?array {
    return $_SESSION['user'] ?? null;
}

function auth_id(): ?int {
    return $_SESSION['user']['id'] ?? null;
}

function is_logged_in(): bool {
    return !empty($_SESSION['user']);
}

function has_role(string $role): bool {
    return is_logged_in() && ($_SESSION['user']['role'] ?? '') === $role;
}

function is_admin(): bool {
    return has_role('admin');
}

function is_wholesale(): bool {
    return has_role('wholesale');
}

function login_user(array $user): void {
    $_SESSION['user'] = [
        'id'    => (int)$user['id'],
        'name'  => $user['name'],
        'email' => $user['email'],
        'phone' => $user['phone'] ?? '',
        'role'  => $user['role'],
    ];

    // If wholesale user, load tier info
    if ($user['role'] === 'wholesale') {
        $ws = DB::fetch(
            "SELECT wa.*, pt.code as tier_code, pt.discount_percent, pt.name as tier_name 
             FROM wholesale_accounts wa
             LEFT JOIN pricing_tiers pt ON wa.tier_id = pt.id
             WHERE wa.user_id = :uid",
            ['uid' => $user['id']]
        );
        $_SESSION['wholesale'] = $ws;
    }
}

function logout_user(): void {
    unset($_SESSION['user'], $_SESSION['wholesale']);
}

function require_login(string $redirect = '/account/login'): void {
    if (!is_logged_in()) {
        set_flash('error', __('msg_login_required', 'Please log in to continue.'));
        redirect($redirect);
    }
}

function require_role(string $role, string $redirect = '/'): void {
    if (!has_role($role)) {
        set_flash('error', __('msg_access_denied', 'Access denied. You do not have permission to view this page.'));
        redirect($redirect);
    }
}

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token()) . '">';
}

function verify_csrf(): bool {
    $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    return !empty($token) && hash_equals($_SESSION['csrf_token'] ?? '', $token);
}
