<?php
// Opt-in rendering smoke check: php tests/portal-pages.php --live
// Uses configured DB inside a rolled-back transaction. No business records persist.
if (!in_array('--live', $argv, true)) exit("Use --live to check pages against the configured local database.\n");
require __DIR__ . '/../config/db.php';
require APP_PATH . '/helpers/auth.php';
require APP_PATH . '/helpers/i18n.php';
require APP_PATH . '/helpers/view.php';
require APP_PATH . '/controllers/AdminController.php';
require APP_PATH . '/controllers/WholesaleController.php';
require APP_PATH . '/controllers/ProductController.php';
require APP_PATH . '/controllers/RecipeController.php';
set_error_handler(fn($n, $s, $f, $l) => throw new ErrorException($s, 0, $n, $f, $l));
$_SERVER['REQUEST_METHOD'] = 'GET';
$pdo = DB::connect();
$pdo->beginTransaction();
$count = 0;
try {
    $admin = DB::fetch("SELECT * FROM users WHERE role = 'admin' LIMIT 1");
    if (!$admin) throw new RuntimeException('Need a local admin fixture.');
    login_user($admin);
    $_SERVER['REQUEST_URI'] = '/admin/dashboard';
    $controller = new AdminController();
    foreach (['dashboard', 'products', 'productCreate', 'categories', 'inventory', 'orders', 'customers', 'wholesaleApplications', 'pricingTiers', 'adminStandingOrders', 'promotions', 'reviews', 'recipes', 'recipeEdit', 'reports', 'settings', 'wholesaleApplicationDetail', 'orderView', 'productEdit'] as $method) {
        $_SERVER['REQUEST_URI'] = '/admin/' . match ($method) {
            'productCreate' => 'products/create', 'productEdit' => 'products/edit',
            'wholesaleApplications' => 'wholesale', 'wholesaleApplicationDetail' => 'wholesale/application',
            'pricingTiers' => 'wholesale/tiers', 'adminStandingOrders' => 'wholesale/standing-orders',
            'recipeEdit' => 'recipes/edit', 'orderView' => 'orders/view', default => $method
        };
        $table = match ($method) { 'wholesaleApplicationDetail' => 'wholesale_accounts', 'orderView' => 'orders', 'productEdit' => 'products', default => null };
        $_GET = [];
        if ($table) {
            $row = DB::fetch("SELECT id FROM $table ORDER BY id LIMIT 1");
            if (!$row) continue;
            $_GET['id'] = $row['id'];
        }
        ob_start();
        $controller->$method();
        $html = ob_get_clean();
        if (substr_count($html, '<!DOCTYPE html>') !== 1) throw new RuntimeException("$method must render once");
        if (getenv('PORTAL_PREVIEW_DIR')) file_put_contents(getenv('PORTAL_PREVIEW_DIR') . '/' . $method . '.html', str_replace(asset(''), 'http://localhost:8001/assets/', $html));
        $count++;
    }
    foreach ([['products', new ProductController()], ['recipes', new RecipeController()]] as [$table, $controller]) {
        $row = DB::fetch("SELECT id, slug FROM $table WHERE " . ($table === 'products' ? 'is_active' : 'is_published') . ' = 1 ORDER BY id LIMIT 1');
        if (!$row) throw new RuntimeException("Need a local $table fixture.");
        $pages = [];
        foreach ([$row['id'], $row['slug']] as $identifier) {
            ob_start();
            $controller->show($identifier);
            $pages[] = ob_get_clean();
            $count++;
        }
        if ($pages[0] !== $pages[1]) throw new RuntimeException("$table ID and slug must render identically.");
    }
    $user = DB::fetch("SELECT u.* FROM users u JOIN wholesale_accounts wa ON wa.user_id = u.id WHERE wa.status = 'approved' LIMIT 1");
    if (!$user) throw new RuntimeException('Need an approved wholesale fixture.');
    login_user($user);
    refresh_wholesale_session();
    $controller = new WholesaleController();
    $_GET = [];
    foreach (['dashboard', 'catalog', 'quickOrder', 'standingOrders', 'editStandingOrder', 'orders', 'invoices'] as $method) {
        ob_start();
        $controller->$method();
        $html = ob_get_clean();
        if (substr_count($html, '<!DOCTYPE html>') !== 1) throw new RuntimeException("Wholesale $method must render once");
        if (getenv('PORTAL_PREVIEW_DIR')) file_put_contents(getenv('PORTAL_PREVIEW_DIR') . '/wholesale-' . $method . '.html', str_replace(asset(''), 'http://localhost:8001/assets/', $html));
        $count++;
    }
} finally {
    if ($pdo->inTransaction()) $pdo->rollBack();
}
echo "$count pages rendered without warnings. Transaction rolled back.\n";
