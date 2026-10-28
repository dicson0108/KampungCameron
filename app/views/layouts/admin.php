<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Store Administration') ?> — Kampung Cameron</title>
    <link rel="icon" type="image/x-icon" href="<?= url('/favicon.ico') ?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?= url('/favicon.ico') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= asset('images/favicon-32x32.png') ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= asset('images/apple-touch-icon.png') ?>">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
    <script>window.BASE_URL = '<?= BASE_URL ?>';</script>
</head>
<body class="admin-body">
    <!-- Admin Sidebar -->
    <aside class="admin-sidebar">
        <div class="admin-brand">
            <a href="<?= url('/admin/dashboard') ?>" style="display: flex; align-items: center; gap: 12px; text-decoration: none;">
                <img src="<?= asset('images/logo-tight.png') ?>" alt="Kampung Cameron" style="height: 44px; width: auto; background: #ffffff; border-radius: 8px; padding: 2px;">
                <div>
                    <h2 style="font-size: 16px; margin: 0; line-height: 1.15; color: #ffffff;">Kampung<br>Cameron</h2>
                    <span style="font-size: 10px; opacity: 0.75; letter-spacing: 0.06em; text-transform: uppercase; font-weight: 700; color: #ffffff;">Admin Console</span>
                </div>
            </a>
        </div>

        <ul class="admin-nav">
            <li class="admin-nav-item">
                <a href="<?= url('/admin/dashboard') ?>" class="admin-nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', 'dashboard') ? 'active' : '' ?>">Dashboard</a>
            </li>
            <li class="admin-nav-item">
                <a href="<?= url('/admin/products') ?>" class="admin-nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', 'products') ? 'active' : '' ?>">Products</a>
            </li>
            <li class="admin-nav-item">
                <a href="<?= url('/admin/categories') ?>" class="admin-nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', 'categories') ? 'active' : '' ?>">Categories</a>
            </li>
            <li class="admin-nav-item">
                <a href="<?= url('/admin/inventory') ?>" class="admin-nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', 'inventory') ? 'active' : '' ?>">Inventory</a>
            </li>
            <li class="admin-nav-item">
                <a href="<?= url('/admin/orders') ?>" class="admin-nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', 'orders') ? 'active' : '' ?>">Orders</a>
            </li>
            <li class="admin-nav-item">
                <a href="<?= url('/admin/customers') ?>" class="admin-nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', 'customers') ? 'active' : '' ?>">Customers</a>
            </li>
            <li class="admin-nav-item">
                <a href="<?= url('/admin/wholesale') ?>" class="admin-nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', 'wholesale') && !str_contains($_SERVER['REQUEST_URI'] ?? '', 'tiers') && !str_contains($_SERVER['REQUEST_URI'] ?? '', 'standing') ? 'active' : '' ?>">Wholesale</a>
            </li>
            <li class="admin-nav-item">
                <a href="<?= url('/admin/wholesale/tiers') ?>" class="admin-nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', 'tiers') ? 'active' : '' ?>">Pricing tiers</a>
            </li>
            <li class="admin-nav-item">
                <a href="<?= url('/admin/wholesale/standing-orders') ?>" class="admin-nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', 'standing-orders') ? 'active' : '' ?>">Standing orders</a>
            </li>
            <li class="admin-nav-item">
                <a href="<?= url('/admin/promotions') ?>" class="admin-nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', 'promotions') ? 'active' : '' ?>">Promotions</a>
            </li>
            <li class="admin-nav-item">
                <a href="<?= url('/admin/reviews') ?>" class="admin-nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', 'reviews') ? 'active' : '' ?>">Reviews</a>
            </li>
            <li class="admin-nav-item">
                <a href="<?= url('/admin/recipes') ?>" class="admin-nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', 'recipes') ? 'active' : '' ?>">Blog & Recipes</a>
            </li>
            <li class="admin-nav-item">
                <a href="<?= url('/admin/reports') ?>" class="admin-nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', 'reports') ? 'active' : '' ?>">Reports</a>
            </li>
            <li class="admin-nav-item">
                <a href="<?= url('/admin/settings') ?>" class="admin-nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', 'settings') ? 'active' : '' ?>">Settings</a>
            </li>
        </ul>

        <div class="admin-sidebar-footer">
            <span>Store manager</span>
            <a href="<?= url('/admin/logout') ?>" style="color: #fca5a5;">Log out</a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="admin-main">
        <header class="admin-topbar">
            <div class="admin-breadcrumb">
                Store overview / <strong><?= htmlspecialchars($title ?? 'Dashboard') ?></strong>
            </div>
            <div>
                <a href="<?= url('/') ?>" target="_blank" style="margin-right: 16px; color: var(--primary-green); font-weight: 600;">View Storefront ↗</a>
                <span>EN / 中文 · Admin</span>
            </div>
        </header>

        <div class="admin-content">
            <?php foreach (get_flashes() as $type => $messages): ?>
                <?php foreach ($messages as $msg): ?>
                    <div class="alert alert-<?= $type === 'error' ? 'error' : 'success' ?>">
                        <span><?= htmlspecialchars($msg) ?></span>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>

            <?= $content ?>
        </div>
    </div>
</body>
</html>
