<div>
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Inventory & Stock Audit</h1>
            <p style="color: var(--admin-text-muted); font-size: 14px;">Real-time stock tracking and inventory balance adjustments.</p>
        </div>
    </div>

    <!-- Quick Stock Adjustment Box -->
    <div class="admin-card" style="padding: 24px; margin-bottom: 28px;">
        <h3 style="font-size: 18px; margin-bottom: 14px;">Quick Stock Adjustment / Harvest Restock</h3>
        <form action="<?= url('/admin/inventory/adjust') ?>" method="POST" style="display: flex; gap: 14px; align-items: flex-end; flex-wrap: wrap;">
            <?= csrf_field() ?>

            <div style="flex: 1; min-width: 200px;">
                <label class="form-label">Select Produce</label>
                <select name="product_id" class="form-select">
                    <?php foreach ($products as $p): ?>
                        <option value="<?= $p['id'] ?>">
                            <?= htmlspecialchars($p['name_en']) ?> (Current: <?= $p['stock_quantity'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="width: 140px;">
                <label class="form-label">Qty Change (+/-)</label>
                <input type="number" name="change_quantity" class="form-input" placeholder="+20 or -5" required>
            </div>

            <div style="flex: 1; min-width: 180px;">
                <label class="form-label">Reason</label>
                <select name="reason" class="form-select">
                    <option value="morning_harvest_restock">Morning highland harvest</option>
                    <option value="manual_stock_count">Manual audit count</option>
                    <option value="damaged_spoilage">Damaged / culled</option>
                    <option value="wholesale_allocation">Wholesale kitchen allocation</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Apply Stock Adjustment</button>
        </form>
    </div>

    <!-- Stock Levels Table -->
    <div class="admin-card" style="margin-bottom: 32px;">
        <div class="admin-card-header">
            <h3>Current Stock Levels</h3>
        </div>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>In Stock</th>
                    <th>Low Threshold</th>
                    <th>Stock Health Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                    <tr>
                        <td style="font-weight: 600;"><?= htmlspecialchars($p['name_en']) ?> (<?= htmlspecialchars($p['unit_weight']) ?>)</td>
                        <td><?= htmlspecialchars($p['category_name']) ?></td>
                        <td style="font-weight: 700;"><?= $p['stock_quantity'] ?> packs</td>
                        <td><?= $p['low_stock_threshold'] ?> packs</td>
                        <td>
                            <?php if ($p['stock_quantity'] <= 0): ?>
                                <span class="badge badge-sold-out">Sold Out</span>
                            <?php elseif ($p['stock_quantity'] <= $p['low_stock_threshold']): ?>
                                <span class="badge badge-low-stock">Low Stock Alert</span>
                            <?php else: ?>
                                <span class="badge badge-in-stock">Healthy Stock</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Stock Audit Log Table per FR-36 -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h3>Stock Change Log (Audit Trail)</h3>
        </div>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>Produce</th>
                    <th>Adjustment</th>
                    <th>Ending Balance</th>
                    <th>Reason</th>
                    <th>Reference / By</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stockLogs as $sl): ?>
                    <tr>
                        <td style="color: var(--admin-text-muted);"><?= date('d M Y, h:i A', strtotime($sl['created_at'])) ?></td>
                        <td style="font-weight: 600;"><?= htmlspecialchars($sl['product_name']) ?></td>
                        <td style="font-weight: 700; color: <?= $sl['change_qty'] > 0 ? 'var(--primary-green)' : '#ef4444' ?>;">
                            <?= $sl['change_qty'] > 0 ? '+' . $sl['change_qty'] : $sl['change_qty'] ?>
                        </td>
                        <td><?= $sl['balance_qty'] ?> packs</td>
                        <td><?= htmlspecialchars(str_replace('_', ' ', $sl['reason'])) ?></td>
                        <td style="font-size: 13px; color: var(--admin-text-muted);"><?= htmlspecialchars($sl['reference_id'] ?? '') ?> · <?= htmlspecialchars($sl['created_by']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
