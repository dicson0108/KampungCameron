<div>
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Orders</h1>
            <p style="color: var(--admin-text-muted); font-size: 14px;">Manage store orders, delivery slips and fulfillment statuses.</p>
        </div>
    </div>

    <!-- Status Filter Tabs -->
    <div style="display: flex; gap: 8px; margin-bottom: 24px; overflow-x: auto;">
        <a href="<?= url('/admin/orders') ?>" class="btn <?= empty($selectedStatus) ? 'btn-primary' : 'btn-secondary' ?>" style="font-size: 13px; padding: 6px 14px;">All Orders</a>
        <a href="<?= url('/admin/orders?status=pending') ?>" class="btn <?= $selectedStatus === 'pending' ? 'btn-primary' : 'btn-secondary' ?>" style="font-size: 13px; padding: 6px 14px;">Pending</a>
        <a href="<?= url('/admin/orders?status=processing') ?>" class="btn <?= $selectedStatus === 'processing' ? 'btn-primary' : 'btn-secondary' ?>" style="font-size: 13px; padding: 6px 14px;">Processing</a>
        <a href="<?= url('/admin/orders?status=out_for_delivery') ?>" class="btn <?= $selectedStatus === 'out_for_delivery' ? 'btn-primary' : 'btn-secondary' ?>" style="font-size: 13px; padding: 6px 14px;">Out for delivery</a>
        <a href="<?= url('/admin/orders?status=delivered') ?>" class="btn <?= $selectedStatus === 'delivered' ? 'btn-primary' : 'btn-secondary' ?>" style="font-size: 13px; padding: 6px 14px;">Delivered</a>
        <a href="<?= url('/admin/orders?status=cancelled') ?>" class="btn <?= $selectedStatus === 'cancelled' ? 'btn-primary' : 'btn-secondary' ?>" style="font-size: 13px; padding: 6px 14px;">Cancelled</a>
    </div>

    <div class="admin-card">
        <div class="admin-table-scroll" role="region" aria-label="Orders data table" tabindex="0"><table class="admin-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Customer</th>
                    <th>Destination</th>
                    <th>Payment</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $ord): ?>
                    <tr>
                        <td style="font-weight: 600;"><?= htmlspecialchars($ord['order_number']) ?></td>
                        <td style="color: var(--admin-text-muted);"><?= date('d M Y, h:i A', strtotime($ord['created_at'])) ?></td>
                        <td>
                            <span class="badge <?= $ord['order_type'] === 'wholesale' ? 'badge-low-stock' : 'badge-in-stock' ?>">
                                <?= ucfirst(htmlspecialchars($ord['order_type'])) ?>
                            </span>
                        </td>
                        <td>
                            <div style="font-weight: 500;"><?= htmlspecialchars($ord['customer_name']) ?></div>
                            <div style="font-size: 12px; color: var(--admin-text-muted);"><?= htmlspecialchars($ord['customer_email']) ?></div>
                        </td>
                        <td><?= htmlspecialchars($ord['delivery_city']) ?>, <?= htmlspecialchars($ord['delivery_state']) ?></td>
                        <td><?= strtoupper(htmlspecialchars($ord['payment_method'])) ?></td>
                        <td style="font-weight: 600;"><?= format_currency($ord['total_amount']) ?></td>
                        <td>
                            <span class="badge <?= $ord['status'] === 'delivered' ? 'badge-in-stock' : ($ord['status'] === 'cancelled' ? 'badge-sold-out' : 'badge-low-stock') ?>">
                                <?= ucfirst(str_replace('_', ' ', htmlspecialchars($ord['status']))) ?>
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <a href="<?= url('/admin/orders/view?id=' . $ord['id']) ?>" class="btn btn-secondary" style="padding: 4px 10px; font-size: 12px;">Manage</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table></div>
    </div>
</div>
