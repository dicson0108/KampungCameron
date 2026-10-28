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
            <div class="admin-table-scroll" role="region" aria-label="Pricing Tiers data table" tabindex="0"><table class="admin-table">
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
                            <td style="font-weight: 600;"><a href="<?= url('/admin/wholesale/tiers?id=' . (int)$t['id']) ?>"><?= htmlspecialchars($t['name']) ?></a></td>
                            <td><code><?= htmlspecialchars($t['code']) ?></code></td>
                            <td style="font-weight: 700; color: var(--primary-green);"><?= number_format($t['discount_percent'], 1) ?>%</td>
                            <td><?= format_currency($t['min_spend']) ?></td>
                            <td style="font-size: 13px; color: var(--admin-text-muted);"><?= htmlspecialchars($t['description']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table></div>
        </div>

        <div class="admin-card" style="padding: 24px;">
            <h3 style="font-size: 18px; margin-bottom: 16px;"><?= $editTier ? 'Edit Pricing Tier' : 'Add Pricing Tier' ?></h3>
            <form action="<?= url('/admin/wholesale/tiers/save') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= (int)($editTier['id'] ?? 0) ?>">

                <div class="form-group">
                    <label class="form-label" for="pricing_tiers-name">Tier Name *</label>
                    <input id="pricing_tiers-name" type="text" name="name" class="form-input" value="<?= e($editTier['name'] ?? '') ?>" placeholder="e.g. Tier D · Corporate" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="pricing_tiers-code">Code Identifier *</label>
                    <input id="pricing_tiers-code" type="text" name="code" class="form-input" value="<?= e($editTier['code'] ?? '') ?>" placeholder="e.g. tier_d" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                    <div class="form-group">
                        <label class="form-label" for="pricing_tiers-discount_percent">Discount % *</label>
                    <input id="pricing_tiers-discount_percent" type="number" step="0.01" min="0" max="100" name="discount_percent" class="form-input" value="<?= e((string)($editTier['discount_percent'] ?? '')) ?>" placeholder="30" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="pricing_tiers-min_spend">Min Spend (RM)</label>
                    <input id="pricing_tiers-min_spend" type="number" step="0.01" min="0" name="min_spend" class="form-input" value="<?= e((string)($editTier['min_spend'] ?? '')) ?>" placeholder="1500">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="pricing_tiers-description">Description</label>
                    <input id="pricing_tiers-description" type="text" name="description" class="form-input" value="<?= e($editTier['description'] ?? '') ?>" placeholder="e.g. Hotel chains">
                </div>

                <button type="submit" class="btn btn-primary btn-block">Save Tier</button>
                <?php if ($editTier): ?><a href="<?= url('/admin/wholesale/tiers') ?>" class="btn btn-secondary btn-block">Cancel Editing</a><?php endif; ?>
            </form>
        </div>
    </div>
</div>
