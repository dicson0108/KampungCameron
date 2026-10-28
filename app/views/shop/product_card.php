<div class="product-card">
    <div class="product-card-media">
        <a href="<?= url('/product/' . $prod['slug']) ?>">
            <img src="<?= asset('images/' . ($prod['image'] ?: 'harvest.png')) ?>" alt="<?= trans($prod, 'name') ?>">
        </a>
        <!-- Wishlist Toggle Button -->
        <button type="button" class="wishlist-btn wishlist-toggle-btn <?= is_in_wishlist($prod['id']) ? 'active' : '' ?>" data-product-id="<?= $prod['id'] ?>" aria-label="Add to wishlist">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
            </svg>
        </button>
        <!-- Stock Status Badge -->
        <div class="product-badge-pos">
            <?php if ($prod['stock_quantity'] <= 0): ?>
                <span class="badge badge-sold-out"><?= __('badge_sold_out', 'Sold out') ?></span>
            <?php elseif ($prod['stock_quantity'] <= $prod['low_stock_threshold']): ?>
                <span class="badge badge-low-stock"><?= sprintf(__('badge_low_stock', 'Low · %d left'), $prod['stock_quantity']) ?></span>
            <?php else: ?>
                <span class="badge badge-in-stock"><?= __('badge_in_stock', 'In stock') ?></span>
            <?php endif; ?>
        </div>
    </div>

    <div class="product-card-body">
        <h3 class="product-title">
            <a href="<?= url('/product/' . $prod['slug']) ?>"><?= trans($prod, 'name') ?></a>
        </h3>
        <div class="product-specs">
            <?= htmlspecialchars($prod['unit_weight']) ?> · ★ <?= number_format($prod['avg_rating'] ?: 4.8, 1) ?> (<?= $prod['review_count'] ?: 24 ?>)
        </div>

        <div class="product-price-row">
            <span class="product-price"><?= format_currency(product_price($prod)) ?></span>
            <?php if (!empty($prod['sale_price'])): ?>
                <span class="product-was-price"><?= format_currency($prod['price']) ?></span>
            <?php endif; ?>
        </div>

        <?php if ($prod['stock_quantity'] > 0): ?>
            <form action="<?= url('/cart/add') ?>" method="POST" data-ajax-cart>
                <?= csrf_field() ?>
                <input type="hidden" name="product_id" value="<?= $prod['id'] ?>">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="btn btn-primary btn-block"><?= __('btn_add_to_cart', '+ Add to cart') ?></button>
            </form>
        <?php else: ?>
            <button type="button" class="btn btn-secondary btn-block" disabled><?= __('btn_notify_me', 'Notify me') ?></button>
        <?php endif; ?>
    </div>
</div>
