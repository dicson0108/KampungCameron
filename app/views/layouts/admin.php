<?php
$adminPath = $_GET['route'] ?? $_SERVER['PATH_INFO'] ?? parse_url($_SERVER['REQUEST_URI'] ?? '/admin', PHP_URL_PATH);
$adminPath = substr($adminPath, strpos($adminPath, '/admin') ?: 0);
$adminPath = rtrim($adminPath, '/');
if ($adminPath === '/admin') $adminPath = '/admin/dashboard';
$adminLinks = ['dashboard' => 'Dashboard', 'products' => 'Products', 'categories' => 'Categories', 'inventory' => 'Inventory', 'orders' => 'Orders', 'customers' => 'Customers', 'wholesale' => 'Wholesale Applications', 'wholesale/tiers' => 'Pricing Tiers', 'wholesale/standing-orders' => 'Standing Orders', 'promotions' => 'Promotions', 'reviews' => 'Reviews', 'recipes' => 'Recipes', 'reports' => 'Reports', 'settings' => 'Settings'];
$activeAdminLink = 'dashboard';
foreach ($adminLinks as $path => $label) {
    if ($adminPath === '/admin/' . $path || str_starts_with($adminPath, '/admin/' . $path . '/')) $activeAdminLink = $path;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#faf8f5">
    <title><?= e($title ?? 'Store Administration') ?> — Kampung Cameron</title>
    <link rel="icon" href="<?= url('/favicon.ico') ?>">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
</head>
<body class="admin-body">
    <a class="skip-link" href="#admin-content">Skip to Content</a>
    <aside class="admin-sidebar">
        <div class="admin-brand"><a href="<?= url('/admin/dashboard') ?>"><h2>Kampung Cameron</h2><span>Administration</span></a></div>
        <nav aria-label="Administration">
            <ul class="admin-nav">
                <?php foreach ($adminLinks as $path => $label): ?>
                    <li class="admin-nav-item"><a href="<?= url('/admin/' . $path) ?>" class="admin-nav-link <?= $activeAdminLink === $path ? 'active' : '' ?>" <?= $activeAdminLink === $path ? 'aria-current="page"' : '' ?>><?= e($label) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </nav>
        <div class="admin-sidebar-footer"><span><?= e(auth_user()['name'] ?? 'Store Manager') ?></span><a href="<?= url('/admin/logout') ?>">Log Out</a></div>
    </aside>
    <div class="admin-main">
        <header class="admin-topbar"><div class="admin-breadcrumb">Administration / <strong><?= e($title ?? 'Dashboard') ?></strong></div><a href="<?= url('/') ?>" target="_blank" rel="noopener">View Storefront ↗</a></header>
        <main class="admin-content" id="admin-content" tabindex="-1">
            <?php foreach (get_flashes() as $type => $messages): ?>
                <?php foreach ($messages as $message): ?><div role="status" class="alert alert-<?= $type === 'error' ? 'error' : 'success' ?>"><?= e($message) ?></div><?php endforeach; ?>
            <?php endforeach; ?>
            <?= $content ?>
        </main>
    </div>
</body>
</html>
