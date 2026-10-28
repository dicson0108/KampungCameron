<?php

class CheckoutController {
    public function index(): void {
        $cart = get_active_cart();
        $cartItems = get_cart_items($cart['id']);

        if (empty($cartItems)) {
            set_flash('error', 'Your shopping bag is empty.');
            redirect('/shop');
        }

        $totals = calculate_cart_totals($cartItems, $cart['promo_code_id'] ?? null);

        // User saved addresses
        $addresses = [];
        if (is_logged_in()) {
            $addresses = DB::fetchAll("SELECT * FROM addresses WHERE user_id = :uid ORDER BY is_default DESC, id DESC", ['uid' => auth_id()]);
        }

        render_view('shop/checkout', [
            'cartItems' => $cartItems,
            'subtotal'  => $totals['subtotal'],
            'discount'  => $totals['discount'],
            'shipping'  => $totals['shipping'],
            'total'     => $totals['total'],
            'promo'     => $totals['promo'],
            'addresses' => $addresses,
            'title'     => __('btn_checkout', 'Checkout')
        ]);
    }

    public function process(): void {
        if (!verify_csrf()) {
            set_flash('error', 'Session expired. Please try again.');
            redirect('/checkout');
        }

        $fullName    = trim($_POST['full_name'] ?? '');
        $email       = trim($_POST['email'] ?? '');
        $phone       = trim($_POST['phone'] ?? '');
        $address1    = trim($_POST['address_line1'] ?? '');
        $address2    = trim($_POST['address_line2'] ?? '');
        $city        = trim($_POST['city'] ?? '');
        $state       = trim($_POST['state'] ?? '');
        $postcode    = trim($_POST['postcode'] ?? '');
        $notes       = trim($_POST['delivery_notes'] ?? '');
        $paymentMethod = $_POST['payment_method'] ?? 'fpx';

        if (empty($fullName) || empty($email) || empty($phone) || empty($address1) || empty($city) || empty($state) || empty($postcode)) {
            set_flash('error', 'Please fill in all required delivery details.');
            redirect('/checkout');
        }

        $cart = get_active_cart();
        $pdo = DB::connect();

        try {
            // Begin transaction for atomic stock check and reservation
            $pdo->beginTransaction();

            // Lock cart items & products with row-level locks
            $stmt = $pdo->prepare(
                "SELECT ci.quantity, p.id as product_id, p.name_en, p.name_zh, p.unit_weight,
                        p.price, p.sale_price, p.stock_quantity
                 FROM cart_items ci
                 JOIN products p ON ci.product_id = p.id
                 WHERE ci.cart_id = :cid
                 FOR UPDATE"
            );
            $stmt->execute(['cid' => $cart['id']]);
            $cartItems = $stmt->fetchAll();

            if (empty($cartItems)) {
                $pdo->rollBack();
                set_flash('error', 'Your shopping bag is empty.');
                redirect('/shop');
            }

            // Verify stock for all items
            foreach ($cartItems as $it) {
                if ($it['stock_quantity'] < $it['quantity']) {
                    $pdo->rollBack();
                    set_flash('error', sprintf('Sorry, %s only has %d unit(s) remaining in stock.', $it['name_en'], $it['stock_quantity']));
                    redirect('/cart');
                }
            }

            $wsDiscount = $_SESSION['wholesale']['discount_percent'] ?? 0;
            foreach ($cartItems as &$it) {
                $it['unit_price'] = product_price($it, $wsDiscount);
                $it['line_total'] = $it['unit_price'] * $it['quantity'];
            }
            unset($it);

            $totals = calculate_cart_totals($cartItems, $cart['promo_code_id'] ?? null);
            if ($totals['promo']) {
                $pdo->prepare("UPDATE promo_codes SET uses_count = uses_count + 1 WHERE id = :id")
                    ->execute(['id' => $totals['promo']['id']]);
            }

            $orderNumber = (has_role('wholesale') ? 'WH-' : 'KC-') . rand(1000, 9999);

            // Insert order
            $orderInsert = $pdo->prepare(
                "INSERT INTO `orders` (
                    `order_number`, `user_id`, `order_type`, `status`,
                    `subtotal`, `discount_amount`, `shipping_fee`, `total_amount`,
                    `payment_method`, `payment_status`,
                    `customer_name`, `customer_email`, `customer_phone`,
                    `delivery_address`, `delivery_line2`, `delivery_city`, `delivery_state`, `delivery_postcode`,
                    `delivery_notes`, `promo_code`
                ) VALUES (
                    :onum, :uid, :otype, 'pending',
                    :sub, :disc, :ship, :total,
                    :pmeth, 'selected',
                    :cname, :cemail, :cphone,
                    :addr, :addr2, :city, :state, :pcode,
                    :notes, :pcode_val
                )"
            );

            $orderInsert->execute([
                'onum'      => $orderNumber,
                'uid'       => auth_id(),
                'otype'     => has_role('wholesale') ? 'wholesale' : 'retail',
                'sub'       => $totals['subtotal'],
                'disc'      => $totals['discount'],
                'ship'      => $totals['shipping'],
                'total'     => $totals['total'],
                'pmeth'     => $paymentMethod,
                'cname'     => $fullName,
                'cemail'    => $email,
                'cphone'    => $phone,
                'addr'      => $address1,
                'addr2'     => $address2,
                'city'      => $city,
                'state'     => $state,
                'pcode'     => $postcode,
                'notes'     => $notes,
                'pcode_val' => $totals['promo']['code'] ?? null
            ]);
            $orderId = (int)$pdo->lastInsertId();

            // Insert order items and deduct stock
            $itemInsert = $pdo->prepare(
                "INSERT INTO `order_items` (`order_id`, `product_id`, `product_name`, `unit_weight`, `unit_price`, `quantity`, `subtotal`)
                 VALUES (:oid, :pid, :pname, :punit, :uprice, :qty, :stotal)"
            );

            $stockUpdate = $pdo->prepare("UPDATE `products` SET `stock_quantity` = `stock_quantity` - :qty WHERE `id` = :pid");
            $stockLog = $pdo->prepare(
                "INSERT INTO `stock_log` (`product_id`, `change_qty`, `balance_qty`, `reason`, `reference_id`, `created_by`)
                 VALUES (:pid, :cqty, (SELECT stock_quantity FROM products WHERE id = :pid2), 'order_placed', :ref, :cby)"
            );

            foreach ($cartItems as $it) {
                $itemInsert->execute([
                    'oid'    => $orderId,
                    'pid'    => $it['product_id'],
                    'pname'  => $it['name_en'],
                    'punit'  => $it['unit_weight'],
                    'uprice' => $it['unit_price'],
                    'qty'    => $it['quantity'],
                    'stotal' => $it['line_total']
                ]);

                // Deduct stock
                $stockUpdate->execute(['qty' => $it['quantity'], 'pid' => $it['product_id']]);

                // Record stock log
                $stockLog->execute([
                    'pid'  => $it['product_id'],
                    'cqty' => -$it['quantity'],
                    'pid2' => $it['product_id'],
                    'ref'  => $orderNumber,
                    'cby'  => $fullName
                ]);
            }

            // Clear cart
            $pdo->prepare("DELETE FROM `cart_items` WHERE `cart_id` = :cid")->execute(['cid' => $cart['id']]);
            $pdo->prepare("UPDATE `carts` SET `promo_code_id` = NULL WHERE `id` = :cid")->execute(['cid' => $cart['id']]);

            $pdo->commit();
            redirect('/checkout/success?order=' . urlencode($orderNumber));

        } catch (Exception $e) {
            $pdo->rollBack();
            set_flash('error', 'Checkout error: ' . $e->getMessage());
            redirect('/checkout');
        }
    }

    public function success(): void {
        $orderNumber = trim($_GET['order'] ?? '');
        $order = DB::fetch("SELECT * FROM orders WHERE order_number = :onum", ['onum' => $orderNumber]);

        if (!$order) {
            redirect('/shop');
        }

        $items = DB::fetchAll(
            "SELECT oi.*, p.image FROM order_items oi
             LEFT JOIN products p ON oi.product_id = p.id
             WHERE oi.order_id = :oid",
            ['oid' => $order['id']]
        );

        render_view('shop/order_confirmation', [
            'order' => $order,
            'items' => $items,
            'title' => 'Order Confirmation — ' . $order['order_number']
        ]);
    }
}
