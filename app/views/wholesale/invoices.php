<?php $activeWholesaleTab = 'invoices'; require __DIR__ . '/subnav.php'; ?>

<div class="container" style="padding-bottom: 60px;">
    <h1 style="font-size: 34px; margin-bottom: 24px;">Wholesale Invoices & Delivery Receipts</h1>

    <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
            <thead>
                <tr style="background: var(--warm-paper); border-bottom: 1px solid var(--border-color);">
                    <th style="padding: 14px 20px;">Invoice #</th>
                    <th style="padding: 14px 20px;">Billing Date</th>
                    <th style="padding: 14px 20px;">Order Reference</th>
                    <th style="padding: 14px 20px;">Amount</th>
                    <th style="padding: 14px 20px;">Status</th>
                    <th style="padding: 14px 20px; text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="6" style="padding: 32px; text-align: center; color: var(--text-muted);">No invoices available.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($orders as $o): ?>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 14px 20px; font-weight: 600;">INV-<?= htmlspecialchars(str_replace(['KC-', 'WH-'], '', $o['order_number'])) ?></td>
                            <td style="padding: 14px 20px; color: var(--text-muted);"><?= date('d M Y', strtotime($o['created_at'])) ?></td>
                            <td style="padding: 14px 20px;"><?= htmlspecialchars($o['order_number']) ?></td>
                            <td style="padding: 14px 20px; font-weight: 600;"><?= format_currency($o['total_amount']) ?></td>
                            <td style="padding: 14px 20px;">
                                <span class="badge badge-in-stock">Generated</span>
                            </td>
                            <td style="padding: 14px 20px; text-align: right;">
                                <a href="<?= url('/wholesale/invoice/' . $o['id']) ?>" target="_blank" class="btn btn-secondary" style="padding: 4px 12px; font-size: 12px;">Print / Download</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
