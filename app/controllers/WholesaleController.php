<?php

class WholesaleController {
    public function landing(): void {
        render_view('wholesale/landing', [
            'title' => 'Wholesale Fresh Produce for Kitchens & Restaurants — Kampung Cameron'
        ]);
    }

    public function apply(): void {
        if (is_logged_in() && has_role('wholesale')) {
            redirect('/wholesale/dashboard');
        }

        render_view('wholesale/apply', [
            'title' => 'Apply for a Wholesale Account — Kampung Cameron'
        ]);
    }

    public function submitApplication(): void {
        $businessName = trim($_POST['business_name'] ?? '');
        $regNumber    = trim($_POST['registration_number'] ?? '');
        $person       = trim($_POST['contact_person'] ?? '');
        $email        = trim($_POST['contact_email'] ?? '');
        $phone        = trim($_POST['contact_phone'] ?? '');
        $type         = trim($_POST['business_type'] ?? 'Restaurant / Kitchen');
        $password     = $_POST['password'] ?? '';

        if (empty($businessName) || empty($regNumber) || empty($person) || empty($email) || empty($phone)) {
            set_flash('error', 'Please complete all required fields.');
            redirect('/wholesale/apply');
        }

        // Handle document upload if provided
        $docPath = null;
        if (!empty($_FILES['document']['name']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['document']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['pdf', 'png', 'jpg', 'jpeg'])) {
                $docName = 'ssm_' . time() . '_' . rand(100, 999) . '.' . $ext;
                $target = UPLOAD_PATH . '/' . $docName;
                if (move_uploaded_file($_FILES['document']['tmp_name'], $target)) {
                    $docPath = $docName;
                }
            }
        }

        $userId = auth_id();
        if (!$userId) {
            // Check if user already exists
            $existing = DB::fetch("SELECT id FROM users WHERE email = :email", ['email' => $email]);
            if ($existing) {
                $userId = $existing['id'];
            } else {
                $pwdHash = password_hash(!empty($password) ? $password : 'Wholesale@123', PASSWORD_BCRYPT);
                $userId = DB::insert('users', [
                    'name'          => $person,
                    'email'         => $email,
                    'password_hash' => $pwdHash,
                    'phone'         => $phone,
                    'role'          => 'customer' // Becomes wholesale upon admin approval
                ]);
            }
        }

        // Check if application exists
        $existingApp = DB::fetch("SELECT id FROM wholesale_accounts WHERE user_id = :uid", ['uid' => $userId]);
        if ($existingApp) {
            DB::update('wholesale_accounts', [
                'business_name'       => $businessName,
                'registration_number' => $regNumber,
                'contact_person'      => $person,
                'contact_email'       => $email,
                'contact_phone'       => $phone,
                'business_type'       => $type,
                'document_path'       => $docPath ?: $existingApp['document_path'] ?? null,
                'status'              => 'pending'
            ], 'id = :id', ['id' => $existingApp['id']]);
        } else {
            DB::insert('wholesale_accounts', [
                'user_id'             => $userId,
                'business_name'       => $businessName,
                'registration_number' => $regNumber,
                'contact_person'      => $person,
                'contact_email'       => $email,
                'contact_phone'       => $phone,
                'business_type'       => $type,
                'document_path'       => $docPath,
                'status'              => 'pending'
            ]);
        }

        render_view('wholesale/pending', [
            'businessName' => $businessName,
            'title'        => 'Wholesale Application Pending — Kampung Cameron'
        ]);
    }

    public function dashboard(): void {
        require_role('wholesale', '/wholesale/apply');
        $wsAccount = $this->getWholesaleAccount();

        // Recent orders
        $recentOrders = DB::fetchAll(
            "SELECT * FROM orders WHERE user_id = :uid ORDER BY id DESC LIMIT 5",
            ['uid' => auth_id()]
        );

        // Active standing order
        $standingOrder = DB::fetch(
            "SELECT * FROM standing_orders WHERE wholesale_account_id = :wid ORDER BY id DESC LIMIT 1",
            ['wid' => $wsAccount['id']]
        );

        render_view('wholesale/dashboard', [
            'wsAccount'     => $wsAccount,
            'recentOrders'  => $recentOrders,
            'standingOrder' => $standingOrder,
            'title'         => 'Wholesale Dashboard — ' . $wsAccount['business_name']
        ]);
    }

    public function catalog(): void {
        require_role('wholesale', '/wholesale/apply');
        $wsAccount = $this->getWholesaleAccount();
        $discount = (float)($wsAccount['discount_percent'] ?? 0);

        $products = DB::fetchAll(
            "SELECT p.*, c.name_en as category_name
             FROM products p
             JOIN categories c ON p.category_id = c.id
             WHERE p.is_active = 1
             ORDER BY c.sort_order ASC, p.name_en ASC"
        );

        foreach ($products as &$p) {
            $base = (float)$p['price'];
            $p['retail_price'] = $base;
            $p['wholesale_price'] = round($base * (1 - ($discount / 100)), 2);
        }
        unset($p);

        render_view('wholesale/catalog', [
            'wsAccount' => $wsAccount,
            'products'  => $products,
            'title'     => 'Wholesale Bulk Catalogue'
        ]);
    }

    public function quickOrder(): void {
        require_role('wholesale', '/wholesale/apply');
        $wsAccount = $this->getWholesaleAccount();
        $discount = (float)($wsAccount['discount_percent'] ?? 0);

        $products = DB::fetchAll("SELECT * FROM products WHERE is_active = 1 AND stock_quantity > 0 ORDER BY name_en ASC");
        foreach ($products as &$p) {
            $p['wholesale_price'] = round((float)$p['price'] * (1 - ($discount / 100)), 2);
        }
        unset($p);

        render_view('wholesale/quick_order', [
            'wsAccount' => $wsAccount,
            'products'  => $products,
            'title'     => 'Wholesale Quick Order Form'
        ]);
    }

    public function submitQuickOrder(): void {
        require_role('wholesale', '/wholesale/apply');
        $items = $_POST['items'] ?? [];
        $cart = get_active_cart();

        $addedCount = 0;
        foreach ($items as $pid => $qty) {
            $pid = (int)$pid;
            $qty = (int)$qty;
            if ($qty > 0) {
                $prod = DB::fetch("SELECT stock_quantity FROM products WHERE id = :id", ['id' => $pid]);
                if ($prod) {
                    $clamped = min($qty, $prod['stock_quantity']);
                    $existing = DB::fetch("SELECT id, quantity FROM cart_items WHERE cart_id = :cid AND product_id = :pid", ['cid' => $cart['id'], 'pid' => $pid]);
                    if ($existing) {
                        DB::update('cart_items', ['quantity' => $clamped], 'id = :id', ['id' => $existing['id']]);
                    } else {
                        DB::insert('cart_items', ['cart_id' => $cart['id'], 'product_id' => $pid, 'quantity' => $clamped]);
                    }
                    $addedCount++;
                }
            }
        }

        if ($addedCount > 0) {
            set_flash('success', sprintf('Added %d bulk produce item(s) to order.', $addedCount));
            redirect('/checkout');
        } else {
            set_flash('error', 'Please enter quantities for at least one product.');
            redirect('/wholesale/quick-order');
        }
    }

    public function standingOrders(): void {
        require_role('wholesale', '/wholesale/apply');
        $wsAccount = $this->getWholesaleAccount();

        $standingOrders = DB::fetchAll(
            "SELECT so.*, 
                    (SELECT COUNT(*) FROM standing_order_items WHERE standing_order_id = so.id) as item_count
             FROM standing_orders so
             WHERE so.wholesale_account_id = :wid
             ORDER BY so.id DESC",
            ['wid' => $wsAccount['id']]
        );

        render_view('wholesale/standing_orders', [
            'wsAccount'      => $wsAccount,
            'standingOrders' => $standingOrders,
            'title'          => 'Standing Orders — Recurring Supply'
        ]);
    }

    public function editStandingOrder(): void {
        require_role('wholesale', '/wholesale/apply');
        $wsAccount = $this->getWholesaleAccount();
        $id = (int)($_GET['id'] ?? 0);

        $order = null;
        $orderItems = [];
        if ($id > 0) {
            $order = DB::fetch(
                "SELECT * FROM standing_orders WHERE id = :id AND wholesale_account_id = :wid",
                ['id' => $id, 'wid' => $wsAccount['id']]
            );
            if ($order) {
                $orderItems = DB::fetchAll(
                    "SELECT soi.*, p.name_en, p.unit_weight, p.price, p.stock_quantity
                     FROM standing_order_items soi
                     JOIN products p ON soi.product_id = p.id
                     WHERE soi.standing_order_id = :soid",
                    ['soid' => $order['id']]
                );
            }
        }

        $allProducts = DB::fetchAll("SELECT * FROM products WHERE is_active = 1 ORDER BY name_en ASC");
        $addresses = DB::fetchAll("SELECT * FROM addresses WHERE user_id = :uid", ['uid' => auth_id()]);

        render_view('wholesale/standing_order_editor', [
            'wsAccount'    => $wsAccount,
            'order'        => $order,
            'orderItems'   => $orderItems,
            'allProducts'  => $allProducts,
            'addresses'    => $addresses,
            'title'        => 'Standing Order Editor'
        ]);
    }

    public function saveStandingOrder(): void {
        require_role('wholesale', '/wholesale/apply');
        $wsAccount = $this->getWholesaleAccount();

        $id       = (int)($_POST['standing_order_id'] ?? 0);
        $name     = trim($_POST['schedule_name'] ?? 'Weekly Supply');
        $day      = $_POST['weekly_delivery_day'] ?? 'Monday';
        $startDate= $_POST['start_date'] ?? date('Y-m-d');
        $address  = trim($_POST['delivery_address'] ?? '');
        $notes    = trim($_POST['order_notes'] ?? '');
        $products = $_POST['products'] ?? []; // [product_id => quantity]

        if (empty($name) || empty($address)) {
            set_flash('error', 'Please provide schedule name and delivery address.');
            redirect('/wholesale/standing-orders/edit' . ($id ? "?id=$id" : ''));
        }

        if ($id > 0) {
            DB::update('standing_orders', [
                'name'             => $name,
                'delivery_day'     => $day,
                'start_date'       => $startDate,
                'delivery_address' => $address,
                'notes'            => $notes
            ], 'id = :id AND wholesale_account_id = :wid', ['id' => $id, 'wid' => $wsAccount['id']]);
            $soId = $id;
            DB::delete('standing_order_items', 'standing_order_id = :soid', ['soid' => $soId]);
        } else {
            $soId = DB::insert('standing_orders', [
                'wholesale_account_id' => $wsAccount['id'],
                'name'                 => $name,
                'delivery_day'         => $day,
                'start_date'           => $startDate,
                'delivery_address'     => $address,
                'notes'                => $notes,
                'status'               => 'active'
            ]);
        }

        foreach ($products as $pid => $qty) {
            $qty = (int)$qty;
            if ($qty > 0) {
                DB::insert('standing_order_items', [
                    'standing_order_id' => $soId,
                    'product_id'        => (int)$pid,
                    'quantity'          => $qty
                ]);
            }
        }

        set_flash('success', 'Standing order schedule saved successfully.');
        redirect('/wholesale/standing-orders');
    }

    public function updateStandingOrderStatus(): void {
        require_role('wholesale', '/wholesale/apply');
        $wsAccount = $this->getWholesaleAccount();
        $id = (int)($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? 'active';

        if (in_array($status, ['active', 'paused', 'cancelled'])) {
            DB::update('standing_orders', ['status' => $status], 'id = :id AND wholesale_account_id = :wid', ['id' => $id, 'wid' => $wsAccount['id']]);
            set_flash('success', 'Standing order status updated to ' . ucfirst($status) . '.');
        }

        redirect('/wholesale/standing-orders');
    }

    public function orders(): void {
        require_role('wholesale', '/wholesale/apply');
        $orders = DB::fetchAll(
            "SELECT * FROM orders WHERE user_id = :uid ORDER BY id DESC",
            ['uid' => auth_id()]
        );

        render_view('wholesale/orders', [
            'orders' => $orders,
            'title'  => 'Wholesale Orders'
        ]);
    }

    public function invoices(): void {
        require_role('wholesale', '/wholesale/apply');
        $orders = DB::fetchAll(
            "SELECT * FROM orders WHERE user_id = :uid ORDER BY id DESC",
            ['uid' => auth_id()]
        );

        render_view('wholesale/invoices', [
            'orders' => $orders,
            'title'  => 'Wholesale Invoices'
        ]);
    }

    public function invoiceDetail(int $id): void {
        require_role('wholesale', '/wholesale/apply');
        $order = DB::fetch(
            "SELECT * FROM orders WHERE id = :id AND user_id = :uid",
            ['id' => $id, 'uid' => auth_id()]
        );

        if (!$order) {
            redirect('/wholesale/invoices');
        }

        $items = DB::fetchAll("SELECT * FROM order_items WHERE order_id = :oid", ['oid' => $order['id']]);
        $wsAccount = $this->getWholesaleAccount();

        render_view('wholesale/invoice_detail', [
            'order'     => $order,
            'items'     => $items,
            'wsAccount' => $wsAccount,
            'title'     => 'Invoice ' . $order['order_number']
        ], 'none');
    }

    private function getWholesaleAccount(): array {
        $acc = DB::fetch(
            "SELECT wa.*, pt.name as tier_name, pt.code as tier_code, pt.discount_percent
             FROM wholesale_accounts wa
             LEFT JOIN pricing_tiers pt ON wa.tier_id = pt.id
             WHERE wa.user_id = :uid",
            ['uid' => auth_id()]
        );
        if (!$acc || $acc['status'] !== 'approved') {
            redirect('/wholesale/apply');
        }
        return $acc;
    }
}
