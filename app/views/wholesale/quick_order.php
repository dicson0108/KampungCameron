<?php $activeWholesaleTab = 'quick-order'; require __DIR__ . '/subnav.php'; ?>

<div class="container" style="padding-bottom: 60px;">
    <div style="margin-bottom: 28px;">
        <span class="badge" style="background: #eef6f9; color: var(--service-blue-dark); font-size: 12px; padding: 4px 10px; margin-bottom: 8px;">
            <?= strtoupper(htmlspecialchars($wsAccount['tier_name'] ?? 'Tier A')) ?> (<?= number_format($wsAccount['discount_percent'] ?? 15, 0) ?>% DISCOUNT APPLIED)
        </span>
        <h1 style="font-size: 34px;">Quick Order Sheet</h1>
        <p style="color: var(--text-muted); font-size: 14px;">Enter quantities for items required. Orders are checked against daily fresh harvest stock.</p>
    </div>

    <form id="wholesale-quick-order-form" action="<?= url('/wholesale/quick-order/submit') ?>" method="POST">
        <?= csrf_field() ?>

        <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); overflow: hidden; margin-bottom: 24px;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                <thead>
                    <tr style="background: var(--warm-paper); border-bottom: 1px solid var(--border-color);">
                        <th style="padding: 14px 20px;">Produce</th>
                        <th style="padding: 14px 20px;">Pack Weight</th>
                        <th style="padding: 14px 20px;">Wholesale Price</th>
                        <th style="padding: 14px 20px;">Stock</th>
                        <th style="padding: 14px 20px; text-align: center; width: 120px;">Order Qty</th>
                        <th style="padding: 14px 20px; text-align: right;">Line Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                        <tr class="quick-order-row" data-price="<?= $p['wholesale_price'] ?>" style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 14px 20px; font-weight: 600;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <img src="<?= asset('images/' . ($p['image'] ?: 'harvest.png')) ?>" alt="<?= htmlspecialchars($p['name_en']) ?>" style="width: 44px; height: 44px; object-fit: cover; border-radius: var(--radius-sm);">
                                    <div>
                                        <div><?= htmlspecialchars($p['name_en']) ?></div>
                                        <div style="font-size: 12px; color: var(--text-muted);"><?= htmlspecialchars($p['name_zh']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 14px 20px; color: var(--text-muted);"><?= htmlspecialchars($p['unit_weight']) ?></td>
                            <td style="padding: 14px 20px; font-weight: 600; color: var(--primary-green);">
                                <?= format_currency($p['wholesale_price']) ?>
                            </td>
                            <td style="padding: 14px 20px;">
                                <span class="badge <?= $p['stock_quantity'] <= 5 ? 'badge-low-stock' : 'badge-in-stock' ?>">
                                    <?= $p['stock_quantity'] ?> packs
                                </span>
                            </td>
                            <td style="padding: 14px 20px; text-align: center;">
                                <input type="number" name="items[<?= $p['id'] ?>]" class="form-input quick-order-qty" min="0" max="<?= $p['stock_quantity'] ?>" value="0" style="width: 80px; text-align: center; padding: 6px;">
                            </td>
                            <td style="padding: 14px 20px; text-align: right; font-weight: 700;" class="quick-order-line-total">
                                RM 0.00
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Bottom Floating Action Bar -->
            <div style="padding: 20px 24px; background: var(--warm-paper); display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <span style="font-size: 13px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Estimated Produce Subtotal:</span>
                    <span id="quick-order-grand-total" style="font-size: 22px; font-weight: 700; color: var(--primary-green); margin-left: 8px;">RM 0.00</span>
                </div>
                <button type="submit" class="btn btn-primary btn-lg">Add to Cart & Checkout →</button>
            </div>
        </div>
    </form>
</div>
