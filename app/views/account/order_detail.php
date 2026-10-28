<div class="container" style="padding-top: 32px; padding-bottom: 60px; max-width: 800px;">
    <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 24px;">
        <div>
            <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 4px;">
                <a href="<?= url('/account/orders') ?>">← All orders</a>
            </div>
            <h1 style="font-size: 30px;">Order <?= htmlspecialchars($order['order_number']) ?></h1>
        </div>
        <span class="badge badge-in-stock" style="padding: 6px 14px; font-size: 14px;"><?= ucfirst(htmlspecialchars($order['status'])) ?></span>
    </div>

    <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 32px; margin-bottom: 32px;">
        <!-- Order Meta Details -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; padding-bottom: 20px; border-bottom: 1px solid var(--border-color); margin-bottom: 24px; font-size: 14px;">
            <div>
                <span style="color: var(--text-muted); font-size: 12px; text-transform: uppercase; font-weight: 700;">Delivery Information</span>
                <div style="font-weight: 600; margin-top: 6px;"><?= htmlspecialchars($order['customer_name']) ?></div>
                <div style="color: var(--text-muted);"><?= htmlspecialchars($order['customer_phone']) ?></div>
                <div style="margin-top: 4px;"><?= htmlspecialchars($order['delivery_address']) ?>, <?= htmlspecialchars($order['delivery_city']) ?></div>
            </div>
            <div>
                <span style="color: var(--text-muted); font-size: 12px; text-transform: uppercase; font-weight: 700;">Order Details</span>
                <div style="margin-top: 6px;">Placed: <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></div>
                <div>Payment Selection: <strong><?= strtoupper(htmlspecialchars($order['payment_method'])) ?></strong></div>
                <div>Notes: <?= htmlspecialchars($order['delivery_notes'] ?: 'None') ?></div>
            </div>
        </div>

        <!-- Ordered Items -->
        <h3 style="font-size: 18px; margin-bottom: 16px;">Produce in this order</h3>
        <div style="margin-bottom: 24px;">
            <?php foreach ($items as $it): ?>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid var(--border-color);">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <img src="<?= asset('images/' . ($it['image'] ?: 'harvest.png')) ?>" alt="<?= htmlspecialchars($it['product_name']) ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: var(--radius-sm);">
                        <div>
                            <div style="font-weight: 600;"><?= htmlspecialchars($it['product_name']) ?></div>
                            <div style="font-size: 13px; color: var(--text-muted);"><?= htmlspecialchars($it['unit_weight']) ?> · <?= format_currency($it['unit_price']) ?> × <?= $it['quantity'] ?></div>
                        </div>
                    </div>
                    <div style="font-weight: 700;"><?= format_currency($it['subtotal']) ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Totals Breakdown -->
        <div style="max-width: 320px; margin-left: auto; font-size: 14px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                <span>Subtotal</span>
                <span><?= format_currency($order['subtotal']) ?></span>
            </div>
            <?php if ($order['discount_amount'] > 0): ?>
                <div style="display: flex; justify-content: space-between; margin-bottom: 6px; color: var(--primary-green);">
                    <span>Promo (<?= htmlspecialchars($order['promo_code']) ?>)</span>
                    <span>– <?= format_currency($order['discount_amount']) ?></span>
                </div>
            <?php endif; ?>
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <span>Flat shipping</span>
                <span><?= format_currency($order['shipping_fee']) ?></span>
            </div>
            <div style="border-top: 2px solid var(--border-color); padding-top: 10px; display: flex; justify-content: space-between; font-size: 18px; font-weight: 700;">
                <span>Total</span>
                <span style="color: var(--primary-green);"><?= format_currency($order['total_amount']) ?></span>
            </div>
        </div>
    </div>
</div>
