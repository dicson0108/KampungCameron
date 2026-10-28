<div class="container" style="padding-top: 32px; padding-bottom: 60px;">
    <!-- Breadcrumb -->
    <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 24px;">
        <a href="<?= url('/') ?>">Home</a> / <a href="<?= url('/shop') ?>">Shop</a> / <a href="<?= url('/shop?category=' . $product['category_id']) ?>"><?= trans(['name_en' => $product['category_name_en'], 'name_zh' => $product['category_name_zh']], 'name') ?></a> / <strong><?= trans($product, 'name') ?></strong>
    </div>

    <!-- Product Top Grid -->
    <div style="display: grid; grid-template-columns: 1.1fr 1fr; gap: 48px; margin-bottom: 60px;">
        <!-- Image Area -->
        <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); overflow: hidden; padding: 24px; display: flex; align-items: center; justify-content: center;">
            <img src="<?= asset('images/' . ($product['image'] ?: 'harvest.png')) ?>" alt="<?= trans($product, 'name') ?>" style="max-height: 440px; object-fit: contain;">
        </div>

        <!-- Info Area -->
        <div style="display: flex; flex-direction: column;">
            <!-- Stock Badge -->
            <div style="margin-bottom: 12px;">
                <?php if ($product['stock_quantity'] <= 0): ?>
                    <span class="badge badge-sold-out" style="padding: 4px 10px; font-size: 13px;"><?= __('badge_sold_out', 'Sold out') ?></span>
                <?php elseif ($product['stock_quantity'] <= $product['low_stock_threshold']): ?>
                    <span class="badge badge-low-stock" style="padding: 4px 10px; font-size: 13px;"><?= sprintf(__('badge_low_stock', 'Low · %d left in stock'), $product['stock_quantity']) ?></span>
                <?php else: ?>
                    <span class="badge badge-in-stock" style="padding: 4px 10px; font-size: 13px;"><?= __('badge_in_stock', 'In stock') ?></span>
                <?php endif; ?>
            </div>

            <h1 style="font-size: 38px; line-height: 1.15; margin-bottom: 8px;"><?= trans($product, 'name') ?></h1>
            
            <div style="font-size: 14px; color: var(--text-muted); margin-bottom: 20px;">
                <span>Unit: <?= htmlspecialchars($product['unit_weight']) ?></span> · 
                <span>SKU: <?= htmlspecialchars($product['sku']) ?></span> · 
                <span>★ <?= number_format($avgRating ?: 4.8, 1) ?> (<?= count($reviews) ?: 24 ?> reviews)</span>
            </div>

            <!-- Price Box -->
            <div style="margin-bottom: 24px; display: flex; align-items: baseline; gap: 12px;">
                <span style="font-size: 32px; font-weight: 700; color: var(--text-main);">
                    <?= format_currency($product['display_price']) ?>
                </span>
                <?php if (!empty($product['sale_price'])): ?>
                    <span style="font-size: 18px; text-decoration: line-through; color: var(--text-muted);">
                        <?= format_currency($product['price']) ?>
                    </span>
                    <span class="badge badge-sale">SALE</span>
                <?php endif; ?>
            </div>

            <?php if (!empty($product['wholesale_price'])): ?>
                <div style="background: var(--service-blue-light); border: 1px solid #cce3ed; border-radius: var(--radius-sm); padding: 12px 16px; margin-bottom: 24px; font-size: 14px; color: var(--service-blue-dark);">
                    <strong>Wholesale Partner Price:</strong> <?= format_currency($product['wholesale_price']) ?> (Applied Tier Discount)
                </div>
            <?php endif; ?>

            <!-- Add to Cart Form -->
            <?php if ($product['stock_quantity'] > 0): ?>
                <form action="<?= url('/cart/add') ?>" method="POST" data-ajax-cart style="margin-bottom: 32px;">
                    <?= csrf_field() ?>
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

                    <div style="display: flex; gap: 16px; align-items: center; margin-bottom: 16px;">
                        <label for="qty" style="font-size: 14px; font-weight: 600;"><?= __('quantity', 'Quantity') ?>:</label>
                        <select name="quantity" id="qty" class="form-select" style="width: 80px;">
                            <?php for ($i = 1; $i <= min(10, $product['stock_quantity']); $i++): ?>
                                <option value="<?= $i ?>"><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div style="display: flex; gap: 12px;">
                        <button type="submit" class="btn btn-primary btn-lg" style="flex: 1;">
                            <?= __('btn_add_to_cart', '+ Add to cart') ?>
                        </button>
                        <button type="button" class="btn btn-secondary wishlist-toggle-btn <?= is_in_wishlist($product['id']) ? 'active' : '' ?>" data-product-id="<?= $product['id'] ?>" style="padding: 0 18px;" title="Save to wishlist">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="<?= is_in_wishlist($product['id']) ? '#e11d48' : 'none' ?>" stroke="<?= is_in_wishlist($product['id']) ? '#e11d48' : 'currentColor' ?>" stroke-width="2">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                            </svg>
                        </button>
                    </div>
                </form>
            <?php else: ?>
                <div style="margin-bottom: 32px;">
                    <button type="button" class="btn btn-secondary btn-lg btn-block" disabled><?= __('badge_sold_out', 'Sold out — Restocking soon') ?></button>
                </div>
            <?php endif; ?>

            <!-- Description -->
            <div style="border-top: 1px solid var(--border-color); padding-top: 24px;">
                <h3 style="font-size: 18px; margin-bottom: 10px;">About this produce</h3>
                <p style="color: var(--text-muted); line-height: 1.7; font-size: 15px;">
                    <?= nl2br(htmlspecialchars(trans($product, 'description'))) ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Related Recipes ("Cook with this harvest") per FR-7 and FR-30 -->
    <?php if (!empty($relatedRecipes)): ?>
        <section style="margin-bottom: 60px; padding: 32px; background: var(--warm-paper-card); border-radius: var(--radius-md); border: 1px solid var(--border-color);">
            <h2 class="section-title" style="margin-bottom: 20px;"><?= __('related_recipes', 'Cook with this harvest') ?></h2>
            <div class="recipes-grid" style="margin-bottom: 0;">
                <?php foreach ($relatedRecipes as $rec): ?>
                    <div class="recipe-card">
                        <div class="recipe-card-media">
                            <a href="<?= url('/recipes/' . $rec['slug']) ?>">
                                <img src="<?= asset('images/' . ($rec['image'] ?: 'salad.png')) ?>" alt="<?= trans($rec, 'title') ?>">
                            </a>
                        </div>
                        <div class="recipe-card-body">
                            <h3 class="recipe-card-title">
                                <a href="<?= url('/recipes/' . $rec['slug']) ?>"><?= trans($rec, 'title') ?></a>
                            </h3>
                            <a href="<?= url('/recipes/' . $rec['slug']) ?>" class="btn btn-secondary"><?= __('read_recipe', 'Read recipe') ?> →</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- Customer Reviews -->
    <section style="border-top: 1px solid var(--border-color); padding-top: 40px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px;">
            <h2 class="section-title" style="margin-bottom: 0;"><?= __('customer_reviews', 'Customer Reviews') ?></h2>
            <span style="font-size: 18px; font-weight: 700;">★ <?= number_format($avgRating ?: 5.0, 1) ?> / 5.0</span>
        </div>

        <!-- Write Review Form (for verified buyers or logged in) -->
        <?php if (is_logged_in()): ?>
            <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 24px; margin-bottom: 36px;">
                <h3 style="font-size: 18px; margin-bottom: 16px;"><?= __('write_review', 'Leave a review') ?></h3>
                <form action="<?= url('/reviews/submit') ?>" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

                    <div class="form-group" style="max-width: 160px;">
                        <label class="form-label"><?= __('rating', 'Rating') ?> (1-5 Stars)</label>
                        <select name="rating" class="form-select">
                            <option value="5">★★★★★ (5 - Excellent)</option>
                            <option value="4">★★★★☆ (4 - Very good)</option>
                            <option value="3">★★★☆☆ (3 - Average)</option>
                            <option value="2">★★☆☆☆ (2 - Below average)</option>
                            <option value="1">★☆☆☆☆ (1 - Poor)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Your experience with this produce</label>
                        <textarea name="review_text" rows="3" class="form-textarea" placeholder="Describe the freshness, taste, or texture..." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary"><?= __('submit_review', 'Submit Review') ?></button>
                </form>
            </div>
        <?php endif; ?>

        <!-- Approved Reviews List -->
        <?php if (empty($reviews)): ?>
            <p style="color: var(--text-muted); font-size: 15px;">No reviews yet for this harvest. Be the first to share your cooking experience!</p>
        <?php else: ?>
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <?php foreach ($reviews as $rev): ?>
                    <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 18px 20px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <strong><?= htmlspecialchars($rev['reviewer_name']) ?> <span style="font-size: 12px; color: var(--primary-green); font-weight: normal;">✓ Verified buyer</span></strong>
                            <span style="color: #b78103; font-weight: 700;"><?= str_repeat('★', $rev['rating']) ?></span>
                        </div>
                        <p style="color: var(--text-main); font-size: 14px; line-height: 1.6;">
                            <?= htmlspecialchars($rev['review_text']) ?>
                        </p>
                        <div style="font-size: 12px; color: var(--text-muted); margin-top: 8px;">
                            <?= date('d M Y', strtotime($rev['created_at'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</div>
