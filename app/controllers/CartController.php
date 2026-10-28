<?php

class CartController {
    public function index(): void {
        $cart = get_active_cart();
        $items = get_cart_items($cart['id']);
        $totals = calculate_cart_totals($items, $cart['promo_code_id'] ?? null);

        render_view('shop/cart', [
            'items'  => $items,
            'totals' => $totals,
            'cart'   => $cart,
            'title'  => __('shopping_cart', 'Shopping Bag')
        ]);
    }

    public function add(): void {
        $productId = (int)($_POST['product_id'] ?? 0);
        $qty = max(1, (int)($_POST['quantity'] ?? 1));
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || !empty($_POST['ajax']);

        $product = DB::fetch("SELECT * FROM products WHERE id = :id AND is_active = 1", ['id' => $productId]);
        if (!$product) {
            $this->respond(['success' => false, 'message' => 'Product not found.'], $isAjax, '/shop');
            return;
        }

        if ($product['stock_quantity'] <= 0) {
            $this->respond(['success' => false, 'message' => 'Item is sold out.'], $isAjax, '/product?id=' . $productId);
            return;
        }

        $cart = get_active_cart();
        $existing = DB::fetch(
            "SELECT id, quantity FROM cart_items WHERE cart_id = :cid AND product_id = :pid",
            ['cid' => $cart['id'], 'pid' => $productId]
        );

        $newQty = $qty;
        if ($existing) {
            $newQty += $existing['quantity'];
        }

        // Check stock boundary per FR-10
        if ($newQty > $product['stock_quantity']) {
            $newQty = $product['stock_quantity'];
            $msg = sprintf(__('msg_stock_limit', 'Stock limit reached. Added %d available units.'), $product['stock_quantity']);
        } else {
            $msg = sprintf(__('msg_added_to_cart', 'Added %s to your bag.'), trans($product, 'name'));
        }

        if ($existing) {
            DB::update('cart_items', ['quantity' => $newQty], 'id = :id', ['id' => $existing['id']]);
        } else {
            DB::insert('cart_items', [
                'cart_id'    => $cart['id'],
                'product_id' => $productId,
                'quantity'   => $newQty
            ]);
        }

        $this->respond([
            'success'    => true,
            'message'    => $msg,
            'cart_count' => get_cart_count(),
            'subtotal'   => format_currency(get_cart_subtotal())
        ], $isAjax, '/cart');
    }

    public function update(): void {
        $cart = get_active_cart();
        $quantities = $_POST['quantities'] ?? [];

        foreach ($quantities as $itemId => $qty) {
            $itemId = (int)$itemId;
            $qty = (int)$qty;
            if ($qty <= 0) {
                DB::delete('cart_items', 'id = :id AND cart_id = :cid', ['id' => $itemId, 'cid' => $cart['id']]);
            } else {
                // Ensure cannot exceed stock
                $item = DB::fetch(
                    "SELECT ci.id, p.stock_quantity FROM cart_items ci JOIN products p ON ci.product_id = p.id WHERE ci.id = :id",
                    ['id' => $itemId]
                );
                if ($item) {
                    $clamped = min($qty, $item['stock_quantity']);
                    DB::update('cart_items', ['quantity' => $clamped], 'id = :id AND cart_id = :cid', ['id' => $itemId, 'cid' => $cart['id']]);
                }
            }
        }

        set_flash('success', 'Shopping bag updated.');
        redirect('/cart');
    }

    public function remove(): void {
        $cart = get_active_cart();
        $itemId = (int)($_POST['item_id'] ?? 0);
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || !empty($_POST['ajax']);

        DB::delete('cart_items', 'id = :id AND cart_id = :cid', ['id' => $itemId, 'cid' => $cart['id']]);

        $this->respond([
            'success'    => true,
            'cart_count' => get_cart_count(),
            'subtotal'   => format_currency(get_cart_subtotal())
        ], $isAjax, '/cart');
    }

    public function applyPromo(): void {
        $cart = get_active_cart();
        $code = strtoupper(trim($_POST['promo_code'] ?? ''));

        if (empty($code)) {
            set_flash('error', 'Please enter a promo code.');
            redirect('/cart');
        }

        $promo = DB::fetch(
            "SELECT * FROM promo_codes WHERE code = :code AND is_active = 1",
            ['code' => $code]
        );

        if (!$promo) {
            set_flash('error', 'Invalid or expired promo code.');
            redirect('/cart');
        }

        $items = get_cart_items($cart['id']);
        $subtotal = array_sum(array_column($items, 'line_total'));

        if ($promo['min_order_amount'] > 0 && $subtotal < $promo['min_order_amount']) {
            set_flash('error', sprintf('Minimum order of %s required for promo %s.', format_currency($promo['min_order_amount']), $code));
            redirect('/cart');
        }

        DB::update('carts', ['promo_code_id' => $promo['id']], 'id = :id', ['id' => $cart['id']]);
        set_flash('success', sprintf('Promo code %s applied!', $code));
        redirect('/cart');
    }

    public function miniCart(): void {
        header('Content-Type: application/json');
        $cart = get_active_cart();
        $items = get_cart_items($cart['id']);
        $totals = calculate_cart_totals($items, $cart['promo_code_id'] ?? null);

        foreach ($items as &$it) {
            $it['name'] = trans($it, 'name');
            $it['image_url'] = asset('images/' . $it['image']);
            $it['formatted_price'] = format_currency($it['unit_price']);
            $it['formatted_total'] = format_currency($it['line_total']);
        }
        unset($it);

        echo json_encode([
            'items'       => $items,
            'count'       => get_cart_count(),
            'subtotal'    => format_currency($totals['subtotal']),
            'total'       => format_currency($totals['total']),
            'cart_url'    => url('/cart'),
            'checkout_url'=> url('/checkout')
        ]);
        exit;
    }

    private function respond(array $data, bool $isAjax, string $redirect): void {
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode($data);
            exit;
        }
        if (!empty($data['message'])) {
            set_flash($data['success'] ? 'success' : 'error', $data['message']);
        }
        redirect($redirect);
    }
}
