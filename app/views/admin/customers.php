<div>
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Customers</h1>
            <p style="color: var(--admin-text-muted); font-size: 14px;">Customer profiles, order frequency, and lifetime spend.</p>
        </div>
    </div>

    <div class="admin-card">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Phone</th>
                    <th>Orders</th>
                    <th>Total Spend</th>
                    <th>Registered</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $c): ?>
                    <tr>
                        <td style="font-weight: 600;"><?= htmlspecialchars($c['name']) ?></td>
                        <td><?= htmlspecialchars($c['email']) ?></td>
                        <td>
                            <span class="badge <?= $c['role'] === 'wholesale' ? 'badge-low-stock' : 'badge-in-stock' ?>">
                                <?= ucfirst(htmlspecialchars($c['role'])) ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($c['phone'] ?? '—') ?></td>
                        <td><?= $c['order_count'] ?> orders</td>
                        <td style="font-weight: 600; color: var(--primary-green);"><?= format_currency($c['total_spend']) ?></td>
                        <td style="color: var(--admin-text-muted);"><?= date('d M Y', strtotime($c['created_at'])) ?></td>
                        <td>
                            <span class="badge <?= $c['is_active'] ? 'badge-in-stock' : 'badge-sold-out' ?>">
                                <?= $c['is_active'] ? 'Active' : 'Deactivated' ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
