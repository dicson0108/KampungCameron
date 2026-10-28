<div class="container" style="padding-top: 32px; padding-bottom: 60px;">
    <!-- Breadcrumb -->
    <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 24px;">
        <a href="<?= url('/') ?>">Home</a> / <a href="<?= url('/recipes') ?>">Recipes</a> / <strong><?= trans($recipe, 'title') ?></strong>
    </div>

    <!-- Recipe Header -->
    <div style="margin-bottom: 32px;">
        <span class="badge" style="background: var(--warm-paper-card); color: var(--primary-green); font-size: 12px; margin-bottom: 10px;">
            <?= $recipe['category'] === 'health_nourishment' ? 'Health Nourishment' : 'Veggie Recipe' ?>
        </span>
        <h1 style="font-size: 42px; line-height: 1.15; margin-bottom: 12px;"><?= trans($recipe, 'title') ?></h1>
        <div style="font-size: 14px; color: var(--text-muted); display: flex; gap: 20px;">
            <span>Prep: <?= $recipe['prep_time_minutes'] ?> mins</span>
            <span>Cook: <?= $recipe['cooking_time_minutes'] ?> mins</span>
            <span>Servings: <?= htmlspecialchars($recipe['servings']) ?></span>
        </div>
    </div>

    <!-- Main Hero Image -->
    <div style="border-radius: var(--radius-md); overflow: hidden; margin-bottom: 48px; max-height: 480px;">
        <img src="<?= asset('images/' . ($recipe['image'] ?: 'salad.png')) ?>" alt="<?= trans($recipe, 'title') ?>" style="width: 100%; height: 100%; object-fit: cover;">
    </div>

    <!-- 2 Column: Ingredients + Add All & Steps -->
    <div style="display: grid; grid-template-columns: 1fr 1.6fr; gap: 48px; margin-bottom: 60px;">
        <!-- Left: Ingredients & Add all to cart -->
        <div>
            <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 28px;">
                <h3 style="font-size: 22px; margin-bottom: 16px;">Ingredients</h3>

                <div style="font-size: 15px; line-height: 1.8; color: var(--text-main); margin-bottom: 24px; white-space: pre-line;">
                    <?= htmlspecialchars(trans($recipe, 'ingredients')) ?>
                </div>

                <!-- Add All To Cart Action per FR-30 -->
                <?php if (!empty($linkedProducts)): ?>
                    <form action="<?= url('/recipes-add-all') ?>" method="POST">
                        <?= csrf_field() ?>
                        <input type="hidden" name="recipe_id" value="<?= $recipe['id'] ?>">
                        <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-bottom: 12px;">
                            🛒 Add all ingredients to cart
                        </button>
                    </form>
                    <span style="font-size: 12px; color: var(--text-muted); text-align: center; display: block;">
                        Fresh highland harvest added directly to your bag.
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right: Step-by-Step Instructions -->
        <div>
            <h2 style="font-size: 28px; margin-bottom: 20px;">Instructions</h2>
            <div style="font-size: 16px; line-height: 1.8; color: var(--text-main); white-space: pre-line;">
                <?= htmlspecialchars(trans($recipe, 'steps')) ?>
            </div>
        </div>
    </div>

    <!-- Linked Fresh Produce Cards -->
    <?php if (!empty($linkedProducts)): ?>
        <section style="border-top: 1px solid var(--border-color); padding-top: 40px;">
            <h2 class="section-title">Fresh produce featured in this recipe</h2>
            <div class="product-grid">
                <?php foreach ($linkedProducts as $prod): ?>
                    <div class="product-card">
                        <div class="product-card-media">
                            <a href="<?= url('/product/' . $prod['slug']) ?>">
                                <img src="<?= asset('images/' . ($prod['image'] ?: 'harvest.png')) ?>" alt="<?= trans($prod, 'name') ?>">
                            </a>
                        </div>
                        <div class="product-card-body">
                            <h3 class="product-title">
                                <a href="<?= url('/product/' . $prod['slug']) ?>"><?= trans($prod, 'name') ?></a>
                            </h3>
                            <div class="product-specs"><?= htmlspecialchars($prod['unit_weight']) ?></div>
                            <div class="product-price-row">
                                <span class="product-price"><?= format_currency($prod['sale_price'] ?? $prod['price']) ?></span>
                            </div>
                            <form action="<?= url('/cart/add') ?>" method="POST" data-ajax-cart>
                                <?= csrf_field() ?>
                                <input type="hidden" name="product_id" value="<?= $prod['id'] ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-primary btn-block">+ Add to cart</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</div>
