<div>
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Wholesale Standing Orders</h1>
            <p style="color: var(--admin-text-muted); font-size: 14px;">All recurring weekly delivery subscriptions across verified restaurants and kitchens.</p>
        </div>
        <a href="<?= url('/admin/wholesale') ?>" class="btn btn-secondary">← Back to Wholesale Accounts</a>
    </div>

    <div class="admin-card">
        <div class="admin-table-scroll" role="region" aria-label="Standing Orders data table" tabindex="0"><table class="admin-table">
            <thead>
                <tr>
                    <th>Schedule Name</th>
                    <th>Wholesale Business</th>
                    <th>Contact</th>
                    <th>Delivery Day</th>
                    <th>Start Date</th>
                    <th>Destination</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($standingOrders)): ?>
                    <tr><td colspan="7" style="padding: 32px; text-align: center; color: var(--admin-text-muted);">No standing orders active.</td></tr>
                <?php else: ?>
                    <?php foreach ($standingOrders as $so): ?>
                        <tr>
                            <td style="font-weight: 600;"><?= htmlspecialchars($so['name']) ?></td>
                            <td><?= htmlspecialchars($so['business_name']) ?></td>
                            <td><?= htmlspecialchars($so['contact_person']) ?></td>
                            <td><strong>Every <?= htmlspecialchars($so['delivery_day']) ?></strong></td>
                            <td style="color: var(--admin-text-muted);"><?= date('d M Y', strtotime($so['start_date'])) ?></td>
                            <td><?= htmlspecialchars($so['delivery_address']) ?></td>
                            <td>
                                <span class="badge <?= $so['status'] === 'active' ? 'badge-in-stock' : 'badge-low-stock' ?>">
                                    <?= ucfirst(htmlspecialchars($so['status'])) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table></div>
    </div>
</div>
