<div class="container" style="padding-top: 24px; padding-bottom: 60px;">
    <!-- Shop Breadcrumbs and Filter Header -->
    <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 style="font-size: 34px; margin-bottom: 6px;"><?= __('nav_shop', 'Shop Fresh Produce') ?></h1>
            <p style="color: var(--text-muted); font-size: 14px;">
                <?= count($products) ?> fresh item(s) harvested from the highlands.
            </p>
        </div>

        <!-- Filter & Sort Bar -->
        <form action="<?= url('/shop') ?>" method="GET" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
            <?php if (!empty($selectedCat)): ?>
                <input type="hidden" name="category" value="<?= htmlspecialchars($selectedCat) ?>">
            <?php endif; ?>

            <!-- Availability Checkbox -->
            <label style="display: flex; align-items: center; gap: 6px; font-size: 14px; cursor: pointer; user-select: none;">
                <input type="checkbox" name="in_stock" value="1" <?= $inStockOnly ? 'checked' : '' ?> onchange="this.form.submit()">
                <span><?= __('in_stock_only', 'In stock only') ?></span>
            </label>

            <!-- Sort Dropdown -->
            <select name="sort" class="form-select" style="width: auto; padding: 6px 12px;" onchange="this.form.submit()">
                <option value="popular" <?= $currentSort === 'popular' ? 'selected' : '' ?>><?= __('sort_popular', 'Popularity') ?></option>
                <option value="newest" <?= $currentSort === 'newest' ? 'selected' : '' ?>><?= __('sort_newest', 'Newest') ?></option>
                <option value="price_low" <?= $currentSort === 'price_low' ? 'selected' : '' ?>><?= __('sort_price_low', 'Price: Low to High') ?></option>
                <option value="price_high" <?= $currentSort === 'price_high' ? 'selected' : '' ?>><?= __('sort_price_high', 'Price: High to Low') ?></option>
            </select>
        </form>
    </div>

    <!-- Category Pills Navigation -->
    <div style="display: flex; gap: 8px; overflow-x: auto; padding-bottom: 16px; margin-bottom: 32px;">
        <a href="<?= url('/shop') ?>" class="btn <?= empty($selectedCat) ? 'btn-primary' : 'btn-secondary' ?>" style="border-radius: var(--radius-pill); font-size: 13px; padding: 6px 16px;">
            <?= __('filter_all', 'All categories') ?>
        </a>
        <?php foreach ($categories as $cat): ?>
            <a href="<?= url('/shop?category=' . $cat['id']) ?>" class="btn <?= ($selectedCat == $cat['id']) ? 'btn-primary' : 'btn-secondary' ?>" style="border-radius: var(--radius-pill); font-size: 13px; padding: 6px 16px;">
                <?= trans($cat, 'name') ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Product Grid -->
    <?php if (empty($products)): ?>
        <div style="text-align: center; padding: 80px 20px; background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md);">
            <h3 style="font-size: 22px; margin-bottom: 8px;"><?= __('no_products_found', 'No products found') ?></h3>
            <p style="color: var(--text-muted); margin-bottom: 20px;">Try adjusting your filters or category selection.</p>
            <a href="<?= url('/shop') ?>" class="btn btn-primary"><?= __('clear_filters', 'Clear all filters') ?></a>
        </div>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($products as $prod): ?>
                <?php require __DIR__ . '/product_card.php'; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
