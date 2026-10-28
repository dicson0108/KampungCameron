<?php
// Run: php tests/cart.php (in-memory data; never connects to the database).
set_error_handler(function ($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});

class DB {
    public static ?array $cart = ['id' => 7, 'promo_code_id' => 1];
    public static array $cartLookup = [];
    public static array $items = [
        ['item_id' => 1, 'product_id' => 1, 'quantity' => 3, 'price' => '12.50', 'sale_price' => '10.00'],
        ['item_id' => 2, 'product_id' => 2, 'quantity' => 2, 'price' => '3.33', 'sale_price' => null],
    ];

    public static function fetchAll(string $sql, array $params = []): array {
        if (str_contains($sql, 'FROM settings')) {
            return [['setting_key' => 'flat_shipping_fee', 'setting_value' => '8.00']];
        }
        if (str_contains($sql, 'JOIN products')) {
            return array_map(fn($item) => $item + [
                'name_en' => 'Broccoli', 'name_zh' => '西兰花', 'slug' => 'broccoli',
                'image' => 'broccoli.png', 'unit_weight' => '500g', 'stock_quantity' => 10,
            ], self::$items);
        }
        throw new RuntimeException('Unexpected query: ' . $sql);
    }

    public static function fetch(string $sql, array $params = []): ?array {
        if (str_contains($sql, 'FROM carts')) {
            self::$cartLookup = $params;
            return self::$cart;
        }
        if (str_contains($sql, 'SUM(quantity)')) return ['total_qty' => array_sum(array_column(self::$items, 'quantity'))];
        if (str_contains($sql, 'FROM promo_codes')) {
            return match ($params['id']) {
                1 => ['id' => 1, 'code' => 'FRESH10', 'discount_type' => 'percentage', 'discount_value' => '10'],
                2 => ['id' => 2, 'code' => 'FIXED', 'discount_type' => 'fixed', 'discount_value' => '100'],
                default => null,
            };
        }
        throw new RuntimeException('Unexpected query: ' . $sql);
    }

    public static function insert(string $table, array $data): int {
        check($table === 'carts', 'Only in-memory cart creation is supported');
        self::$cart = $data + ['id' => 8, 'promo_code_id' => null];
        return 8;
    }
}

require __DIR__ . '/../config/config.php';
require APP_PATH . '/helpers/auth.php';
require APP_PATH . '/helpers/i18n.php';
require APP_PATH . '/helpers/view.php';
require APP_PATH . '/controllers/CartController.php';
require APP_PATH . '/controllers/CheckoutController.php';

function check(bool $condition, string $message): void {
    if (!$condition) throw new RuntimeException($message);
}

foreach ([null, 0, -1, '10.00'] as $sale) {
    foreach ([0, 15, 20, 25] as $discount) {
        $price = $sale > 0 ? (float)$sale : 12.50;
        $expected = $discount > 0 ? round($price * (1 - $discount / 100), 2) : $price;
        check(product_price(['price' => '12.50', 'sale_price' => $sale], $discount) === $expected, 'Sale selection and tier rounding changed');
    }
}

$_SESSION = [];
$items = get_cart_items(7);
check($items[0]['unit_price'] === 10.0, 'Sale price must win');
check(abs(get_cart_subtotal() - 36.66) < 0.00001, 'Retail header subtotal');
$totals = calculate_cart_totals($items, 1);
check($totals['discount'] === 3.67 && abs($totals['total'] - 40.99) < 0.00001, 'Percentage discount and shipping');
check(calculate_cart_totals($items, 2)['total'] === 8.0, 'Fixed discount must cap at subtotal');
check(calculate_cart_totals($items, 999)['discount'] === 0.0, 'Missing promotion');
check(calculate_cart_totals([], null)['total'] === 0.0, 'Empty cart must not charge shipping');

$_SESSION['wholesale'] = ['discount_percent' => 15];
$items = get_cart_items(7);
check($items[1]['unit_price'] === 2.83, 'Round tier price before multiplying quantity');
check(abs(get_cart_subtotal() - 31.16) < 0.00001, 'Wholesale header subtotal');

// Exercise actual cart/checkout templates and layout to catch duplicate output.
foreach ([new CartController(), new CheckoutController()] as $controller) {
    ob_start();
    $controller->index();
    $html = ob_get_clean();
    check(substr_count($html, '<!DOCTYPE html>') === 1, 'Exactly one page must render');
    check(str_contains($html, 'RM 36.04'), 'Page totals must agree with wholesale cart plus promotion and shipping');
}

foreach (['dashboard', 'catalog', 'quick-order', 'standing-orders', 'orders', 'invoices'] as $activeWholesaleTab) {
    ob_start();
    require VIEW_PATH . '/wholesale/subnav.php';
    $html = ob_get_clean();
    check(substr_count($html, '<a ') === 6, 'All wholesale destinations must remain');
    check(substr_count($html, 'wholesale-subnav-link active') === 1, 'Exactly one wholesale tab active');
    check(str_contains($html, url('/wholesale/' . $activeWholesaleTab) . '" class="wholesale-subnav-link active"'), 'Correct wholesale tab active');
}

foreach (['dashboard', 'orders', 'addresses', 'profile', 'wishlist', 'reviews'] as $activeAccountTab) {
    $wishlistCount = 4;
    ob_start();
    require VIEW_PATH . '/account/nav.php';
    $html = ob_get_clean();
    check(substr_count($html, '<a ') === 6 && substr_count($html, 'btn btn-primary') === 1, 'Account tabs retained');
    check(str_contains($html, url('/account/' . $activeAccountTab) . '" class="btn btn-primary"'), 'Correct account tab active');
    check(str_contains($html, 'Wishlist (4)') === ($activeAccountTab === 'dashboard'), 'Dashboard-only wishlist count retained');
}

foreach (['en', 'zh'] as $lang) {
    $_SESSION['lang'] = $lang;
    foreach ([0, 1, 10] as $stock) {
        $prod = [
            'id' => 1, 'slug' => 'broccoli', 'image' => 'broccoli.png', 'name_en' => 'Broccoli', 'name_zh' => '西兰花',
            'unit_weight' => '500g', 'avg_rating' => 4.5, 'review_count' => 3,
            'price' => 12.50, 'sale_price' => 10.0,
            'stock_quantity' => $stock, 'low_stock_threshold' => 2,
        ];
        ob_start();
        require VIEW_PATH . '/shop/product_card.php';
        $html = ob_get_clean();
        check(str_contains($html, $prod['name_' . $lang]), 'Translated product name retained');
        check(str_contains($html, 'RM 10.00'), 'Product card derives its sale price without controller enrichment');
        check(str_contains($html, 'data-ajax-cart') === ($stock > 0), 'Stock controls add-to-cart availability');
        check(str_contains($html, 'badge-' . ($stock === 0 ? 'sold-out' : ($stock <= 2 ? 'low-stock' : 'in-stock'))), 'Stock badge retained');
    }
}

foreach ([null, 12] as $userId) {
    $_SESSION = $userId ? ['user' => ['id' => $userId]] : [];
    DB::$cart = null;
    $cart = get_active_cart();
    check($cart['user_id'] === $userId && $cart['session_id'] === session_id(), 'New cart belongs to correct user/session');
    get_active_cart();
    check(DB::$cartLookup === ($userId ? ['uid' => $userId] : ['sid' => session_id()]), 'Existing cart lookup remains scoped');
}
foreach (['', '/', '/shop', '///shop', '/shop/'] as $path) {
    check(url($path) === BASE_URL . '/' . ltrim($path, '/'), 'Base URL and slash handling');
}

echo "Cart money calculations, guest/user carts, single-page rendering, navigation, and bilingual stock cards passed.\n";
