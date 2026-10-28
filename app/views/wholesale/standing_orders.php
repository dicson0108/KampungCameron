<?php $activeWholesaleTab = 'standing-orders'; require __DIR__ . '/subnav.php'; ?>

<div class="container" style="padding-bottom: 60px;">
    <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 28px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 style="font-size: 34px; margin-bottom: 6px;">Standing Orders</h1>
            <p style="color: var(--text-muted); font-size: 14px;">Recurring weekly fresh produce schedules for your kitchen.</p>
        </div>
        <a href="<?= url('/wholesale/standing-orders/edit') ?>" class="btn btn-primary">+ Create New Standing Order</a>
    </div>

    <?php if (empty($standingOrders)): ?>
        <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 60px 20px; text-align: center;">
            <p style="font-size: 16px; color: var(--text-muted); margin-bottom: 16px;">You don't have any active standing orders yet.</p>
            <a href="<?= url('/wholesale/standing-orders/edit') ?>" class="btn btn-primary">Set up your weekly schedule</a>
        </div>
    <?php else: ?>
        <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                <thead>
                    <tr style="background: var(--warm-paper); border-bottom: 1px solid var(--border-color);">
                        <th style="padding: 14px 20px;">Schedule Name</th>
                        <th style="padding: 14px 20px;">Delivery Day</th>
                        <th style="padding: 14px 20px;">Start Date</th>
                        <th style="padding: 14px 20px;">Items</th>
                        <th style="padding: 14px 20px;">Status</th>
                        <th style="padding: 14px 20px; text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($standingOrders as $so): ?>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 16px 20px; font-weight: 600; font-size: 15px;">
                                <?= htmlspecialchars($so['name']) ?>
                            </td>
                            <td style="padding: 16px 20px;">Every <?= htmlspecialchars($so['delivery_day']) ?></td>
                            <td style="padding: 16px 20px; color: var(--text-muted);"><?= date('d M Y', strtotime($so['start_date'])) ?></td>
                            <td style="padding: 16px 20px;"><?= $so['item_count'] ?> products</td>
                            <td style="padding: 16px 20px;">
                                <?php if ($so['status'] === 'active'): ?>
                                    <span class="badge badge-in-stock">Active</span>
                                <?php elseif ($so['status'] === 'paused'): ?>
                                    <span class="badge badge-low-stock">Paused</span>
                                <?php else: ?>
                                    <span class="badge badge-sold-out">Cancelled</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 16px 20px; text-align: right;">
                                <a href="<?= url('/wholesale/standing-orders/edit?id=' . $so['id']) ?>" class="btn btn-secondary" style="padding: 6px 12px; font-size: 13px;">Edit Schedule</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
