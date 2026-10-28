<?php
// View and Template Helpers

function e(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function render_view(string $viewPath, array $data = [], string $layout = 'main'): void {
    extract($data);
    
    // View output buffer
    $viewFile = VIEW_PATH . '/' . $viewPath . '.php';
    if (!file_exists($viewFile)) {
        die("View not found: " . htmlspecialchars($viewPath));
    }
    
    ob_start();
    require $viewFile;
    $content = ob_get_clean();
    
    if ($layout === 'none') {
        echo $content;
        return;
    }
    
    $layoutFile = VIEW_PATH . '/layouts/' . $layout . '.php';
    if (!file_exists($layoutFile)) {
        die("Layout not found: " . htmlspecialchars($layout));
    }
    
    require $layoutFile;
}

function redirect(string $path): void {
    $target = url($path);
    header("Location: $target");
    exit;
}

function url(string $path = ''): string {
    return BASE_URL . '/' . ltrim($path, '/');
}

function asset(string $path): string {
    return BASE_URL . '/assets/' . ltrim($path, '/');
}

function format_currency(float|int|string|null $amount): string {
    $num = (float)($amount ?? 0);
    return CURRENCY_SYMBOL . ' ' . number_format($num, 2);
}

function set_flash(string $type, string $message): void {
    $_SESSION['flash'][$type][] = $message;
}

function get_flashes(): array {
    $flashes = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $flashes;
}

function get_setting(string $key, string $default = ''): string {
    static $settings = null;
    if ($settings === null) {
        $rows = DB::fetchAll("SELECT setting_key, setting_value FROM settings");
        $settings = array_column($rows, 'setting_value', 'setting_key');
    }
    return $settings[$key] ?? $default;
}

function get_checkout_payment_methods(): array {
    $labels = ['fpx' => 'FPX online banking', 'card' => 'Credit / debit card — Visa, Mastercard', 'grabpay' => 'GrabPay', 'tng' => 'Touch ’n Go eWallet', 'cod' => 'Cash on delivery'];
    $enabled = array_flip(array_map('trim', explode(',', get_setting('enabled_payment_methods', implode(',', array_keys($labels))))));
    return array_intersect_key($labels, $enabled);
}

function get_cart_count(): int {
    $cart = get_active_cart();
    if (!$cart) return 0;
    $row = DB::fetch("SELECT SUM(quantity) as total_qty FROM cart_items WHERE cart_id = :cid", ['cid' => $cart['id']]);
    return (int)($row['total_qty'] ?? 0);
}

function product_price(array $product, float $discount = 0): float {
    $price = ($product['sale_price'] !== null && $product['sale_price'] > 0)
        ? (float)$product['sale_price'] : (float)$product['price'];
    return $discount > 0 ? round($price * (1 - $discount / 100), 2) : $price;
}

function get_cart_subtotal(): float {
    $cart = get_active_cart();
    return $cart ? (float)array_sum(array_column(get_cart_items($cart['id']), 'line_total')) : 0.00;
}

function get_cart_items(int $cartId): array {
    $rows = DB::fetchAll(
        "SELECT ci.id as item_id, ci.quantity, p.id as product_id, p.name_en, p.name_zh, p.unit_weight,
                p.price, p.sale_price, p.stock_quantity, p.image, p.slug
         FROM cart_items ci
         JOIN products p ON ci.product_id = p.id
         WHERE ci.cart_id = :cid
         ORDER BY ci.id ASC",
        ['cid' => $cartId]
    );

    $wsDiscount = $_SESSION['wholesale']['discount_percent'] ?? 0;
    foreach ($rows as &$r) {
        $base = product_price($r, $wsDiscount);
        $r['unit_price'] = $base;
        $r['line_total'] = $base * $r['quantity'];
    }
    return $rows;
}

function calculate_cart_totals(array $items, ?int $promoCodeId): array {
    $subtotal = array_sum(array_column($items, 'line_total'));

    $discount = 0.00;
    $promo = null;
    if ($promoCodeId) {
        $promo = DB::fetch("SELECT * FROM promo_codes WHERE id = :id", ['id' => $promoCodeId]);
        if ($promo) {
            if ($promo['discount_type'] === 'percentage') {
                $discount = round($subtotal * ((float)$promo['discount_value'] / 100), 2);
            } else {
                $discount = min($subtotal, (float)$promo['discount_value']);
            }
        }
    }

    $shipping = (float)get_setting('flat_shipping_fee', '8.00');
    $total = max(0.00, $subtotal - $discount) + ($items ? $shipping : 0.00);

    return [
        'subtotal' => $subtotal,
        'discount' => $discount,
        'shipping' => $shipping,
        'total'    => $total,
        'promo'    => $promo
    ];
}

function get_active_cart(): ?array {
    $sessionId = session_id();
    $userId = auth_id();

    $where = $userId ? 'user_id = :uid' : 'session_id = :sid AND user_id IS NULL';
    $params = $userId ? ['uid' => $userId] : ['sid' => $sessionId];
    $cart = DB::fetch("SELECT * FROM carts WHERE $where ORDER BY id DESC LIMIT 1", $params);
    if (!$cart) {
        $cid = DB::insert('carts', ['user_id' => $userId, 'session_id' => $sessionId]);
        $cart = DB::fetch("SELECT * FROM carts WHERE id = :id", ['id' => $cid]);
    }
    return $cart;
}

function get_wishlist_count(): int {
    if (!is_logged_in()) return 0;
    $row = DB::fetch("SELECT COUNT(*) as cnt FROM wishlist WHERE user_id = :uid", ['uid' => auth_id()]);
    return (int)($row['cnt'] ?? 0);
}

function is_in_wishlist(int $productId): bool {
    if (!is_logged_in()) return false;
    $row = DB::fetch("SELECT id FROM wishlist WHERE user_id = :uid AND product_id = :pid", [
        'uid' => auth_id(),
        'pid' => $productId
    ]);
    return !empty($row);
}
