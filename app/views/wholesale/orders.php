<?php $activeWholesaleTab = 'orders'; require __DIR__ . '/subnav.php'; ?>

<div class="container" style="padding-bottom: 60px;">
    <h1 style="font-size: 34px; margin-bottom: 24px;">Wholesale Orders History</h1>

    <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); overflow: hidden;">
        <div class="portal-table-scroll" role="region" aria-label="Orders" tabindex="0"><table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
            <thead>
                <tr style="background: var(--warm-paper); border-bottom: 1px solid var(--border-color);">
                    <th style="padding: 14px 20px;">Order #</th>
                    <th style="padding: 14px 20px;">Date</th>
                    <th style="padding: 14px 20px;">Delivery Destination</th>
                    <th style="padding: 14px 20px;">Total</th>
                    <th style="padding: 14px 20px;">Status</th>
                    <th style="padding: 14px 20px; text-align: right;">Documents</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="6" style="padding: 32px; text-align: center; color: var(--text-muted);">No orders recorded yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($orders as $o): ?>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 14px 20px; font-weight: 600;"><?= htmlspecialchars($o['order_number']) ?></td>
                            <td style="padding: 14px 20px; color: var(--text-muted);"><?= date('d M Y, h:i A', strtotime($o['created_at'])) ?></td>
                            <td style="padding: 14px 20px;"><?= htmlspecialchars($o['delivery_city']) ?>, <?= htmlspecialchars($o['delivery_state']) ?></td>
                            <td style="padding: 14px 20px; font-weight: 600;"><?= format_currency($o['total_amount']) ?></td>
                            <td style="padding: 14px 20px;">
                                <span class="badge badge-in-stock"><?= ucfirst(htmlspecialchars($o['status'])) ?></span>
                            </td>
                            <td style="padding: 14px 20px; text-align: right;">
                                <a href="<?= url('/wholesale/invoice/' . $o['id']) ?>" target="_blank" class="btn btn-secondary" style="padding: 4px 10px; font-size: 12px;">View Invoice</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table></div>
    </div>
</div>
