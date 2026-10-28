<?php
// Front Controller & Dispatcher

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../app/helpers/auth.php';
require_once __DIR__ . '/../app/helpers/i18n.php';
require_once __DIR__ . '/../app/helpers/view.php';

refresh_wholesale_session();

// Auto-load controllers
spl_autoload_register(function ($class) {
    $file = APP_PATH . '/controllers/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Resolve URI route
$uri = $_GET['route'] ?? $_SERVER['PATH_INFO'] ?? '';

if (empty($uri)) {
    $reqUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $reqUri = str_replace('\\', '/', $reqUri);

    // Strip BASE_URL prefix from request URI to get clean route
    if (defined('BASE_URL') && BASE_URL !== '' && str_starts_with($reqUri, BASE_URL)) {
        $uri = substr($reqUri, strlen(BASE_URL));
    } else {
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        $scriptDir = preg_replace('#/public$#', '', $scriptDir);

        if ($scriptDir !== '/' && $scriptDir !== '' && str_starts_with($reqUri, $scriptDir)) {
            $uri = substr($reqUri, strlen($scriptDir));
        } else {
            $uri = $reqUri;
        }
    }

    // Strip /public if user directly requested with /public/ in the URL
    if (str_starts_with($uri, '/public')) {
        $uri = substr($uri, strlen('/public'));
    }
}

$uri = '/' . trim($uri, '/');
if ($uri === '//') $uri = '/';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Dispatch routing table
switch (true) {
    // Storefront Home
    case $uri === '/':
        (new HomeController())->index();
        break;

    // Shop & Catalogue
    case $uri === '/shop':
        (new ShopController())->index();
        break;
    case str_starts_with($uri, '/shop/category/'):
        $slug = substr($uri, strlen('/shop/category/'));
        (new ShopController())->category($slug);
        break;
    case $uri === '/category':
        $slug = $_GET['slug'] ?? '';
        (new ShopController())->category($slug);
        break;
    case $uri === '/api/search':
        (new ShopController())->searchSuggestions();
        break;

    // Product Detail
    case str_starts_with($uri, '/product/'):
        $slug = substr($uri, strlen('/product/'));
        (new ProductController())->show($slug);
        break;
    case $uri === '/product':
        $id = (int)($_GET['id'] ?? 0);
        $slug = $_GET['slug'] ?? '';
        (new ProductController())->show($slug ?: $id);
        break;
    case $uri === '/reviews/submit' && $method === 'POST':
        (new ProductController())->submitReview();
        break;

    // Cart & Checkout
    case $uri === '/cart':
        (new CartController())->index();
        break;
    case $uri === '/cart/add' && $method === 'POST':
        (new CartController())->add();
        break;
    case $uri === '/cart/update' && $method === 'POST':
        (new CartController())->update();
        break;
    case $uri === '/cart/remove' && $method === 'POST':
        (new CartController())->remove();
        break;
    case $uri === '/cart/apply-promo' && $method === 'POST':
        (new CartController())->applyPromo();
        break;
    case $uri === '/api/mini-cart':
        (new CartController())->miniCart();
        break;
    case $uri === '/checkout':
        (new CheckoutController())->index();
        break;
    case $uri === '/checkout/process' && $method === 'POST':
        (new CheckoutController())->process();
        break;
    case $uri === '/checkout/success':
        (new CheckoutController())->success();
        break;

    // Wishlist
    case $uri === '/wishlist/toggle' && $method === 'POST':
        (new AccountController())->toggleWishlist();
        break;

    // Recipes Hub
    case $uri === '/recipes':
        (new RecipeController())->index();
        break;
    case str_starts_with($uri, '/recipes/'):
        $slug = substr($uri, strlen('/recipes/'));
        (new RecipeController())->show($slug);
        break;
    case $uri === '/recipe':
        $slug = $_GET['slug'] ?? '';
        (new RecipeController())->show($slug);
        break;
    case $uri === '/recipes-add-all' && $method === 'POST':
        (new RecipeController())->addAllToCart();
        break;

    // Wholesale Portal (B2B)
    case $uri === '/wholesale':
        (new WholesaleController())->landing();
        break;
    case $uri === '/wholesale/apply':
        if ($method === 'POST') {
            (new WholesaleController())->submitApplication();
        } else {
            (new WholesaleController())->apply();
        }
        break;
    case $uri === '/wholesale/dashboard':
        (new WholesaleController())->dashboard();
        break;
    case $uri === '/wholesale/catalog':
        (new WholesaleController())->catalog();
        break;
    case $uri === '/wholesale/quick-order':
        (new WholesaleController())->quickOrder();
        break;
    case $uri === '/wholesale/quick-order/submit' && $method === 'POST':
        (new WholesaleController())->submitQuickOrder();
        break;
    case $uri === '/wholesale/standing-orders':
        (new WholesaleController())->standingOrders();
        break;
    case $uri === '/wholesale/standing-orders/edit':
        (new WholesaleController())->editStandingOrder();
        break;
    case $uri === '/wholesale/standing-orders/save' && $method === 'POST':
        (new WholesaleController())->saveStandingOrder();
        break;
    case $uri === '/wholesale/standing-orders/status' && $method === 'POST':
        (new WholesaleController())->updateStandingOrderStatus();
        break;
    case $uri === '/wholesale/orders':
        (new WholesaleController())->orders();
        break;
    case $uri === '/wholesale/invoices':
        (new WholesaleController())->invoices();
        break;
    case str_starts_with($uri, '/wholesale/invoice/'):
        $id = (int)substr($uri, strlen('/wholesale/invoice/'));
        (new WholesaleController())->invoiceDetail($id);
        break;

    // Customer Account
    case $uri === '/account' || $uri === '/account/dashboard':
        (new AccountController())->dashboard();
        break;
    case $uri === '/account/login':
        if ($method === 'POST') {
            (new AccountController())->doLogin();
        } else {
            (new AccountController())->login();
        }
        break;
    case $uri === '/account/register':
        if ($method === 'POST') {
            (new AccountController())->doRegister();
        } else {
            (new AccountController())->register();
        }
        break;
    case $uri === '/account/logout':
        (new AccountController())->logout();
        break;
    case $uri === '/account/orders':
        (new AccountController())->orders();
        break;
    case str_starts_with($uri, '/account/orders/'):
        $id = (int)substr($uri, strlen('/account/orders/'));
        (new AccountController())->orderDetail($id);
        break;
    case $uri === '/account/addresses':
        if ($method === 'POST') {
            (new AccountController())->saveAddress();
        } else {
            (new AccountController())->addresses();
        }
        break;
    case $uri === '/account/profile':
        if ($method === 'POST') {
            (new AccountController())->saveProfile();
        } else {
            (new AccountController())->profile();
        }
        break;
    case $uri === '/account/wishlist':
        (new AccountController())->wishlist();
        break;
    case $uri === '/account/reviews':
        (new AccountController())->reviews();
        break;

    // Informational Static Pages
    case $uri === '/about':
        (new PageController())->about();
        break;
    case $uri === '/contact':
        (new PageController())->contact();
        break;
    case $uri === '/terms':
        (new PageController())->terms();
        break;
    case $uri === '/privacy':
        (new PageController())->privacy();
        break;
    case $uri === '/shipping':
        (new PageController())->shipping();
        break;
    case $uri === '/return-policy':
        (new PageController())->returnPolicy();
        break;

    // Admin Back-Office
    case $uri === '/admin' || $uri === '/admin/dashboard':
        (new AdminController())->dashboard();
        break;
    case $uri === '/admin/login':
        if ($method === 'POST') {
            (new AdminController())->doLogin();
        } else {
            (new AdminController())->login();
        }
        break;
    case $uri === '/admin/logout':
        (new AdminController())->logout();
        break;
    case $uri === '/admin/products':
        (new AdminController())->products();
        break;
    case $uri === '/admin/products/create':
        (new AdminController())->productCreate();
        break;
    case $uri === '/admin/products/edit':
        (new AdminController())->productEdit();
        break;
    case $uri === '/admin/products/save' && $method === 'POST':
        (new AdminController())->productSave();
        break;
    case $uri === '/admin/products/delete' && $method === 'POST':
        (new AdminController())->productDelete();
        break;
    case $uri === '/admin/categories':
        (new AdminController())->categories();
        break;
    case $uri === '/admin/categories/save' && $method === 'POST':
        (new AdminController())->categorySave();
        break;
    case $uri === '/admin/inventory':
        (new AdminController())->inventory();
        break;
    case $uri === '/admin/inventory/adjust' && $method === 'POST':
        (new AdminController())->inventoryAdjust();
        break;
    case $uri === '/admin/orders':
        (new AdminController())->orders();
        break;
    case $uri === '/admin/orders/view':
        (new AdminController())->orderView();
        break;
    case $uri === '/admin/orders/status' && $method === 'POST':
        (new AdminController())->orderUpdateStatus();
        break;
    case $uri === '/admin/orders/invoice':
        (new AdminController())->orderInvoice();
        break;
    case $uri === '/admin/customers':
        (new AdminController())->customers();
        break;
    case $uri === '/admin/wholesale':
        (new AdminController())->wholesaleApplications();
        break;
    case $uri === '/admin/wholesale/application':
        (new AdminController())->wholesaleApplicationDetail();
        break;
    case $uri === '/admin/wholesale/application/action' && $method === 'POST':
        (new AdminController())->wholesaleApplicationAction();
        break;
    case $uri === '/admin/wholesale/tiers':
        (new AdminController())->pricingTiers();
        break;
    case $uri === '/admin/wholesale/tiers/save' && $method === 'POST':
        (new AdminController())->pricingTierSave();
        break;
    case $uri === '/admin/wholesale/standing-orders':
        (new AdminController())->adminStandingOrders();
        break;
    case $uri === '/admin/promotions':
        (new AdminController())->promotions();
        break;
    case $uri === '/admin/promotions/save' && $method === 'POST':
        (new AdminController())->promotionSave();
        break;
    case $uri === '/admin/reviews':
        (new AdminController())->reviews();
        break;
    case $uri === '/admin/reviews/action' && $method === 'POST':
        (new AdminController())->reviewAction();
        break;
    case $uri === '/admin/recipes':
        (new AdminController())->recipes();
        break;
    case $uri === '/admin/recipes/edit':
        (new AdminController())->recipeEdit();
        break;
    case $uri === '/admin/recipes/save' && $method === 'POST':
        (new AdminController())->recipeSave();
        break;
    case $uri === '/admin/reports':
        (new AdminController())->reports();
        break;
    case $uri === '/admin/reports/export':
        (new AdminController())->exportCsv();
        break;
    case $uri === '/admin/settings':
        (new AdminController())->settings();
        break;
    case $uri === '/admin/settings/save' && $method === 'POST':
        (new AdminController())->settingsSave();
        break;

    default:
        http_response_code(404);
        render_view('pages/404', ['title' => 'Page Not Found']);
        break;
}
