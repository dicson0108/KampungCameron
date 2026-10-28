<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delivery Slip / Invoice <?= htmlspecialchars($order['order_number']) ?></title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; margin: 40px; color: #1e293b; line-height: 1.5; font-size: 14px; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #2d6a4f; padding-bottom: 20px; margin-bottom: 30px; }
        .brand { font-size: 24px; font-weight: bold; color: #2d6a4f; font-family: serif; }
        .inv-title { font-size: 26px; font-weight: bold; text-align: right; }
        .meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { background: #f8fafc; padding: 10px; border-bottom: 2px solid #e2e8f0; text-align: left; }
        td { padding: 10px; border-bottom: 1px solid #e2e8f0; }
        .totals { float: right; width: 300px; }
        .total-row { display: flex; justify-content: space-between; margin-bottom: 8px; }
        .grand-total { font-size: 18px; font-weight: bold; border-top: 2px solid #2d6a4f; padding-top: 8px; color: #2d6a4f; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #2d6a4f; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Print Delivery Slip</button>
    </div>

    <div class="header">
        <div>
            <div class="brand">Kampung Cameron</div>
            <div>Highlands Fresh Produce Supply & Dispatch</div>
            <div>Brinchang, 39100 Cameron Highlands, Pahang</div>
            <div>Customer Care: +60 12 345 6789</div>
        </div>
        <div>
            <div class="inv-title">DELIVERY SLIP</div>
            <div><strong>Order No:</strong> <?= htmlspecialchars($order['order_number']) ?></div>
            <div><strong>Date:</strong> <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></div>
            <div><strong>Status:</strong> <?= strtoupper(htmlspecialchars($order['status'])) ?></div>
        </div>
    </div>

    <div class="meta-grid">
        <div>
            <h4 style="margin-bottom: 6px; text-transform: uppercase; font-size: 12px; color: #64748b;">Deliver To:</h4>
            <div style="font-size: 16px; font-weight: bold;"><?= htmlspecialchars($order['customer_name']) ?></div>
            <div>Phone: <?= htmlspecialchars($order['customer_phone']) ?></div>
            <div>Email: <?= htmlspecialchars($order['customer_email']) ?></div>
            <div><?= htmlspecialchars($order['delivery_address']) ?><?= $order['delivery_line2'] ? ', ' . htmlspecialchars($order['delivery_line2']) : '' ?></div>
            <div><?= htmlspecialchars($order['delivery_postcode']) ?> <?= htmlspecialchars($order['delivery_city']) ?>, <?= htmlspecialchars($order['delivery_state']) ?></div>
        </div>
        <div>
            <h4 style="margin-bottom: 6px; text-transform: uppercase; font-size: 12px; color: #64748b;">Fulfillment Instructions:</h4>
            <div>Payment Channel: <strong><?= strtoupper(htmlspecialchars($order['payment_method'])) ?></strong></div>
            <div>Special Delivery Notes: <em><?= htmlspecialchars($order['delivery_notes'] ?: 'Standard doorstep delivery') ?></em></div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Produce Item</th>
                <th>Pack Unit</th>
                <th style="text-align: right;">Unit Price</th>
                <th style="text-align: center;">Quantity</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $it): ?>
                <tr>
                    <td style="font-weight: bold;"><?= htmlspecialchars($it['product_name']) ?></td>
                    <td><?= htmlspecialchars($it['unit_weight']) ?></td>
                    <td style="text-align: right;"><?= format_currency($it['unit_price']) ?></td>
                    <td style="text-align: center; font-size: 16px; font-weight: bold;"><?= $it['quantity'] ?></td>
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
            <span>Flat Shipping:</span>
            <span><?= format_currency($order['shipping_fee']) ?></span>
        </div>
        <div class="total-row grand-total">
            <span>Total Payable:</span>
            <span><?= format_currency($order['total_amount']) ?></span>
        </div>
    </div>
</body>
</html>
