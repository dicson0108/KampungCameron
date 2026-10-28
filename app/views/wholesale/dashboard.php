<?php $activeWholesaleTab = 'dashboard'; require __DIR__ . '/subnav.php'; ?>

<div class="container" style="padding-bottom: 60px;">
    <!-- Tier Badge & Title -->
    <div style="margin-bottom: 28px;">
        <span class="badge" style="background: #eef6f9; color: var(--service-blue-dark); font-size: 12px; padding: 4px 10px; margin-bottom: 8px;">
            APPROVED ACCOUNT · <?= strtoupper(htmlspecialchars($wsAccount['tier_name'] ?? 'Tier A')) ?> (<?= number_format($wsAccount['discount_percent'] ?? 15, 0) ?>% OFF)
        </span>
        <h1 style="font-size: 34px;"><?= htmlspecialchars($wsAccount['business_name']) ?></h1>
        <p style="color: var(--text-muted); font-size: 14px;">Business Reg: <?= htmlspecialchars($wsAccount['registration_number']) ?> · Contact: <?= htmlspecialchars($wsAccount['contact_person']) ?></p>
    </div>

    <!-- Quick Action Cards -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 40px;">
        <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 24px;">
            <h3 style="font-size: 18px; margin-bottom: 6px;">Quick Bulk Order</h3>
            <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 16px;">Key in multi-item quantities for fast order dispatch.</p>
            <a href="<?= url('/wholesale/quick-order') ?>" class="btn btn-primary btn-block">Open quick order</a>
        </div>

        <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 24px;">
            <h3 style="font-size: 18px; margin-bottom: 6px;">Active Standing Order</h3>
            <?php if ($standingOrder): ?>
                <p style="font-size: 13px; color: var(--primary-green); font-weight: 600; margin-bottom: 4px;">● <?= htmlspecialchars($standingOrder['name']) ?></p>
                <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 16px;">Every <?= htmlspecialchars($standingOrder['delivery_day']) ?> delivery</p>
                <a href="<?= url('/wholesale/standing-orders/edit?id=' . $standingOrder['id']) ?>" class="btn btn-secondary btn-block">Manage schedule</a>
            <?php else: ?>
                <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 16px;">No recurring delivery schedule set up yet.</p>
                <a href="<?= url('/wholesale/standing-orders/edit') ?>" class="btn btn-secondary btn-block">Set up standing order</a>
            <?php endif; ?>
        </div>

        <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 24px;">
            <h3 style="font-size: 18px; margin-bottom: 6px;">Catalogue & Pricing</h3>
            <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 16px;">Browse full fresh produce line with your tier pricing.</p>
            <a href="<?= url('/wholesale/catalog') ?>" class="btn btn-secondary btn-block">View wholesale catalogue</a>
        </div>
    </div>

    <!-- Recent Wholesale Orders -->
    <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); overflow: hidden;">
        <div style="padding: 18px 24px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
            <h2 style="font-size: 20px;">Recent Wholesale Orders</h2>
            <a href="<?= url('/wholesale/orders') ?>" style="font-size: 13px; font-weight: 600; color: var(--primary-green);">View all orders →</a>
        </div>

        <?php if (empty($recentOrders)): ?>
            <div style="padding: 32px; text-align: center; color: var(--text-muted);">
                No previous orders. Place your first bulk order with the quick order form!
            </div>
        <?php else: ?>
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                <thead>
                    <tr style="background: var(--warm-paper); border-bottom: 1px solid var(--border-color);">
                        <th style="padding: 12px 20px;">Order #</th>
                        <th style="padding: 12px 20px;">Date</th>
                        <th style="padding: 12px 20px;">Total</th>
                        <th style="padding: 12px 20px;">Status</th>
                        <th style="padding: 12px 20px; text-align: right;">Invoice</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentOrders as $ord): ?>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 14px 20px; font-weight: 600;"><?= htmlspecialchars($ord['order_number']) ?></td>
                            <td style="padding: 14px 20px; color: var(--text-muted);"><?= date('d M Y', strtotime($ord['created_at'])) ?></td>
                            <td style="padding: 14px 20px; font-weight: 600;"><?= format_currency($ord['total_amount']) ?></td>
                            <td style="padding: 14px 20px;">
                                <span class="badge badge-in-stock"><?= ucfirst(htmlspecialchars($ord['status'])) ?></span>
                            </td>
                            <td style="padding: 14px 20px; text-align: right;">
                                <a href="<?= url('/wholesale/invoice/' . $ord['id']) ?>" target="_blank" class="btn btn-secondary" style="padding: 4px 10px; font-size: 12px;">View PDF</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
