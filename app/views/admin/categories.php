<div>
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Categories</h1>
            <p style="color: var(--admin-text-muted); font-size: 14px;">Organize produce categories and bilingual labels.</p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1.4fr 1fr; gap: 32px;">
        <!-- Left: Categories Table -->
        <div class="admin-card">
            <div class="admin-table-scroll" role="region" aria-label="Categories data table" tabindex="0"><table class="admin-table">
                <thead>
                    <tr>
                        <th>Category Name</th>
                        <th>Chinese (中文)</th>
                        <th>Products</th>
                        <th>Sort Order</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $c): ?>
                        <tr>
                            <td style="font-weight: 600;"><?= htmlspecialchars($c['name_en']) ?></td>
                            <td><?= htmlspecialchars($c['name_zh']) ?></td>
                            <td><?= $c['product_count'] ?> items</td>
                            <td><?= $c['sort_order'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table></div>
        </div>

        <!-- Right: Add / Edit Form -->
        <div class="admin-card" style="padding: 24px;">
            <h3 style="font-size: 18px; margin-bottom: 16px;">Add New Category</h3>
            <form action="<?= url('/admin/categories/save') ?>" method="POST">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label class="form-label" for="categories-name_en">Category Name (EN) *</label>
                    <input id="categories-name_en" type="text" name="name_en" class="form-input" placeholder="e.g. Fresh Herbs" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="categories-name_zh">Category Name (中文) *</label>
                    <input id="categories-name_zh" type="text" name="name_zh" class="form-input" placeholder="e.g. 新鲜香草" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="categories-sort_order">Sort Order</label>
                    <input id="categories-sort_order" type="number" name="sort_order" class="form-input" value="10">
                </div>

                <button type="submit" class="btn btn-primary btn-block">Save Category</button>
            </form>
        </div>
    </div>
</div>
