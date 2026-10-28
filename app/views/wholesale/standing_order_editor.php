<?php $activeWholesaleTab = 'standing-orders'; require __DIR__ . '/subnav.php';
$quantities = array_column($orderItems, 'quantity', 'product_id');
$activeProductIds = array_column($allProducts, 'id');
$unavailableItems = array_filter($orderItems, fn($item) => !in_array($item['product_id'], $activeProductIds));
$defaultAddress = implode(', ', array_filter(array_map(fn($key) => $addresses[0][$key] ?? '', ['address_line1', 'address_line2', 'postcode', 'city', 'state']))); ?>
<div class="container portal-page">
    <h1><?= $order ? 'Edit Standing Order' : 'New Standing Order' ?></h1>
    <p class="portal-intro">Save your weekly supply schedule. Automatic recurring orders are not available yet.</p>
    <form action="<?= url('/wholesale/standing-orders/save') ?>" method="post" id="wholesale-quick-order-form">
        <?= csrf_field() ?>
        <input type="hidden" name="standing_order_id" value="<?= (int)($order['id'] ?? 0) ?>">
        <section class="portal-panel">
            <div class="portal-form-grid">
                <div class="form-group"><label for="schedule-name" class="form-label">Schedule Name</label><input id="schedule-name" name="schedule_name" class="form-input" value="<?= e($order['name'] ?? '') ?>" required maxlength="150"></div>
                <div class="form-group"><label for="delivery-day" class="form-label">Weekly Delivery Day</label><select id="delivery-day" name="weekly_delivery_day" class="form-select">
                    <?php foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day): ?><option <?= ($order['delivery_day'] ?? 'Monday') === $day ? 'selected' : '' ?>><?= $day ?></option><?php endforeach; ?>
                </select></div>
                <div class="form-group"><label for="start-date" class="form-label">Start Date</label><input type="date" id="start-date" name="start_date" class="form-input" value="<?= e($order['start_date'] ?? date('Y-m-d')) ?>" required></div>
                <div class="form-group"><label for="delivery-address" class="form-label">Delivery Address</label><input id="delivery-address" name="delivery_address" autocomplete="street-address" class="form-input" value="<?= e($order['delivery_address'] ?? $defaultAddress) ?>" required></div>
            </div>
            <h2>Scheduled Produce</h2>
            <?php if ($unavailableItems): ?>
                <div class="alert alert-error">
                    <p>Unavailable scheduled products: <?= e(implode(', ', array_column($unavailableItems, 'name_en'))) ?>.</p>
                    <label><input type="checkbox" name="remove_unavailable" value="1" required> Remove these unavailable products when saving this schedule.</label>
                </div>
            <?php endif; ?>
            <p>Enter whole pack quantities. Use 0 to leave a product out.</p>
            <div class="portal-table-scroll" role="region" aria-label="Scheduled produce quantities" tabindex="0">
                <table class="portal-table">
                    <thead><tr><th scope="col">Product</th><th scope="col">Pack</th><th scope="col">Wholesale Price</th><th scope="col">Quantity</th><th scope="col">Line Total</th></tr></thead>
                    <tbody>
                    <?php foreach ($allProducts as $product): ?>
                        <tr class="quick-order-row" data-price="<?= $product['wholesale_price'] ?>">
                            <th scope="row"><?= e($product['name_en']) ?></th><td><?= e($product['unit_weight']) ?></td><td><?= format_currency($product['wholesale_price']) ?></td>
                            <td><input type="number" name="products[<?= (int)$product['id'] ?>]" aria-label="<?= e($product['name_en']) ?> packs" class="form-input quick-order-qty" min="0" step="1" value="<?= (int)($quantities[$product['id']] ?? 0) ?>" style="width: 6rem;"></td>
                            <td class="quick-order-line-total"><?= format_currency($product['wholesale_price'] * ($quantities[$product['id']] ?? 0)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!$allProducts): ?><tr><td colspan="5">No products available for scheduling.</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
            <p>Estimated Produce Subtotal: <strong id="quick-order-grand-total" aria-live="polite"><?= format_currency(array_sum(array_map(fn($p) => $p['wholesale_price'] * ($quantities[$p['id']] ?? 0), $allProducts))) ?></strong>. Delivery and final prices are confirmed at checkout.</p>
            <div class="form-group"><label for="order-notes" class="form-label">Order Notes</label><textarea id="order-notes" name="order_notes" class="form-input" rows="3"><?= e($order['notes'] ?? '') ?></textarea></div>
            <button class="btn btn-primary" type="submit">Save Schedule</button>
            <a class="btn btn-secondary" href="<?= url('/wholesale/standing-orders') ?>">Back to Standing Orders</a>
        </section>
    </form>
    <?php if ($order): ?>
        <form action="<?= url('/wholesale/standing-orders/status') ?>" method="post" class="portal-actions">
            <?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$order['id'] ?>">
            <button class="btn btn-secondary" name="status" value="<?= $order['status'] === 'active' ? 'paused' : 'active' ?>"><?= $order['status'] === 'active' ? 'Pause Schedule' : 'Resume Schedule' ?></button>
            <?php if ($order['status'] !== 'cancelled'): ?><button class="btn btn-secondary" name="status" value="cancelled" onclick="return confirm('Cancel this standing order schedule?')">Cancel Schedule</button><?php endif; ?>
        </form>
    <?php endif; ?>
</div>
