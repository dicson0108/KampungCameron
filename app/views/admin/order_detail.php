<div>
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Order <?= htmlspecialchars($order['order_number']) ?></h1>
            <a href="<?= url('/admin/orders') ?>" style="font-size: 13px; color: var(--admin-text-muted);">← Back to orders list</a>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="<?= url('/admin/orders/invoice?id=' . $order['id']) ?>" target="_blank" class="btn btn-secondary">Print Delivery Slip / Invoice ↗</a>
        </div>
    </div>

    <div class="admin-order-layout">
        <!-- Left: Items & Customer Details -->
        <div>
            <div class="admin-card" style="padding: 24px; margin-bottom: 24px;">
                <h3 style="font-size: 18px; margin-bottom: 16px;">Produce Line Items</h3>
                <div class="admin-table-scroll" role="region" aria-label="Order Detail data table" tabindex="0"><table class="admin-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Pack</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
                            <th style="text-align: right;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $it): ?>
                            <tr>
                                <td style="font-weight: 600;"><?= htmlspecialchars($it['product_name']) ?></td>
                                <td><?= htmlspecialchars($it['unit_weight']) ?></td>
                                <td><?= format_currency($it['unit_price']) ?></td>
                                <td><?= $it['quantity'] ?></td>
                                <td style="text-align: right; font-weight: 600;"><?= format_currency($it['subtotal']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table></div>

                <div style="max-width: 280px; margin-left: auto; margin-top: 20px; font-size: 14px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                        <span>Subtotal:</span>
                        <span><?= format_currency($order['subtotal']) ?></span>
                    </div>
                    <?php if ($order['discount_amount'] > 0): ?>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 6px; color: var(--primary-green);">
                            <span>Discount:</span>
                            <span>– <?= format_currency($order['discount_amount']) ?></span>
                        </div>
                    <?php endif; ?>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span>Flat Shipping:</span>
                        <span><?= format_currency($order['shipping_fee']) ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 18px; font-weight: 700; border-top: 1px solid var(--admin-border); padding-top: 8px;">
                        <span>Total:</span>
                        <span style="color: var(--primary-green);"><?= format_currency($order['total_amount']) ?></span>
                    </div>
                </div>
            </div>

            <!-- Customer Destination Details -->
            <div class="admin-card" style="padding: 24px;">
                <h3 style="font-size: 18px; margin-bottom: 14px;">Customer & Delivery Address</h3>
                <div style="font-size: 14px; line-height: 1.6;">
                    <div><strong>Name:</strong> <?= htmlspecialchars($order['customer_name']) ?></div>
                    <div><strong>Email:</strong> <?= htmlspecialchars($order['customer_email']) ?></div>
                    <div><strong>Phone:</strong> <?= htmlspecialchars($order['customer_phone']) ?></div>
                    <div style="margin-top: 8px;"><strong>Address:</strong> <?= htmlspecialchars($order['delivery_address']) ?><?= $order['delivery_line2'] ? ', ' . htmlspecialchars($order['delivery_line2']) : '' ?></div>
                    <div><strong>City/State:</strong> <?= htmlspecialchars($order['delivery_postcode']) ?> <?= htmlspecialchars($order['delivery_city']) ?>, <?= htmlspecialchars($order['delivery_state']) ?></div>
                    <div style="margin-top: 8px;"><strong>Delivery Notes:</strong> <?= htmlspecialchars($order['delivery_notes'] ?: 'None') ?></div>
                </div>
            </div>
        </div>

        <!-- Right: Status Workflow Form -->
        <div>
            <div class="admin-card" style="padding: 24px;">
                <h3 style="font-size: 18px; margin-bottom: 16px;">Update Order Status</h3>
                <form action="<?= url('/admin/orders/status') ?>" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= $order['id'] ?>">

                    <div class="form-group">
                        <label class="form-label" for="order_detail-status">Current Fulfillment Status</label>
                    <select id="order_detail-status" name="status" class="form-select">
                            <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending Review</option>
                            <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>Processing / Packing Cold</option>
                            <option value="out_for_delivery" <?= $order['status'] === 'out_for_delivery' ? 'selected' : '' ?>>Out for Delivery</option>
                            <option value="delivered" <?= $order['status'] === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                            <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled (Restores Stock)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Update Status</button>
                </form>

                <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--admin-border); font-size: 13px; color: var(--admin-text-muted);">
                    <div>Payment Selection: <strong><?= strtoupper(htmlspecialchars($order['payment_method'])) ?></strong></div>
                    <div>Order Placed: <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
