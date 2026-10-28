<div class="container" style="padding-top: 48px; padding-bottom: 80px; max-width: 720px;">
    <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 48px 40px; text-align: center;">
        <div style="width: 64px; height: 64px; background: var(--primary-green-light); color: var(--primary-green); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 20px;">
            ✓
        </div>

        <span class="badge badge-in-stock" style="margin-bottom: 12px; font-size: 13px; padding: 4px 12px;">
            <?= htmlspecialchars($order['order_number']) ?>
        </span>

        <h1 style="font-size: 32px; margin-bottom: 12px;"><?= __('order_confirmed', 'Thank you! Your order is placed.') ?></h1>
        <p style="color: var(--text-muted); font-size: 15px; margin-bottom: 32px;">
            We have received your harvest order. A confirmation has been sent to <strong><?= htmlspecialchars($order['customer_email']) ?></strong>.
        </p>

        <!-- Order Summary Details Box -->
        <div style="background: var(--warm-paper); border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 24px; text-align: left; margin-bottom: 32px; font-size: 14px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid var(--border-color);">
                <div>
                    <span style="color: var(--text-muted); font-size: 12px; text-transform: uppercase; font-weight: 700;">Recipient</span>
                    <div style="font-weight: 600; margin-top: 4px;"><?= htmlspecialchars($order['customer_name']) ?></div>
                    <div style="color: var(--text-muted);"><?= htmlspecialchars($order['customer_phone']) ?></div>
                </div>
                <div>
                    <span style="color: var(--text-muted); font-size: 12px; text-transform: uppercase; font-weight: 700;">Delivery Address</span>
                    <div style="margin-top: 4px;"><?= htmlspecialchars($order['delivery_address']) ?></div>
                    <div style="color: var(--text-muted);"><?= htmlspecialchars($order['delivery_postcode']) ?> <?= htmlspecialchars($order['delivery_city']) ?>, <?= htmlspecialchars($order['delivery_state']) ?></div>
                </div>
            </div>

            <!-- Items -->
            <div style="margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid var(--border-color);">
                <span style="color: var(--text-muted); font-size: 12px; text-transform: uppercase; font-weight: 700; display: block; margin-bottom: 8px;">Produce Items</span>
                <?php foreach ($items as $it): ?>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                        <span><?= htmlspecialchars($it['product_name']) ?> (<?= htmlspecialchars($it['unit_weight']) ?>) × <?= $it['quantity'] ?></span>
                        <strong><?= format_currency($it['subtotal']) ?></strong>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Totals -->
            <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                <span>Subtotal</span>
                <span><?= format_currency($order['subtotal']) ?></span>
            </div>
            <?php if ($order['discount_amount'] > 0): ?>
                <div style="display: flex; justify-content: space-between; margin-bottom: 6px; color: var(--primary-green);">
                    <span>Promo discount (<?= htmlspecialchars($order['promo_code']) ?>)</span>
                    <span>– <?= format_currency($order['discount_amount']) ?></span>
                </div>
            <?php endif; ?>
            <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                <span>Flat shipping</span>
                <span><?= format_currency($order['shipping_fee']) ?></span>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 18px; font-weight: 700; padding-top: 10px; border-top: 1px solid var(--border-color);">
                <span>Total</span>
                <span style="color: var(--primary-green);"><?= format_currency($order['total_amount']) ?></span>
            </div>
            <div style="margin-top: 12px; font-size: 12px; color: var(--text-muted);">
                Selected Payment Channel: <strong><?= strtoupper(htmlspecialchars($order['payment_method'])) ?></strong> (Selection recorded)
            </div>
        </div>

        <div style="display: flex; gap: 16px; justify-content: center;">
            <a href="<?= url('/shop') ?>" class="btn btn-primary"><?= __('start_shopping', 'Continue Shopping') ?></a>
            <?php if (is_logged_in()): ?>
                <a href="<?= url('/account/orders/' . $order['id']) ?>" class="btn btn-secondary">View Order Details</a>
            <?php endif; ?>
        </div>
    </div>
</div>
