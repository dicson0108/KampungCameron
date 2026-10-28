<?php
// Run: php tests/portals.php. In-memory DB; no live records touched.
require __DIR__ . '/../config/config.php';
require APP_PATH . '/helpers/auth.php';
require APP_PATH . '/controllers/WholesaleController.php';
require APP_PATH . '/controllers/AdminController.php';
require APP_PATH . '/controllers/CheckoutController.php';
set_error_handler(fn($n, $s, $f, $l) => throw new ErrorException($s, 0, $n, $f, $l));
class Navigation extends RuntimeException {}
function redirect(string $path): void { throw new Navigation($path); }
function set_flash(string $type, string $message): void { $GLOBALS['flash'] = [$type, $message]; }
function __(string $key, string $fallback): string { return $fallback; }
function render_view(string $view, array $data = [], string $layout = 'main'): void { $GLOBALS['view'] = [$view, $data]; }
function get_active_cart(): array { return ['id' => 7]; }
function get_checkout_payment_methods(): array { return ['cod' => 'Cash on delivery']; }
function product_price(array $p, float $discount = 0): float { return 4.25; } // Sentinel: controller must call shared pricing.
function check(bool $ok, string $message): void { if (!$ok) throw new RuntimeException($message); }
function action(callable $fn): void { try { $fn(); } catch (Navigation $e) {} }
class DB {
    public static array $writes = [];
    public static array $queries = [];
    public static ?string $failInsert = null;
    public static ?string $failSetting = null;
    public static array $account = ['id' => 3, 'user_id' => 2, 'status' => 'approved', 'discount_percent' => 15, 'business_name' => 'Kitchen'];
    public static ?array $standing = null;
    public static array $unavailable = [];
    public static array $order = ['id' => 9, 'status' => 'cancelled', 'order_number' => 'WH-9'];
    public static object $pdo;
    public static function connect(): object { return self::$pdo; }
    public static function fetch(string $sql, array $params = []): ?array {
        self::$queries[] = $sql;
        if (str_contains($sql, 'FROM wholesale_accounts')) return self::$account;
        if (str_contains($sql, 'FROM standing_orders')) return self::$standing;
        if (str_contains($sql, 'FROM users')) return ['id' => 99, 'role' => 'customer'];
        if (str_contains($sql, 'FROM pricing_tiers')) return ($params['id'] ?? 0) === 1 ? ['id' => 1] : null;
        if (str_contains($sql, 'FROM products')) return ['id' => 1, 'stock_quantity' => 10, 'is_active' => 1];
        if (str_contains($sql, 'FROM cart_items')) return ['id' => 5, 'quantity' => 4];
        if (str_contains($sql, 'FROM orders')) return self::$order;
        throw new RuntimeException('Unexpected query: ' . $sql);
    }
    public static function fetchAll(string $sql, array $params = []): array {
        if (str_contains($sql, 'FROM standing_order_items')) return self::$unavailable;
        if (str_contains($sql, 'FROM products')) return [['id' => 1, 'price' => 12, 'sale_price' => 5, 'stock_quantity' => 10]];
        if (str_contains($sql, 'FROM pricing_tiers')) return [['id' => 1, 'name' => 'A']];
        return [];
    }
    public static function update(string $table, array $data, string $where, array $params = []): int {
        if ($table === 'settings' && ($params['k'] ?? '') === self::$failSetting) throw new RuntimeException('Simulated settings failure');
        self::$writes[] = [$table, $data]; return 1;
    }
    public static function insert(string $table, array $data): int {
        if (self::$failInsert === $table) throw new RuntimeException('Simulated DB failure');
        self::$writes[] = [$table, $data]; return 8;
    }
    public static function delete(string $table, string $where, array $params = []): int { self::$writes[] = [$table, 'delete']; return 1; }
    public static function query(string $sql, array $params = []): void { self::$writes[] = [$sql, $params]; }
}
DB::$pdo = new class {
    public bool $active = false;
    public array $snapshot = [];
    public function beginTransaction(): void { $this->snapshot = DB::$writes; $this->active = true; }
    public function commit(): void { $this->active = false; }
    public function rollBack(): void { DB::$writes = $this->snapshot; $this->active = false; }
    public function inTransaction(): bool { return $this->active; }
};
$_SESSION = ['user' => ['id' => 2, 'role' => 'wholesale'], 'csrf_token' => 'test'];
$_POST = ['csrf_token' => 'test'];
$wholesale = new WholesaleController();
check(method_exists(AdminController::class, 'wholesaleApplicationDetail'), 'Application detail route must exist');
action(fn() => $wholesale->catalog());
check($GLOBALS['view'][1]['products'][0]['wholesale_price'] === 4.25, 'Catalogue must reuse checkout pricing');
$_POST += ['items' => [1 => 3]];
action(fn() => $wholesale->submitQuickOrder());
check(DB::$writes[0][1]['quantity'] === 7, 'Bulk add must preserve existing cart quantity');
DB::$writes = [];
$_POST = ['csrf_token' => 'test', 'standing_order_id' => 99, 'schedule_name' => 'Weekly', 'delivery_address' => 'Address', 'weekly_delivery_day' => 'Monday', 'start_date' => '2026-11-02', 'products' => [1 => 2]];
action(fn() => $wholesale->saveStandingOrder());
check(DB::$writes === [], 'Foreign standing order must not alter parent or items');
$_POST['standing_order_id'] = 0;
$_POST['weekly_delivery_day'] = 'Invalid';
action(fn() => $wholesale->saveStandingOrder());
check(DB::$writes === [], 'Invalid schedule day must not write');
$_POST['weekly_delivery_day'] = 'Monday';
$_POST['products'] = [];
action(fn() => $wholesale->saveStandingOrder());
check(DB::$writes === [], 'Empty schedule must not write');
DB::$standing = ['id' => 99];
DB::$unavailable = [['id' => 2]];
$_POST['standing_order_id'] = 99;
$_POST['products'] = [1 => 3];
action(fn() => $wholesale->saveStandingOrder());
check(DB::$writes === [], 'Unavailable scheduled products must require explicit removal');
DB::$standing = null;
DB::$unavailable = [];
$_POST['standing_order_id'] = 0;
$_POST['products'] = [1 => 3];
action(fn() => $wholesale->saveStandingOrder());
check(count(DB::$writes) === 2 && DB::$writes[1][1]['quantity'] === 3, 'Valid schedule must save parent and selected quantities');
check(!DB::$pdo->inTransaction(), 'Successful schedule must commit');
DB::$writes = [];
DB::$failInsert = 'standing_order_items';
action(fn() => $wholesale->saveStandingOrder());
check(DB::$writes === [] && !DB::$pdo->inTransaction(), 'Item insertion failure must roll back schedule');
DB::$failInsert = null;
refresh_wholesale_session();
check($_SESSION['wholesale']['discount_percent'] === 15, 'Refresh must load current pricing tier');
DB::$account['status'] = 'rejected';
refresh_wholesale_session();
check(!isset($_SESSION['wholesale']) && !is_wholesale(), 'Rejected account must lose wholesale access and prices');
DB::$account['status'] = 'approved';
$_SESSION['user']['role'] = 'admin';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/admin/wholesale';
$admin = new AdminController();
$_POST = ['app_id' => 3, 'action' => 'approve', 'tier_id' => 999];
action(fn() => $admin->wholesaleApplicationAction());
check(DB::$writes === [], 'Unknown tier must not approve account');
$_POST = ['app_id' => 3, 'action' => 'approve', 'tier_id' => 1];
action(fn() => $admin->wholesaleApplicationAction());
check(count(DB::$writes) === 2 && DB::$writes[0][1]['status'] === 'approved' && DB::$writes[1][1]['role'] === 'wholesale', 'Approval must update both account and user');
DB::$writes = [];
$_POST['action'] = 'reject';
action(fn() => $admin->wholesaleApplicationAction());
check(count(DB::$writes) === 2 && DB::$writes[1][1]['role'] === 'customer', 'Rejection must revoke wholesale role');
DB::$writes = [];
$_POST = ['id' => 9, 'status' => 'processing'];
action(fn() => $admin->orderUpdateStatus());
check(DB::$writes === [], 'Cancelled order cannot reopen without reserving stock');
$_POST = ['product_id' => 1, 'change_quantity' => -20, 'reason' => 'count'];
action(fn() => $admin->inventoryAdjust());
check(DB::$writes === [], 'Inventory adjustment cannot silently clamp negative balance');
$_POST = ['name' => 'Bad', 'code' => 'B', 'discount_percent' => 150];
action(fn() => $admin->pricingTierSave());
check(DB::$writes === [], 'Invalid discount must not write');
$_POST = ['name_en' => 'Produce', 'price' => -5, 'stock_quantity' => 10];
action(fn() => $admin->productSave());
check(DB::$writes === [], 'Negative product price must not write');
$_POST = ['code' => 'BAD', 'discount_type' => 'percentage', 'discount_value' => 150];
action(fn() => $admin->promotionSave());
check(DB::$writes === [], 'Percentage promotion cannot exceed 100');
$_POST = ['settings' => ['flat_shipping_fee' => '-8']];
action(fn() => $admin->settingsSave());
check(DB::$writes === [], 'Negative shipping fee must not write');
foreach ([['contact_email' => 'bad-email'], ['store_name' => ' '], ['enabled_payment_methods' => 'bitcoin'], ['enabled_payment_methods' => '']] as $badSettings) {
    $_POST = ['settings' => $badSettings];
    action(fn() => $admin->settingsSave());
    check(DB::$writes === [], 'Invalid settings must not write');
}
$_POST = ['settings' => ['store_name' => ' Updated Store ', 'contact_phone' => '+60 12 345 6789']];
DB::$failSetting = 'contact_phone';
action(fn() => $admin->settingsSave());
check(DB::$writes === [] && !DB::$pdo->inTransaction(), 'Settings batch failure must roll back earlier changes');
DB::$failSetting = null;
action(fn() => $admin->settingsSave());
check(count(DB::$writes) === 2 && DB::$writes[0][1]['setting_value'] === 'Updated Store', 'Valid settings batch must save trimmed values');
DB::$writes = [];
$_POST = ['csrf_token' => 'test', 'payment_method' => 'fpx', 'full_name' => 'Customer', 'email' => 'customer@example.com', 'phone' => '0123456789', 'address_line1' => 'Address', 'city' => 'City', 'state' => 'State', 'postcode' => '12345'];
action(fn() => (new CheckoutController())->process());
check(DB::$writes === [] && str_contains($GLOBALS['flash'][1], 'available payment method'), 'Checkout must reject a disabled payment method before creating an order');
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST = [];
$denied = false;
try { new AdminController(); } catch (Navigation $e) { $denied = true; }
check($denied, 'Admin POST must require CSRF');
$_SESSION = [];
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST = ['csrf_token' => 'bad', 'business_name' => 'Kitchen', 'registration_number' => 'SSM', 'contact_person' => 'Chef', 'contact_email' => 'existing@example.com', 'contact_phone' => '123', 'password' => 'not-the-password'];
action(fn() => (new WholesaleController())->submitApplication());
check(DB::$writes === [], 'Guest cannot change an existing account via application');
$_SESSION['csrf_token'] = 'test';
$_POST['csrf_token'] = 'test';
action(fn() => (new WholesaleController())->submitApplication());
check(DB::$writes === [], 'Valid CSRF must not let a guest overwrite another account');
echo "Portal regression checks passed.\n";
