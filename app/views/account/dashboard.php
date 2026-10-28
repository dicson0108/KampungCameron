<div class="container" style="padding-top: 32px; padding-bottom: 60px;">
    <!-- Account Breadcrumbs -->
    <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 24px;">
        <h1 style="font-size: 34px;">Hello, <?= htmlspecialchars(explode(' ', auth_user()['name'])[0]) ?></h1>
        <a href="<?= url('/account/logout') ?>" class="btn btn-secondary" style="font-size: 13px;">Sign out</a>
    </div>

    <?php $activeAccountTab = 'dashboard'; require __DIR__ . '/nav.php'; ?>

    <!-- Quick Stats Grid -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 36px;">
        <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 24px;">
            <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 6px;">Total Orders Placed</div>
            <div style="font-size: 32px; font-weight: 700; color: var(--primary-green); font-family: 'Lora', serif;"><?= $orderCount ?></div>
            <a href="<?= url('/account/orders') ?>" style="font-size: 13px; font-weight: 600; color: var(--text-muted); margin-top: 8px; display: inline-block;">View order history →</a>
        </div>

        <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 24px;">
            <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 6px;">Wishlist Items</div>
            <div style="font-size: 32px; font-weight: 700; color: var(--primary-green); font-family: 'Lora', serif;"><?= $wishlistCount ?></div>
            <a href="<?= url('/account/wishlist') ?>" style="font-size: 13px; font-weight: 600; color: var(--text-muted); margin-top: 8px; display: inline-block;">View saved produce →</a>
        </div>

        <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 24px;">
            <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 6px;">Default Delivery Address</div>
            <?php if ($defaultAddress): ?>
                <div style="font-weight: 600; font-size: 14px; margin-top: 4px;"><?= htmlspecialchars($defaultAddress['recipient_name']) ?></div>
                <div style="font-size: 13px; color: var(--text-muted);"><?= htmlspecialchars($defaultAddress['city']) ?>, <?= htmlspecialchars($defaultAddress['state']) ?></div>
            <?php else: ?>
                <div style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">No saved address.</div>
            <?php endif; ?>
            <a href="<?= url('/account/addresses') ?>" style="font-size: 13px; font-weight: 600; color: var(--text-muted); margin-top: 8px; display: inline-block;">Manage addresses →</a>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); overflow: hidden;">
        <div style="padding: 18px 24px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
            <h2 style="font-size: 20px;">Recent Orders</h2>
            <a href="<?= url('/account/orders') ?>" style="font-size: 13px; font-weight: 600; color: var(--primary-green);">View all orders →</a>
        </div>

        <?php if (empty($recentOrders)): ?>
            <div style="padding: 40px; text-align: center; color: var(--text-muted);">
                <p style="margin-bottom: 16px;">You haven't placed any orders yet.</p>
                <a href="<?= url('/shop') ?>" class="btn btn-primary">Start shopping fresh produce</a>
            </div>
        <?php else: ?>
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                <thead>
                    <tr style="background: var(--warm-paper); border-bottom: 1px solid var(--border-color);">
                        <th style="padding: 14px 20px;">Order #</th>
                        <th style="padding: 14px 20px;">Date</th>
                        <th style="padding: 14px 20px;">Total</th>
                        <th style="padding: 14px 20px;">Status</th>
                        <th style="padding: 14px 20px; text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentOrders as $ord): ?>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 14px 20px; font-weight: 600;"><?= htmlspecialchars($ord['order_number']) ?></td>
                            <td style="padding: 14px 20px; color: var(--text-muted);"><?= date('d M Y', strtotime($ord['created_at'])) ?></td>
                            <td style="padding: 14px 20px; font-weight: 600;"><?= format_currency($ord['total_amount']) ?></td>
                            <td style="padding: 14px 20px;">
                                <span class="badge badge-in-stock"><?= ucfirst(htmlspecialchars($ord['status'])) ?></span>
                            </td>
                            <td style="padding: 14px 20px; text-align: right;">
                                <a href="<?= url('/account/orders/' . $ord['id']) ?>" class="btn btn-secondary" style="padding: 4px 12px; font-size: 12px;">View Details</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
