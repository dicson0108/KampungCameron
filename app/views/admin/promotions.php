<div>
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Promotions & Promo Codes</h1>
            <p style="color: var(--admin-text-muted); font-size: 14px;">Create seasonal coupon codes and harvest discounts.</p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1.4fr 1fr; gap: 32px;">
        <div class="admin-card">
            <div class="admin-table-scroll" role="region" aria-label="Promotions data table" tabindex="0"><table class="admin-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Type</th>
                        <th>Discount</th>
                        <th>Min Order</th>
                        <th>Usage</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($promos as $pr): ?>
                        <tr>
                            <td style="font-weight: 700; color: var(--primary-green);"><code><?= htmlspecialchars($pr['code']) ?></code></td>
                            <td><?= ucfirst(htmlspecialchars($pr['discount_type'])) ?></td>
                            <td style="font-weight: 600;">
                                <?= $pr['discount_type'] === 'percentage' ? (int)$pr['discount_value'] . '%' : format_currency($pr['discount_value']) ?>
                            </td>
                            <td><?= format_currency($pr['min_order_amount']) ?></td>
                            <td><?= $pr['uses_count'] ?> / <?= $pr['max_uses'] ?? '∞' ?></td>
                            <td>
                                <span class="badge badge-in-stock">Active</span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table></div>
        </div>

        <div class="admin-card" style="padding: 24px;">
            <h3 style="font-size: 18px; margin-bottom: 16px;">Create Promo Code</h3>
            <form action="<?= url('/admin/promotions/save') ?>" method="POST">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label class="form-label" for="promotions-code">Promo Code (Uppercase) *</label>
                    <input id="promotions-code" type="text" name="code" class="form-input" placeholder="e.g. HARVEST20" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                    <div class="form-group">
                        <label class="form-label" for="promotions-discount_type">Discount Type</label>
                    <select id="promotions-discount_type" name="discount_type" class="form-select">
                            <option value="percentage">Percentage (%)</option>
                            <option value="fixed">Fixed Amount (RM)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="promotions-discount_value">Discount Value *</label>
                    <input id="promotions-discount_value" type="number" step="0.5" name="discount_value" class="form-input" placeholder="10" required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                    <div class="form-group">
                        <label class="form-label" for="promotions-min_order_amount">Min Spend (RM)</label>
                    <input id="promotions-min_order_amount" type="number" step="1" name="min_order_amount" class="form-input" value="20">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="promotions-max_uses">Max Uses Limit</label>
                    <input id="promotions-max_uses" type="number" name="max_uses" class="form-input" placeholder="500">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Save Promo Code</button>
            </form>
        </div>
    </div>
</div>
