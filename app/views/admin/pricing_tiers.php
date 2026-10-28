<div>
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Wholesale Pricing Tiers</h1>
            <p style="color: var(--admin-text-muted); font-size: 14px;">Define percentage bulk discounts and minimum spend thresholds for B2B accounts.</p>
        </div>
        <a href="<?= url('/admin/wholesale') ?>" class="btn btn-secondary">← Back to Wholesale Accounts</a>
    </div>

    <div style="display: grid; grid-template-columns: 1.4fr 1fr; gap: 32px;">
        <div class="admin-card">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Tier Name</th>
                        <th>Code</th>
                        <th>Discount %</th>
                        <th>Min Spend</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tiers as $t): ?>
                        <tr>
                            <td style="font-weight: 600;"><?= htmlspecialchars($t['name']) ?></td>
                            <td><code><?= htmlspecialchars($t['code']) ?></code></td>
                            <td style="font-weight: 700; color: var(--primary-green);"><?= number_format($t['discount_percent'], 1) ?>%</td>
                            <td><?= format_currency($t['min_spend']) ?></td>
                            <td style="font-size: 13px; color: var(--admin-text-muted);"><?= htmlspecialchars($t['description']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="admin-card" style="padding: 24px;">
            <h3 style="font-size: 18px; margin-bottom: 16px;">Add / Update Pricing Tier</h3>
            <form action="<?= url('/admin/wholesale/tiers/save') ?>" method="POST">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label class="form-label">Tier Name *</label>
                    <input type="text" name="name" class="form-input" placeholder="e.g. Tier D · Corporate" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Code Identifier *</label>
                    <input type="text" name="code" class="form-input" placeholder="e.g. tier_d" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                    <div class="form-group">
                        <label class="form-label">Discount % *</label>
                        <input type="number" step="0.5" name="discount_percent" class="form-input" placeholder="30" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Min Spend (RM)</label>
                        <input type="number" step="10" name="min_spend" class="form-input" placeholder="1500">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <input type="text" name="description" class="form-input" placeholder="e.g. Hotel chains">
                </div>

                <button type="submit" class="btn btn-primary btn-block">Save Tier</button>
            </form>
        </div>
    </div>
</div>
