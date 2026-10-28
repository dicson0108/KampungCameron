<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice <?= htmlspecialchars($order['order_number']) ?> — Kampung Cameron</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; margin: 40px; color: #1e293b; line-height: 1.5; font-size: 14px; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #184e68; padding-bottom: 20px; margin-bottom: 30px; }
        .brand { font-size: 24px; font-weight: bold; color: #184e68; font-family: serif; }
        .inv-title { font-size: 28px; font-weight: bold; text-align: right; }
        .meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { background: #f8fafc; padding: 10px; border-bottom: 2px solid #e2e8f0; text-align: left; }
        td { padding: 10px; border-bottom: 1px solid #e2e8f0; }
        .totals { float: right; width: 300px; }
        .total-row { display: flex; justify-content: space-between; margin-bottom: 8px; }
        .grand-total { font-size: 18px; font-weight: bold; border-top: 2px solid #184e68; padding-top: 8px; color: #2d6a4f; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #2d6a4f; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Print / Save as PDF</button>
    </div>

    <div class="header">
        <div>
            <div class="brand">Kampung Cameron</div>
            <div>Cameron Highlands Fresh Produce Supply</div>
            <div>Brinchang, 39100 Cameron Highlands, Pahang</div>
            <div>Registration: 202601009988 (198271-K)</div>
            <div>WhatsApp: +60 12 345 6789</div>
        </div>
        <div>
            <div class="inv-title">TAX INVOICE</div>
            <div><strong>Invoice No:</strong> INV-<?= htmlspecialchars(str_replace(['KC-', 'WH-'], '', $order['order_number'])) ?></div>
            <div><strong>Order Ref:</strong> <?= htmlspecialchars($order['order_number']) ?></div>
            <div><strong>Date:</strong> <?= date('d M Y', strtotime($order['created_at'])) ?></div>
            <div><strong>Status:</strong> <?= strtoupper(htmlspecialchars($order['status'])) ?></div>
        </div>
    </div>

    <div class="meta-grid">
        <div>
            <h4 style="margin-bottom: 6px; text-transform: uppercase; font-size: 12px; color: #64748b;">Billed & Delivered To:</h4>
            <div style="font-size: 16px; font-weight: bold;"><?= htmlspecialchars($wsAccount['business_name'] ?? $order['customer_name']) ?></div>
            <div>SSM: <?= htmlspecialchars($wsAccount['registration_number'] ?? 'N/A') ?></div>
            <div>Attn: <?= htmlspecialchars($order['customer_name']) ?> (<?= htmlspecialchars($order['customer_phone']) ?>)</div>
            <div><?= htmlspecialchars($order['delivery_address']) ?>, <?= htmlspecialchars($order['delivery_city']) ?>, <?= htmlspecialchars($order['delivery_state']) ?> <?= htmlspecialchars($order['delivery_postcode']) ?></div>
        </div>
        <div>
            <h4 style="margin-bottom: 6px; text-transform: uppercase; font-size: 12px; color: #64748b;">Supply Terms:</h4>
            <div>Wholesale Pricing Tier: <strong><?= htmlspecialchars($wsAccount['tier_name'] ?? 'Tier A') ?> (<?= $wsAccount['discount_percent'] ?? 15 ?>% off)</strong></div>
            <div>Payment Channel: <strong><?= strtoupper(htmlspecialchars($order['payment_method'])) ?></strong></div>
            <div>Notes: <?= htmlspecialchars($order['delivery_notes'] ?: 'None') ?></div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th>Pack Weight</th>
                <th style="text-align: right;">Unit Price</th>
                <th style="text-align: center;">Qty</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $it): ?>
                <tr>
                    <td><?= htmlspecialchars($it['product_name']) ?></td>
                    <td><?= htmlspecialchars($it['unit_weight']) ?></td>
                    <td style="text-align: right;"><?= format_currency($it['unit_price']) ?></td>
                    <td style="text-align: center;"><?= $it['quantity'] ?></td>
                    <td style="text-align: right;"><?= format_currency($it['subtotal']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="totals">
        <div class="total-row">
            <span>Subtotal:</span>
            <span><?= format_currency($order['subtotal']) ?></span>
        </div>
        <?php if ($order['discount_amount'] > 0): ?>
            <div class="total-row" style="color: #2d6a4f;">
                <span>Discount (<?= htmlspecialchars($order['promo_code'] ?? 'Promo') ?>):</span>
                <span>– <?= format_currency($order['discount_amount']) ?></span>
            </div>
        <?php endif; ?>
        <div class="total-row">
            <span>Flat Delivery Fee:</span>
            <span><?= format_currency($order['shipping_fee']) ?></span>
        </div>
        <div class="total-row grand-total">
            <span>Total Payable:</span>
            <span><?= format_currency($order['total_amount']) ?></span>
        </div>
    </div>

    <div style="clear: both; padding-top: 40px; font-size: 12px; color: #64748b; border-top: 1px solid #e2e8f0; margin-top: 60px;">
        <p>This is a computer-generated invoice. Fresh highlands produce guaranteed inspected and packed cold upon dispatch.</p>
    </div>
</body>
</html>
