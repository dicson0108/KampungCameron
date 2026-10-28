<div class="container" style="padding-top: 32px; padding-bottom: 60px;">
    <!-- Account Breadcrumbs -->
    <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 24px;">
        <h1 style="font-size: 34px;">Saved Addresses</h1>
        <a href="<?= url('/account/dashboard') ?>" class="btn btn-secondary" style="font-size: 13px;">← Back to dashboard</a>
    </div>

    <?php $activeAccountTab = 'addresses'; require __DIR__ . '/nav.php'; ?>

    <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 36px;">
        <!-- Left: Address List -->
        <div>
            <h2 style="font-size: 20px; margin-bottom: 16px;">Your Addresses</h2>
            <?php if (empty($addresses)): ?>
                <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 32px; text-align: center; color: var(--text-muted);">
                    No saved addresses yet.
                </div>
            <?php else: ?>
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <?php foreach ($addresses as $ad): ?>
                        <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 20px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                <strong style="font-size: 16px;"><?= htmlspecialchars($ad['label']) ?></strong>
                                <?php if ($ad['is_default']): ?>
                                    <span class="badge badge-in-stock">Default</span>
                                <?php endif; ?>
                            </div>
                            <div style="font-weight: 600; font-size: 14px;"><?= htmlspecialchars($ad['recipient_name']) ?> (<?= htmlspecialchars($ad['phone']) ?>)</div>
                            <div style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">
                                <?= htmlspecialchars($ad['address_line1']) ?><?= $ad['address_line2'] ? ', ' . htmlspecialchars($ad['address_line2']) : '' ?><br>
                                <?= htmlspecialchars($ad['postcode']) ?> <?= htmlspecialchars($ad['city']) ?>, <?= htmlspecialchars($ad['state']) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Right: Add New Address Form -->
        <div>
            <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 28px;">
                <h2 style="font-size: 20px; margin-bottom: 18px;">Add New Address</h2>
                <form action="<?= url('/account/addresses') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="form-group">
                        <label class="form-label">Address Label</label>
                        <input type="text" name="label" class="form-input" placeholder="e.g. Home, Office, Kitchen" value="Home" required>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                        <div class="form-group">
                            <label class="form-label">Recipient Name *</label>
                            <input type="text" name="recipient_name" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Phone *</label>
                            <input type="text" name="phone" class="form-input" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Address Line 1 *</label>
                        <input type="text" name="address_line1" class="form-input" placeholder="Street address" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Address Line 2</label>
                        <input type="text" name="address_line2" class="form-input" placeholder="Unit, floor (optional)">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                        <div class="form-group">
                            <label class="form-label">City *</label>
                            <input type="text" name="city" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Postcode *</label>
                            <input type="text" name="postcode" class="form-input" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">State *</label>
                        <select name="state" class="form-select" required>
                            <option value="Selangor">Selangor</option>
                            <option value="Kuala Lumpur">Kuala Lumpur</option>
                            <option value="Johor">Johor</option>
                            <option value="Penang">Penang</option>
                            <option value="Perak">Perak</option>
                            <option value="Pahang">Pahang</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; cursor: pointer;">
                            <input type="checkbox" name="is_default" value="1" checked>
                            <span>Set as default delivery address</span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Save Address</button>
                </form>
            </div>
        </div>
    </div>
</div>
