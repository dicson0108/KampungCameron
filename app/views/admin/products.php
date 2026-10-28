<div>
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Products</h1>
            <p style="color: var(--admin-text-muted); font-size: 14px;">Manage fresh produce catalogue, prices, and inventory thresholds.</p>
        </div>
        <a href="<?= url('/admin/products/create') ?>" class="btn btn-primary">+ Add New Produce</a>
    </div>

    <div class="admin-card">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>SKU</th>
                    <th>Unit</th>
                    <th>Retail Price</th>
                    <th>Sale Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                    <tr>
                        <td style="font-weight: 600;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <img src="<?= asset('images/' . ($p['image'] ?: 'harvest.png')) ?>" alt="<?= htmlspecialchars($p['name_en']) ?>" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                <div>
                                    <div><?= htmlspecialchars($p['name_en']) ?></div>
                                    <div style="font-size: 12px; color: var(--admin-text-muted);"><?= htmlspecialchars($p['name_zh']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($p['category_name']) ?></td>
                        <td><code><?= htmlspecialchars($p['sku']) ?></code></td>
                        <td><?= htmlspecialchars($p['unit_weight']) ?></td>
                        <td style="font-weight: 600;"><?= format_currency($p['price']) ?></td>
                        <td><?= $p['sale_price'] ? format_currency($p['sale_price']) : '—' ?></td>
                        <td>
                            <span class="badge <?= $p['stock_quantity'] <= $p['low_stock_threshold'] ? 'badge-low-stock' : 'badge-in-stock' ?>">
                                <?= $p['stock_quantity'] ?> packs
                            </span>
                        </td>
                        <td>
                            <span class="badge <?= $p['is_active'] ? 'badge-in-stock' : 'badge-sold-out' ?>">
                                <?= $p['is_active'] ? 'Active' : 'Archived' ?>
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <a href="<?= url('/admin/products/edit?id=' . $p['id']) ?>" class="btn btn-secondary" style="padding: 4px 10px; font-size: 12px;">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
