<?php

class AccountController {
    public function login(): void {
        if (is_logged_in()) {
            redirect(is_admin() ? '/admin/dashboard' : (is_wholesale() ? '/wholesale/dashboard' : '/account/dashboard'));
        }
        render_view('account/login', ['title' => 'Sign In — Kampung Cameron']);
    }

    public function doLogin(): void {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            set_flash('error', 'Please enter your email and password.');
            redirect('/account/login');
        }

        $user = DB::fetch("SELECT * FROM users WHERE email = :email", ['email' => $email]);
        if (!$user || !password_verify($password, $user['password_hash'])) {
            set_flash('error', 'Invalid email or password.');
            redirect('/account/login');
        }

        if (!$user['is_active']) {
            set_flash('error', 'This account has been deactivated. Please contact support.');
            redirect('/account/login');
        }

        login_user($user);
        set_flash('success', 'Welcome back, ' . htmlspecialchars($user['name']) . '!');

        if ($user['role'] === 'admin') {
            redirect('/admin/dashboard');
        } elseif ($user['role'] === 'wholesale') {
            redirect('/wholesale/dashboard');
        } else {
            redirect('/account/dashboard');
        }
    }

    public function register(): void {
        if (is_logged_in()) {
            redirect('/account/dashboard');
        }
        render_view('account/register', ['title' => 'Create an Account — Kampung Cameron']);
    }

    public function doRegister(): void {
        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $phone    = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($name) || empty($email) || empty($password)) {
            set_flash('error', 'Please fill in all required fields.');
            redirect('/account/register');
        }

        $existing = DB::fetch("SELECT id FROM users WHERE email = :email", ['email' => $email]);
        if ($existing) {
            set_flash('error', 'An account with that email already exists.');
            redirect('/account/register');
        }

        $userId = DB::insert('users', [
            'name'          => $name,
            'email'         => $email,
            'phone'         => $phone,
            'password_hash' => password_hash($password, PASSWORD_BCRYPT),
            'role'          => 'customer',
            'is_active'     => 1
        ]);

        $user = DB::fetch("SELECT * FROM users WHERE id = :id", ['id' => $userId]);
        login_user($user);

        set_flash('success', 'Account created successfully! Welcome to Kampung Cameron.');
        redirect('/account/dashboard');
    }

    public function logout(): void {
        logout_user();
        set_flash('success', 'You have been signed out.');
        redirect('/shop');
    }

    public function dashboard(): void {
        require_login();
        $userId = auth_id();

        $recentOrders = DB::fetchAll("SELECT * FROM orders WHERE user_id = :uid ORDER BY id DESC LIMIT 3", ['uid' => $userId]);
        $orderCount = (int)(DB::fetch("SELECT COUNT(*) as c FROM orders WHERE user_id = :uid", ['uid' => $userId])['c'] ?? 0);
        $wishlistCount = (int)(DB::fetch("SELECT COUNT(*) as c FROM wishlist WHERE user_id = :uid", ['uid' => $userId])['c'] ?? 0);
        $defaultAddress = DB::fetch("SELECT * FROM addresses WHERE user_id = :uid ORDER BY is_default DESC, id DESC LIMIT 1", ['uid' => $userId]);

        render_view('account/dashboard', [
            'recentOrders'   => $recentOrders,
            'orderCount'     => $orderCount,
            'wishlistCount'  => $wishlistCount,
            'defaultAddress' => $defaultAddress,
            'title'          => 'My Account Dashboard'
        ]);
    }

    public function orders(): void {
        require_login();
        $orders = DB::fetchAll("SELECT * FROM orders WHERE user_id = :uid ORDER BY id DESC", ['uid' => auth_id()]);
        render_view('account/orders', [
            'orders' => $orders,
            'title'  => 'My Order History'
        ]);
    }

    public function orderDetail(int $id): void {
        require_login();
        $order = DB::fetch("SELECT * FROM orders WHERE id = :id AND user_id = :uid", ['id' => $id, 'uid' => auth_id()]);
        if (!$order) {
            redirect('/account/orders');
        }

        $items = DB::fetchAll(
            "SELECT oi.*, p.image, p.slug 
             FROM order_items oi
             LEFT JOIN products p ON oi.product_id = p.id
             WHERE oi.order_id = :oid",
            ['oid' => $order['id']]
        );

        render_view('account/order_detail', [
            'order' => $order,
            'items' => $items,
            'title' => 'Order ' . $order['order_number']
        ]);
    }

    public function addresses(): void {
        require_login();
        $addresses = DB::fetchAll("SELECT * FROM addresses WHERE user_id = :uid ORDER BY is_default DESC, id DESC", ['uid' => auth_id()]);
        render_view('account/addresses', [
            'addresses' => $addresses,
            'title'     => 'Saved Addresses'
        ]);
    }

    public function saveAddress(): void {
        require_login();
        $label     = trim($_POST['label'] ?? 'Home');
        $recipient = trim($_POST['recipient_name'] ?? '');
        $phone     = trim($_POST['phone'] ?? '');
        $addr1     = trim($_POST['address_line1'] ?? '');
        $addr2     = trim($_POST['address_line2'] ?? '');
        $city      = trim($_POST['city'] ?? '');
        $state     = trim($_POST['state'] ?? '');
        $postcode  = trim($_POST['postcode'] ?? '');
        $isDefault = !empty($_POST['is_default']) ? 1 : 0;

        if ($isDefault) {
            DB::update('addresses', ['is_default' => 0], 'user_id = :uid', ['uid' => auth_id()]);
        }

        DB::insert('addresses', [
            'user_id'        => auth_id(),
            'label'          => $label,
            'recipient_name' => $recipient,
            'phone'          => $phone,
            'address_line1'  => $addr1,
            'address_line2'  => $addr2,
            'city'           => $city,
            'state'          => $state,
            'postcode'       => $postcode,
            'is_default'     => $isDefault
        ]);

        set_flash('success', 'Address saved.');
        redirect('/account/addresses');
    }

    public function profile(): void {
        require_login();
        $user = DB::fetch("SELECT * FROM users WHERE id = :id", ['id' => auth_id()]);
        render_view('account/profile', [
            'user'  => $user,
            'title' => 'Profile & Password'
        ]);
    }

    public function saveProfile(): void {
        require_login();
        $name  = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['new_password'] ?? '';

        $update = [
            'name'  => $name,
            'phone' => $phone
        ];

        if (!empty($password)) {
            $update['password_hash'] = password_hash($password, PASSWORD_BCRYPT);
        }

        DB::update('users', $update, 'id = :id', ['id' => auth_id()]);
        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['phone'] = $phone;

        set_flash('success', 'Profile updated successfully.');
        redirect('/account/profile');
    }

    public function wishlist(): void {
        require_login();
        $items = DB::fetchAll(
            "SELECT w.id as wishlist_id, p.*, c.name_en as category_name
             FROM wishlist w
             JOIN products p ON w.product_id = p.id
             JOIN categories c ON p.category_id = c.id
             WHERE w.user_id = :uid",
            ['uid' => auth_id()]
        );

        render_view('account/wishlist', [
            'items' => $items,
            'title' => 'My Wishlist'
        ]);
    }

    public function toggleWishlist(): void {
        if (!is_logged_in()) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'redirect' => url('/account/login')]);
                exit;
            }
            redirect('/account/login');
        }

        $productId = (int)($_POST['product_id'] ?? 0);
        $userId = auth_id();

        $existing = DB::fetch("SELECT id FROM wishlist WHERE user_id = :uid AND product_id = :pid", ['uid' => $userId, 'pid' => $productId]);

        if ($existing) {
            DB::delete('wishlist', 'id = :id', ['id' => $existing['id']]);
            $added = false;
        } else {
            DB::insert('wishlist', ['user_id' => $userId, 'product_id' => $productId]);
            $added = true;
        }

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) || !empty($_POST['ajax'])) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'added'   => $added,
                'count'   => get_wishlist_count()
            ]);
            exit;
        }

        redirect('/account/wishlist');
    }

    public function reviews(): void {
        require_login();
        $reviews = DB::fetchAll(
            "SELECT r.*, p.name_en, p.name_zh, p.image, p.slug
             FROM reviews r
             JOIN products p ON r.product_id = p.id
             WHERE r.user_id = :uid
             ORDER BY r.created_at DESC",
            ['uid' => auth_id()]
        );

        render_view('account/reviews', [
            'reviews' => $reviews,
            'title'   => 'My Reviews'
        ]);
    }
}
