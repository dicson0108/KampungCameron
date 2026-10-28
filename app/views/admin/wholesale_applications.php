<div>
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Wholesale Accounts & Applications</h1>
            <p style="color: var(--admin-text-muted); font-size: 14px;">Review B2B restaurant supply applications and assign pricing tiers.</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="<?= url('/admin/wholesale/tiers') ?>" class="btn btn-secondary">Configure Pricing Tiers</a>
            <a href="<?= url('/admin/wholesale/standing-orders') ?>" class="btn btn-secondary">All Standing Orders</a>
        </div>
    </div>

    <div class="admin-card">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Business Name</th>
                    <th>SSM Registration</th>
                    <th>Contact Person</th>
                    <th>Email & Phone</th>
                    <th>Assigned Tier</th>
                    <th>Status</th>
                    <th style="text-align: right; width: 280px;">Review & Assign Tier</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($applications as $app): ?>
                    <tr>
                        <td style="font-weight: 600;">
                            <div><?= htmlspecialchars($app['business_name']) ?></div>
                            <div style="font-size: 12px; color: var(--admin-text-muted);"><?= htmlspecialchars($app['business_type']) ?></div>
                        </td>
                        <td><code><?= htmlspecialchars($app['registration_number']) ?></code></td>
                        <td><?= htmlspecialchars($app['contact_person']) ?></td>
                        <td>
                            <div><?= htmlspecialchars($app['contact_email']) ?></div>
                            <div style="font-size: 12px; color: var(--admin-text-muted);"><?= htmlspecialchars($app['contact_phone']) ?></div>
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($app['tier_name'] ?? 'Not Assigned') ?></strong>
                        </td>
                        <td>
                            <?php if ($app['status'] === 'approved'): ?>
                                <span class="badge badge-in-stock">Approved</span>
                            <?php elseif ($app['status'] === 'pending'): ?>
                                <span class="badge badge-low-stock">Pending Review</span>
                            <?php else: ?>
                                <span class="badge badge-sold-out">Rejected</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: right;">
                            <form action="<?= url('/admin/wholesale/application/action') ?>" method="POST" style="display: flex; gap: 8px; justify-content: flex-end; align-items: center;">
                                <?= csrf_field() ?>
                                <input type="hidden" name="app_id" value="<?= $app['id'] ?>">

                                <select name="tier_id" class="form-select" style="width: auto; padding: 4px 8px; font-size: 12px;">
                                    <?php foreach ($tiers as $t): ?>
                                        <option value="<?= $t['id'] ?>" <?= ($app['tier_id'] ?? 1) == $t['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($t['name']) ?> (<?= (int)$t['discount_percent'] ?>%)
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <?php if ($app['status'] === 'pending'): ?>
                                    <button type="submit" name="action" value="approve" class="btn btn-primary" style="padding: 4px 10px; font-size: 12px;">Approve</button>
                                    <button type="submit" name="action" value="reject" class="btn btn-secondary" style="padding: 4px 8px; font-size: 12px; color: #ef4444;">Reject</button>
                                <?php else: ?>
                                    <button type="submit" name="action" value="approve" class="btn btn-secondary" style="padding: 4px 10px; font-size: 12px;">Update Tier</button>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
