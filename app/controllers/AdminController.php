<?php

class AdminController {
    public function __construct() {
        // Enforce admin login for all routes except /admin/login
        $uri = $_GET['route'] ?? $_SERVER['PATH_INFO'] ?? parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
        if (!str_contains($uri, '/admin/login') && !is_admin()) {
            redirect('/admin/login');
        }
    }

    public function login(): void {
        if (is_admin()) {
            redirect('/admin/dashboard');
        }
        render_view('admin/login', ['title' => 'Admin Sign In — Kampung Cameron'], 'none');
    }

    public function doLogin(): void {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $user = DB::fetch("SELECT * FROM users WHERE email = :email AND role = 'admin'", ['email' => $email]);
        if (!$user || !password_verify($password, $user['password_hash'])) {
            set_flash('error', 'Invalid administrator credentials.');
            redirect('/admin/login');
        }

        login_user($user);
        redirect('/admin/dashboard');
    }

    public function logout(): void {
        logout_user();
        redirect('/admin/login');
    }

    public function dashboard(): void {
        // Sales today
        $salesToday = (float)(DB::fetch(
            "SELECT SUM(total_amount) as total FROM orders WHERE DATE(created_at) = CURDATE() AND status != 'cancelled'"
        )['total'] ?? 0);

        // Orders today
        $ordersToday = (int)(DB::fetch(
            "SELECT COUNT(*) as cnt FROM orders WHERE DATE(created_at) = CURDATE()"
        )['cnt'] ?? 0);

        $pendingProcessing = (int)(DB::fetch(
            "SELECT COUNT(*) as cnt FROM orders WHERE status = 'pending'"
        )['cnt'] ?? 0);

        // New customers this week
        $newCustomers = (int)(DB::fetch(
            "SELECT COUNT(*) as cnt FROM users WHERE role = 'customer' AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)"
        )['cnt'] ?? 0);

        // Pending B2B applications
        $pendingB2B = (int)(DB::fetch(
            "SELECT COUNT(*) as cnt FROM wholesale_accounts WHERE status = 'pending'"
        )['cnt'] ?? 0);

        // Recent orders
        $recentOrders = DB::fetchAll(
            "SELECT * FROM orders ORDER BY id DESC LIMIT 5"
        );

        // Low stock alerts
        $lowStockProducts = DB::fetchAll(
            "SELECT * FROM products WHERE stock_quantity <= low_stock_threshold AND is_active = 1 ORDER BY stock_quantity ASC"
        );

        render_view('admin/dashboard', [
            'salesToday'        => $salesToday,
            'ordersToday'       => $ordersToday,
            'pendingProcessing' => $pendingProcessing,
            'newCustomers'      => $newCustomers,
            'pendingB2B'        => $pendingB2B,
            'recentOrders'      => $recentOrders,
            'lowStockProducts'  => $lowStockProducts,
            'title'             => 'Store Dashboard — Administration'
        ], 'admin');
    }

    public function products(): void {
        $products = DB::fetchAll(
            "SELECT p.*, c.name_en as category_name
             FROM products p
             JOIN categories c ON p.category_id = c.id
             ORDER BY p.id DESC"
        );

        render_view('admin/products', [
            'products' => $products,
            'title'    => 'Manage Products — Back-office'
        ], 'admin');
    }

    public function productCreate(): void {
        $categories = DB::fetchAll("SELECT * FROM categories ORDER BY name_en ASC");
        render_view('admin/product_editor', [
            'product'    => null,
            'categories' => $categories,
            'title'      => 'New Product'
        ], 'admin');
    }

    public function productEdit(): void {
        $id = (int)($_GET['id'] ?? 0);
        $product = DB::fetch("SELECT * FROM products WHERE id = :id", ['id' => $id]);
        if (!$product) {
            redirect('/admin/products');
        }

        $categories = DB::fetchAll("SELECT * FROM categories ORDER BY name_en ASC");
        render_view('admin/product_editor', [
            'product'    => $product,
            'categories' => $categories,
            'title'      => 'Edit Product — ' . $product['name_en']
        ], 'admin');
    }

    public function productSave(): void {
        $id          = (int)($_POST['id'] ?? 0);
        $nameEn      = trim($_POST['name_en'] ?? '');
        $nameZh      = trim($_POST['name_zh'] ?? '');
        $categoryId  = (int)($_POST['category_id'] ?? 1);
        $sku         = trim($_POST['sku'] ?? 'KC-' . rand(100, 999));
        $unit        = trim($_POST['unit_weight'] ?? '500g');
        $price       = (float)($_POST['price'] ?? 0);
        $salePrice   = !empty($_POST['sale_price']) ? (float)$_POST['sale_price'] : null;
        $stock       = (int)($_POST['stock_quantity'] ?? 0);
        $threshold   = (int)($_POST['low_stock_threshold'] ?? 5);
        $descEn      = trim($_POST['description_en'] ?? '');
        $descZh      = trim($_POST['description_zh'] ?? '');
        $isBest      = !empty($_POST['is_best_seller']) ? 1 : 0;
        $isActive    = !empty($_POST['is_active']) ? 1 : 0;

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $nameEn)));

        // Handle image upload
        $image = $_POST['existing_image'] ?? 'harvest.png';
        if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['png', 'jpg', 'jpeg', 'webp'])) {
                $imgName = 'prod_' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], PUBLIC_PATH . '/assets/images/' . $imgName)) {
                    $image = $imgName;
                }
            }
        }

        $data = [
            'category_id'         => $categoryId,
            'sku'                 => $sku,
            'slug'                => $slug,
            'name_en'             => $nameEn,
            'name_zh'             => $nameZh,
            'unit_weight'         => $unit,
            'price'               => $price,
            'sale_price'          => $salePrice,
            'stock_quantity'      => $stock,
            'low_stock_threshold' => $threshold,
            'description_en'      => $descEn,
            'description_zh'      => $descZh,
            'image'               => $image,
            'is_best_seller'      => $isBest,
            'is_active'           => $isActive
        ];

        if ($id > 0) {
            DB::update('products', $data, 'id = :id', ['id' => $id]);
            set_flash('success', 'Product updated successfully.');
        } else {
            DB::insert('products', $data);
            set_flash('success', 'Product created successfully.');
        }

        redirect('/admin/products');
    }

    public function productDelete(): void {
        $id = (int)($_POST['id'] ?? 0);
        DB::update('products', ['is_active' => 0], 'id = :id', ['id' => $id]);
        set_flash('success', 'Product archived.');
        redirect('/admin/products');
    }

    public function categories(): void {
        $categories = DB::fetchAll(
            "SELECT c.*, (SELECT COUNT(*) FROM products WHERE category_id = c.id) as product_count
             FROM categories c
             ORDER BY c.sort_order ASC"
        );

        render_view('admin/categories', [
            'categories' => $categories,
            'title'      => 'Product Categories'
        ], 'admin');
    }

    public function categorySave(): void {
        $id       = (int)($_POST['id'] ?? 0);
        $nameEn   = trim($_POST['name_en'] ?? '');
        $nameZh   = trim($_POST['name_zh'] ?? '');
        $descEn   = trim($_POST['description_en'] ?? '');
        $descZh   = trim($_POST['description_zh'] ?? '');
        $sort     = (int)($_POST['sort_order'] ?? 0);
        $slug     = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $nameEn)));

        $data = [
            'slug'           => $slug,
            'name_en'        => $nameEn,
            'name_zh'        => $nameZh,
            'description_en' => $descEn,
            'description_zh' => $descZh,
            'sort_order'     => $sort
        ];

        if ($id > 0) {
            DB::update('categories', $data, 'id = :id', ['id' => $id]);
            set_flash('success', 'Category updated.');
        } else {
            DB::insert('categories', $data);
            set_flash('success', 'Category added.');
        }

        redirect('/admin/categories');
    }

    public function inventory(): void {
        $products = DB::fetchAll(
            "SELECT p.*, c.name_en as category_name
             FROM products p
             JOIN categories c ON p.category_id = c.id
             ORDER BY p.stock_quantity ASC"
        );

        $stockLogs = DB::fetchAll(
            "SELECT sl.*, p.name_en as product_name
             FROM stock_log sl
             JOIN products p ON sl.product_id = p.id
             ORDER BY sl.id DESC
             LIMIT 50"
        );

        render_view('admin/inventory', [
            'products'  => $products,
            'stockLogs' => $stockLogs,
            'title'     => 'Stock Inventory & Audit Log'
        ], 'admin');
    }

    public function inventoryAdjust(): void {
        $productId = (int)($_POST['product_id'] ?? 0);
        $changeQty = (int)($_POST['change_quantity'] ?? 0);
        $reason    = trim($_POST['reason'] ?? 'manual_restock');

        if ($productId > 0 && $changeQty != 0) {
            $prod = DB::fetch("SELECT stock_quantity FROM products WHERE id = :id", ['id' => $productId]);
            if ($prod) {
                $newBalance = max(0, $prod['stock_quantity'] + $changeQty);
                DB::update('products', ['stock_quantity' => $newBalance], 'id = :id', ['id' => $productId]);
                DB::insert('stock_log', [
                    'product_id'   => $productId,
                    'change_qty'   => $changeQty,
                    'balance_qty'  => $newBalance,
                    'reason'       => $reason,
                    'reference_id' => 'ADMIN-ADJUST',
                    'created_by'   => auth_user()['name'] ?? 'admin'
                ]);
                set_flash('success', 'Inventory adjusted.');
            }
        }

        redirect('/admin/inventory');
    }

    public function orders(): void {
        $status = $_GET['status'] ?? null;
        $sql = "SELECT * FROM orders";
        $params = [];
        if ($status) {
            $sql .= " WHERE status = :st";
            $params['st'] = $status;
        }
        $sql .= " ORDER BY id DESC";
        $orders = DB::fetchAll($sql, $params);

        render_view('admin/orders', [
            'orders'         => $orders,
            'selectedStatus' => $status,
            'title'          => 'Store Orders'
        ], 'admin');
    }

    public function orderView(): void {
        $id = (int)($_GET['id'] ?? 0);
        $order = DB::fetch("SELECT * FROM orders WHERE id = :id", ['id' => $id]);
        if (!$order) {
            redirect('/admin/orders');
        }

        $items = DB::fetchAll("SELECT * FROM order_items WHERE order_id = :oid", ['oid' => $order['id']]);

        render_view('admin/order_detail', [
            'order' => $order,
            'items' => $items,
            'title' => 'Order ' . $order['order_number']
        ], 'admin');
    }

    public function orderUpdateStatus(): void {
        $id = (int)($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';

        $valid = ['pending', 'processing', 'out_for_delivery', 'delivered', 'cancelled'];
        if (in_array($status, $valid)) {
            $order = DB::fetch("SELECT * FROM orders WHERE id = :id", ['id' => $id]);
            if ($order) {
                // If moving to cancelled, restore stock per FR-11!
                if ($status === 'cancelled' && $order['status'] !== 'cancelled') {
                    $items = DB::fetchAll("SELECT * FROM order_items WHERE order_id = :oid", ['oid' => $id]);
                    foreach ($items as $it) {
                        DB::query("UPDATE products SET stock_quantity = stock_quantity + :qty WHERE id = :pid", [
                            'qty' => $it['quantity'],
                            'pid' => $it['product_id']
                        ]);
                        DB::insert('stock_log', [
                            'product_id'   => $it['product_id'],
                            'change_qty'   => $it['quantity'],
                            'balance_qty'  => (int)(DB::fetch("SELECT stock_quantity FROM products WHERE id = :id", ['id' => $it['product_id']])['stock_quantity'] ?? 0),
                            'reason'       => 'order_cancelled',
                            'reference_id' => $order['order_number'],
                            'created_by'   => 'admin'
                        ]);
                    }
                }
                DB::update('orders', ['status' => $status], 'id = :id', ['id' => $id]);
                set_flash('success', 'Order status updated to ' . ucfirst(str_replace('_', ' ', $status)) . '.');
            }
        }

        redirect('/admin/orders/view?id=' . $id);
    }

    public function orderInvoice(): void {
        $id = (int)($_GET['id'] ?? 0);
        $order = DB::fetch("SELECT * FROM orders WHERE id = :id", ['id' => $id]);
        if (!$order) redirect('/admin/orders');
        $items = DB::fetchAll("SELECT * FROM order_items WHERE order_id = :oid", ['oid' => $order['id']]);

        render_view('admin/invoice_print', [
            'order' => $order,
            'items' => $items,
            'title' => 'Invoice ' . $order['order_number']
        ], 'none');
    }

    public function customers(): void {
        $customers = DB::fetchAll(
            "SELECT u.*, 
                    (SELECT COUNT(*) FROM orders WHERE user_id = u.id) as order_count,
                    (SELECT SUM(total_amount) FROM orders WHERE user_id = u.id AND status != 'cancelled') as total_spend
             FROM users u
             WHERE u.role != 'admin'
             ORDER BY u.id DESC"
        );

        render_view('admin/customers', [
            'customers' => $customers,
            'title'     => 'Customer Directory'
        ], 'admin');
    }

    public function wholesaleApplications(): void {
        $apps = DB::fetchAll(
            "SELECT wa.*, u.email as user_email, pt.name as tier_name
             FROM wholesale_accounts wa
             JOIN users u ON wa.user_id = u.id
             LEFT JOIN pricing_tiers pt ON wa.tier_id = pt.id
             ORDER BY wa.id DESC"
        );

        $tiers = DB::fetchAll("SELECT * FROM pricing_tiers ORDER BY discount_percent ASC");

        render_view('admin/wholesale_applications', [
            'applications' => $apps,
            'tiers'        => $tiers,
            'title'        => 'Wholesale Applications'
        ], 'admin');
    }

    public function wholesaleApplicationAction(): void {
        $appId  = (int)($_POST['app_id'] ?? 0);
        $action = $_POST['action'] ?? 'approve';
        $tierId = (int)($_POST['tier_id'] ?? 1);
        $notes  = trim($_POST['notes'] ?? '');

        $app = DB::fetch("SELECT * FROM wholesale_accounts WHERE id = :id", ['id' => $appId]);
        if ($app) {
            if ($action === 'approve') {
                DB::update('wholesale_accounts', [
                    'status'      => 'approved',
                    'tier_id'     => $tierId,
                    'admin_notes' => $notes,
                    'approved_at' => date('Y-m-d H:i:s')
                ], 'id = :id', ['id' => $appId]);

                // Update user role to wholesale
                DB::update('users', ['role' => 'wholesale'], 'id = :id', ['id' => $app['user_id']]);
                set_flash('success', 'Wholesale application approved with tier assigned.');
            } else {
                DB::update('wholesale_accounts', [
                    'status'      => 'rejected',
                    'admin_notes' => $notes
                ], 'id = :id', ['id' => $appId]);
                set_flash('error', 'Wholesale application rejected.');
            }
        }

        redirect('/admin/wholesale');
    }

    public function pricingTiers(): void {
        $tiers = DB::fetchAll("SELECT * FROM pricing_tiers ORDER BY discount_percent ASC");
        render_view('admin/pricing_tiers', ['tiers' => $tiers, 'title' => 'B2B Pricing Tiers'], 'admin');
    }

    public function pricingTierSave(): void {
        $id       = (int)($_POST['id'] ?? 0);
        $name     = trim($_POST['name'] ?? '');
        $code     = trim($_POST['code'] ?? '');
        $discount = (float)($_POST['discount_percent'] ?? 0);
        $minSpend = (float)($_POST['min_spend'] ?? 0);
        $desc     = trim($_POST['description'] ?? '');

        $data = [
            'name'             => $name,
            'code'             => $code,
            'discount_percent' => $discount,
            'min_spend'        => $minSpend,
            'description'      => $desc
        ];

        if ($id > 0) {
            DB::update('pricing_tiers', $data, 'id = :id', ['id' => $id]);
            set_flash('success', 'Pricing tier updated.');
        } else {
            DB::insert('pricing_tiers', $data);
            set_flash('success', 'Pricing tier added.');
        }

        redirect('/admin/wholesale/tiers');
    }

    public function adminStandingOrders(): void {
        $standingOrders = DB::fetchAll(
            "SELECT so.*, wa.business_name, wa.contact_person
             FROM standing_orders so
             JOIN wholesale_accounts wa ON so.wholesale_account_id = wa.id
             ORDER BY so.id DESC"
        );

        render_view('admin/standing_orders', [
            'standingOrders' => $standingOrders,
            'title'          => 'Wholesale Standing Orders'
        ], 'admin');
    }

    public function promotions(): void {
        $promos = DB::fetchAll("SELECT * FROM promo_codes ORDER BY id DESC");
        render_view('admin/promotions', [
            'promos' => $promos,
            'title'  => 'Promo Codes & Offers'
        ], 'admin');
    }

    public function promotionSave(): void {
        $id       = (int)($_POST['id'] ?? 0);
        $code     = strtoupper(trim($_POST['code'] ?? ''));
        $type     = $_POST['discount_type'] ?? 'percentage';
        $val      = (float)($_POST['discount_value'] ?? 0);
        $minSpend = (float)($_POST['min_order_amount'] ?? 0);
        $maxUses  = !empty($_POST['max_uses']) ? (int)$_POST['max_uses'] : null;

        $data = [
            'code'             => $code,
            'discount_type'    => $type,
            'discount_value'   => $val,
            'min_order_amount' => $minSpend,
            'max_uses'         => $maxUses,
            'is_active'        => 1
        ];

        if ($id > 0) {
            DB::update('promo_codes', $data, 'id = :id', ['id' => $id]);
            set_flash('success', 'Promo code updated.');
        } else {
            DB::insert('promo_codes', $data);
            set_flash('success', 'Promo code created.');
        }

        redirect('/admin/promotions');
    }

    public function reviews(): void {
        $reviews = DB::fetchAll(
            "SELECT r.*, p.name_en as product_name, u.name as reviewer_name
             FROM reviews r
             JOIN products p ON r.product_id = p.id
             JOIN users u ON r.user_id = u.id
             ORDER BY r.id DESC"
        );

        render_view('admin/reviews', [
            'reviews' => $reviews,
            'title'   => 'Review Moderation'
        ], 'admin');
    }

    public function reviewAction(): void {
        $id = (int)($_POST['id'] ?? 0);
        $action = $_POST['action'] ?? 'approve';

        if ($action === 'approve') {
            DB::update('reviews', ['status' => 'approved'], 'id = :id', ['id' => $id]);
            set_flash('success', 'Review approved and published.');
        } elseif ($action === 'reject') {
            DB::update('reviews', ['status' => 'rejected'], 'id = :id', ['id' => $id]);
            set_flash('error', 'Review rejected.');
        } elseif ($action === 'delete') {
            DB::delete('reviews', 'id = :id', ['id' => $id]);
            set_flash('success', 'Review deleted.');
        }

        redirect('/admin/reviews');
    }

    public function recipes(): void {
        $recipes = DB::fetchAll("SELECT * FROM recipes ORDER BY id DESC");
        render_view('admin/recipes', [
            'recipes' => $recipes,
            'title'   => 'Recipes Management'
        ], 'admin');
    }

    public function recipeEdit(): void {
        $id = (int)($_GET['id'] ?? 0);
        $recipe = DB::fetch("SELECT * FROM recipes WHERE id = :id", ['id' => $id]);
        $allProducts = DB::fetchAll("SELECT * FROM products WHERE is_active = 1 ORDER BY name_en ASC");
        
        $linkedProductIds = [];
        if ($recipe) {
            $linked = DB::fetchAll("SELECT product_id FROM recipe_products WHERE recipe_id = :rid", ['rid' => $recipe['id']]);
            $linkedProductIds = array_column($linked, 'product_id');
        }

        render_view('admin/recipe_editor', [
            'recipe'           => $recipe,
            'allProducts'      => $allProducts,
            'linkedProductIds' => $linkedProductIds,
            'title'            => $recipe ? 'Edit Recipe' : 'New Recipe'
        ], 'admin');
    }

    public function recipeSave(): void {
        $id       = (int)($_POST['id'] ?? 0);
        $titleEn  = trim($_POST['title_en'] ?? '');
        $titleZh  = trim($_POST['title_zh'] ?? '');
        $cat      = $_POST['category'] ?? 'veggie_recipe';
        $prep     = (int)($_POST['prep_time_minutes'] ?? 15);
        $cook     = (int)($_POST['cooking_time_minutes'] ?? 15);
        $servings = trim($_POST['servings'] ?? '2-4 portions');
        $excEn    = trim($_POST['excerpt_en'] ?? '');
        $excZh    = trim($_POST['excerpt_zh'] ?? '');
        $ingEn    = trim($_POST['ingredients_en'] ?? '');
        $ingZh    = trim($_POST['ingredients_zh'] ?? '');
        $stepEn   = trim($_POST['steps_en'] ?? '');
        $stepZh   = trim($_POST['steps_zh'] ?? '');
        $products = $_POST['products'] ?? [];

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $titleEn)));

        $data = [
            'slug'                 => $slug,
            'category'             => $cat,
            'title_en'             => $titleEn,
            'title_zh'             => $titleZh,
            'prep_time_minutes'    => $prep,
            'cooking_time_minutes' => $cook,
            'servings'             => $servings,
            'image'                => 'salad.png',
            'excerpt_en'           => $excEn,
            'excerpt_zh'           => $excZh,
            'ingredients_en'       => $ingEn,
            'ingredients_zh'       => $ingZh,
            'steps_en'             => $stepEn,
            'steps_zh'             => $stepZh,
            'is_published'         => 1
        ];

        if ($id > 0) {
            DB::update('recipes', $data, 'id = :id', ['id' => $id]);
            $recipeId = $id;
            DB::delete('recipe_products', 'recipe_id = :rid', ['rid' => $recipeId]);
        } else {
            $recipeId = DB::insert('recipes', $data);
        }

        foreach ($products as $pid) {
            DB::insert('recipe_products', [
                'recipe_id'  => $recipeId,
                'product_id' => (int)$pid,
                'quantity'   => 1
            ]);
        }

        set_flash('success', 'Recipe saved successfully.');
        redirect('/admin/recipes');
    }

    public function reports(): void {
        // Sales aggregate
        $totalSales = (float)(DB::fetch("SELECT SUM(total_amount) as s FROM orders WHERE status != 'cancelled'")['s'] ?? 0);
        $totalRetail = (float)(DB::fetch("SELECT SUM(total_amount) as s FROM orders WHERE order_type = 'retail' AND status != 'cancelled'")['s'] ?? 0);
        $totalWholesale = (float)(DB::fetch("SELECT SUM(total_amount) as s FROM orders WHERE order_type = 'wholesale' AND status != 'cancelled'")['s'] ?? 0);

        // Best selling products
        $bestSellers = DB::fetchAll(
            "SELECT p.name_en, SUM(oi.quantity) as total_qty, SUM(oi.subtotal) as total_revenue
             FROM order_items oi
             JOIN products p ON oi.product_id = p.id
             GROUP BY oi.product_id
             ORDER BY total_qty DESC
             LIMIT 10"
        );

        render_view('admin/reports', [
            'totalSales'     => $totalSales,
            'totalRetail'    => $totalRetail,
            'totalWholesale' => $totalWholesale,
            'bestSellers'    => $bestSellers,
            'title'          => 'Store Performance Reports'
        ], 'admin');
    }

    public function exportCsv(): void {
        $type = $_GET['type'] ?? 'sales';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=kampung_cameron_' . $type . '_' . date('Ymd') . '.csv');

        $out = fopen('php://output', 'w');
        // UTF-8 BOM for Excel
        fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));

        if ($type === 'stock') {
            fputcsv($out, ['Product SKU', 'Name EN', 'Category', 'Unit', 'Price', 'Stock', 'Threshold', 'Status']);
            $rows = DB::fetchAll(
                "SELECT p.sku, p.name_en, c.name_en as cat, p.unit_weight, p.price, p.stock_quantity, p.low_stock_threshold
                 FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.id ASC"
            );
            foreach ($rows as $r) {
                $status = ($r['stock_quantity'] <= 0) ? 'Sold Out' : (($r['stock_quantity'] <= $r['low_stock_threshold']) ? 'Low Stock' : 'In Stock');
                fputcsv($out, [$r['sku'], $r['name_en'], $r['cat'], $r['unit_weight'], $r['price'], $r['stock_quantity'], $r['low_stock_threshold'], $status]);
            }
        } else {
            fputcsv($out, ['Order Number', 'Date', 'Type', 'Customer', 'Email', 'Payment Method', 'Subtotal', 'Discount', 'Shipping', 'Total', 'Status']);
            $rows = DB::fetchAll("SELECT * FROM orders ORDER BY id DESC");
            foreach ($rows as $r) {
                fputcsv($out, [$r['order_number'], $r['created_at'], $r['order_type'], $r['customer_name'], $r['customer_email'], $r['payment_method'], $r['subtotal'], $r['discount_amount'], $r['shipping_fee'], $r['total_amount'], $r['status']]);
            }
        }

        fclose($out);
        exit;
    }

    public function settings(): void {
        $settings = DB::fetchAll("SELECT * FROM settings");
        render_view('admin/settings', [
            'settings' => $settings,
            'title'    => 'Store Global Settings'
        ], 'admin');
    }

    public function settingsSave(): void {
        $settings = $_POST['settings'] ?? [];
        foreach ($settings as $key => $val) {
            DB::update('settings', ['setting_value' => trim($val)], 'setting_key = :k', ['k' => $key]);
        }
        set_flash('success', 'Global settings updated successfully.');
        redirect('/admin/settings');
    }
}
