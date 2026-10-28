<div class="container" style="padding-top: 32px; padding-bottom: 60px;">
    <h1 style="font-size: 34px; margin-bottom: 24px;"><?= __('shopping_cart', 'Shopping Bag') ?></h1>

    <?php if (empty($items)): ?>
        <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 80px 20px; text-align: center;">
            <p style="font-size: 18px; color: var(--text-muted); margin-bottom: 20px;"><?= __('empty_cart', 'Your shopping bag is empty.') ?></p>
            <a href="<?= url('/shop') ?>" class="btn btn-primary btn-lg"><?= __('start_shopping', 'Explore fresh produce') ?></a>
        </div>
    <?php else: ?>
        <div class="checkout-layout">
            <!-- Left: Items List -->
            <div>
                <form action="<?= url('/cart/update') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); overflow: hidden; margin-bottom: 24px;">
                        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                            <thead>
                                <tr style="background: var(--warm-paper); border-bottom: 1px solid var(--border-color);">
                                    <th style="padding: 14px 18px;"><?= __('item', 'Item') ?></th>
                                    <th style="padding: 14px 18px; text-align: center;"><?= __('price', 'Price') ?></th>
                                    <th style="padding: 14px 18px; text-align: center;"><?= __('quantity', 'Quantity') ?></th>
                                    <th style="padding: 14px 18px; text-align: right;"><?= __('total', 'Total') ?></th>
                                    <th style="padding: 14px 18px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $it): ?>
                                    <tr style="border-bottom: 1px solid var(--border-color);">
                                        <td style="padding: 16px 18px; display: flex; align-items: center; gap: 14px;">
                                            <img src="<?= asset('images/' . ($it['image'] ?: 'harvest.png')) ?>" alt="<?= trans($it, 'name') ?>" style="width: 56px; height: 56px; object-fit: cover; border-radius: var(--radius-sm);">
                                            <div>
                                                <a href="<?= url('/product/' . $it['slug']) ?>" style="font-weight: 600; font-size: 15px; color: var(--text-main);">
                                                    <?= trans($it, 'name') ?>
                                                </a>
                                                <div style="font-size: 13px; color: var(--text-muted);"><?= htmlspecialchars($it['unit_weight']) ?></div>
                                            </div>
                                        </td>
                                        <td style="padding: 16px 18px; text-align: center;">
                                            <?= format_currency($it['unit_price']) ?>
                                        </td>
                                        <td style="padding: 16px 18px; text-align: center;">
                                            <input type="number" name="quantities[<?= $it['item_id'] ?>]" value="<?= $it['quantity'] ?>" min="0" max="<?= $it['stock_quantity'] ?>" style="width: 64px; padding: 6px; text-align: center; border: 1px solid var(--border-color); border-radius: var(--radius-sm);">
                                        </td>
                                        <td style="padding: 16px 18px; text-align: right; font-weight: 700;">
                                            <?= format_currency($it['line_total']) ?>
                                        </td>
                                        <td style="padding: 16px 18px; text-align: center;">
                                            <button type="submit" name="quantities[<?= $it['item_id'] ?>]" value="0" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 16px;" title="Remove item">&times;</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div style="padding: 14px 18px; display: flex; justify-content: space-between; align-items: center; background: var(--warm-paper);">
                            <a href="<?= url('/shop') ?>" class="btn btn-secondary" style="font-size: 13px;">← Continue shopping</a>
                            <button type="submit" class="btn btn-secondary" style="font-size: 13px;">Update bag</button>
                        </div>
                    </div>
                </form>

                <!-- Promo Code Box -->
                <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 20px;">
                    <form action="<?= url('/cart/apply-promo') ?>" method="POST" style="display: flex; gap: 12px; align-items: center;">
                        <?= csrf_field() ?>
                        <div style="flex: 1;">
                            <input type="text" name="promo_code" class="form-input" placeholder="Promo code (e.g. FRESH10)" value="<?= htmlspecialchars($totals['promo']['code'] ?? '') ?>" required>
                        </div>
                        <button type="submit" class="btn btn-secondary"><?= __('apply', 'Apply') ?></button>
                    </form>
                </div>
            </div>

            <!-- Right: Order Summary -->
            <div>
                <div class="order-summary-card">
                    <h3 style="font-size: 20px; margin-bottom: 20px;">Order summary</h3>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px;">
                        <span><?= __('subtotal', 'Subtotal') ?></span>
                        <strong><?= format_currency($totals['subtotal']) ?></strong>
                    </div>

                    <?php if ($totals['discount'] > 0): ?>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; color: var(--primary-green);">
                            <span><?= __('discount', 'Promo') ?> (<?= htmlspecialchars($totals['promo']['code']) ?>)</span>
                            <strong>– <?= format_currency($totals['discount']) ?></strong>
                        </div>
                    <?php endif; ?>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 18px; font-size: 14px;">
                        <span><?= __('shipping', 'Flat shipping') ?></span>
                        <strong><?= format_currency($totals['shipping']) ?></strong>
                    </div>

                    <div style="border-top: 1px solid var(--border-color); padding-top: 16px; margin-bottom: 24px; display: flex; justify-content: space-between; font-size: 20px; font-weight: 700;">
                        <span><?= __('total', 'Total') ?></span>
                        <span style="color: var(--primary-green);"><?= format_currency($totals['total']) ?></span>
                    </div>

                    <a href="<?= url('/checkout') ?>" class="btn btn-primary btn-block btn-lg"><?= __('btn_checkout', 'Proceed to checkout') ?></a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
