<div class="container" style="padding-top: 32px; padding-bottom: 60px;">
    <!-- Account Breadcrumbs -->
    <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 24px;">
        <h1 style="font-size: 34px;">My Wishlist</h1>
        <a href="<?= url('/account/dashboard') ?>" class="btn btn-secondary" style="font-size: 13px;">← Back to dashboard</a>
    </div>

    <?php $activeAccountTab = 'wishlist'; require __DIR__ . '/nav.php'; ?>

    <?php if (empty($items)): ?>
        <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 80px 20px; text-align: center;">
            <p style="font-size: 16px; color: var(--text-muted); margin-bottom: 20px;">Your wishlist is currently empty.</p>
            <a href="<?= url('/shop') ?>" class="btn btn-primary">Discover fresh produce</a>
        </div>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($items as $prod): ?>
                <div class="product-card">
                    <div class="product-card-media">
                        <a href="<?= url('/product/' . $prod['slug']) ?>">
                            <img src="<?= asset('images/' . ($prod['image'] ?: 'harvest.png')) ?>" alt="<?= trans($prod, 'name') ?>">
                        </a>
                        <div class="product-badge-pos">
                            <?php if ($prod['stock_quantity'] <= 0): ?>
                                <span class="badge badge-sold-out">Sold out</span>
                            <?php else: ?>
                                <span class="badge badge-in-stock">In stock</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="product-card-body">
                        <h3 class="product-title">
                            <a href="<?= url('/product/' . $prod['slug']) ?>"><?= trans($prod, 'name') ?></a>
                        </h3>
                        <div class="product-specs"><?= htmlspecialchars($prod['unit_weight']) ?> · <?= htmlspecialchars($prod['category_name']) ?></div>
                        <div class="product-price-row">
                            <span class="product-price"><?= format_currency($prod['sale_price'] ?? $prod['price']) ?></span>
                        </div>

                        <div style="display: flex; gap: 8px;">
                            <?php if ($prod['stock_quantity'] > 0): ?>
                                <form action="<?= url('/cart/add') ?>" method="POST" data-ajax-cart style="flex: 1;">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="product_id" value="<?= $prod['id'] ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-primary btn-block">Move to Cart</button>
                                </form>
                            <?php endif; ?>
                            <form action="<?= url('/wishlist/toggle') ?>" method="POST">
                                <?= csrf_field() ?>
                                <input type="hidden" name="product_id" value="<?= $prod['id'] ?>">
                                <button type="submit" class="btn btn-secondary" title="Remove from wishlist">✕</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
