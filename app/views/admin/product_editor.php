<div style="max-width: 800px;">
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title"><?= $product ? 'Edit Produce' : 'Create New Produce' ?></h1>
            <a href="<?= url('/admin/products') ?>" style="font-size: 13px; color: var(--admin-text-muted);">← Back to product list</a>
        </div>
    </div>

    <div class="admin-card" style="padding: 32px;">
        <form action="<?= url('/admin/products/save') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= htmlspecialchars($product['id'] ?? 0) ?>">
            <input type="hidden" name="existing_image" value="<?= htmlspecialchars($product['image'] ?? 'harvest.png') ?>">

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">English Name *</label>
                    <input type="text" name="name_en" class="form-input" value="<?= htmlspecialchars($product['name_en'] ?? '') ?>" placeholder="e.g. Cameron Broccoli" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Chinese Name (中文名) *</label>
                    <input type="text" name="name_zh" class="form-input" value="<?= htmlspecialchars($product['name_zh'] ?? '') ?>" placeholder="e.g. 金马仑西兰花" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">Category *</label>
                    <select name="category_id" class="form-select">
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= ($product['category_id'] ?? 0) == $cat['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name_en']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">SKU</label>
                    <input type="text" name="sku" class="form-input" value="<?= htmlspecialchars($product['sku'] ?? 'KC-' . rand(100, 999)) ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Unit Weight</label>
                    <input type="text" name="unit_weight" class="form-input" value="<?= htmlspecialchars($product['unit_weight'] ?? '500g') ?>" placeholder="e.g. 500g, 1 bunch" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">Retail Price (RM) *</label>
                    <input type="number" step="0.10" name="price" class="form-input" value="<?= htmlspecialchars($product['price'] ?? '6.50') ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Sale Price (RM)</label>
                    <input type="number" step="0.10" name="sale_price" class="form-input" value="<?= htmlspecialchars($product['sale_price'] ?? '') ?>" placeholder="Optional">
                </div>
                <div class="form-group">
                    <label class="form-label">Stock Quantity *</label>
                    <input type="number" name="stock_quantity" class="form-input" value="<?= htmlspecialchars($product['stock_quantity'] ?? '30') ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Low Stock Threshold</label>
                    <input type="number" name="low_stock_threshold" class="form-input" value="<?= htmlspecialchars($product['low_stock_threshold'] ?? '5') ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">English Description</label>
                <textarea name="description_en" rows="3" class="form-textarea"><?= htmlspecialchars($product['description_en'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Chinese Description (中文描述)</label>
                <textarea name="description_zh" rows="3" class="form-textarea"><?= htmlspecialchars($product['description_zh'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Produce Image</label>
                <input type="file" name="image" class="form-input" accept="image/*">
                <?php if (!empty($product['image'])): ?>
                    <div style="margin-top: 8px; display: flex; align-items: center; gap: 10px;">
                        <img src="<?= asset('images/' . $product['image']) ?>" alt="Current image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                        <span style="font-size: 13px; color: var(--admin-text-muted);">Current image file: <?= htmlspecialchars($product['image']) ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <div style="display: flex; gap: 24px; margin-bottom: 24px;">
                <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; cursor: pointer;">
                    <input type="checkbox" name="is_best_seller" value="1" <?= !empty($product['is_best_seller']) ? 'checked' : '' ?>>
                    <span>Best Seller Badge</span>
                </label>
                <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" <?= ($product['is_active'] ?? 1) ? 'checked' : '' ?>>
                    <span>Active on storefront</span>
                </label>
            </div>

            <div style="display: flex; gap: 14px;">
                <button type="submit" class="btn btn-primary btn-lg">Save Product</button>
                <a href="<?= url('/admin/products') ?>" class="btn btn-secondary btn-lg">Cancel</a>
            </div>
        </form>
    </div>
</div>
