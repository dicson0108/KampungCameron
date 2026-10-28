<div class="container">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <span class="hero-tag"><?= __('hero_tag', 'FROM THE HIGHLANDS, WITH CARE') ?></span>
            <h1 class="hero-title"><?= current_lang() === 'zh' ? '新鲜食材，<br>幸福厨房。' : 'Fresh roots.<br>Happy kitchens.' ?></h1>
            <p class="hero-desc"><?= __('hero_desc', 'Bring the goodness of Cameron Highlands home. Seasonal vegetables, hand-picked for your everyday.') ?></p>
            <div>
                <a href="<?= url('/shop') ?>" class="btn btn-primary btn-lg"><?= __('hero_cta', 'Shop this week’s harvest') ?></a>
            </div>
            <div style="margin-top: 32px; font-size: 13px; color: var(--text-muted); display: flex; align-items: center; gap: 12px;">
                <span>01 / 03</span>
                <span>● ○ ○</span>
                <span style="cursor: pointer;">← →</span>
            </div>
        </div>
        <div class="hero-media">
            <img src="<?= asset('images/harvest.png') ?>" alt="Cameron Highland Produce">
        </div>
    </section>

    <!-- USP Bar -->
    <div class="usp-bar">
        <div class="usp-item"><span class="usp-check">✓</span> <?= __('usp_highland', 'Highland grown') ?></div>
        <div class="usp-item"><span class="usp-check">✓</span> <?= __('usp_packed', 'Carefully packed') ?></div>
        <div class="usp-item"><span class="usp-check">✓</span> <?= __('usp_shipping', 'Flat delivery RM 8') ?></div>
        <div class="usp-item"><span class="usp-check">✓</span> <?= __('usp_kitchens', 'For homes & kitchens') ?></div>
    </div>

    <!-- Category Tiles -->
    <section style="margin-bottom: 48px;">
        <h2 class="section-title"><?= __('cat_section_title', 'A little freshness for every meal') ?></h2>
        <div class="category-grid">
            <?php foreach ($categories as $cat): ?>
                <a href="<?= url('/shop?category=' . $cat['id']) ?>" class="category-tile">
                    <img src="<?= asset('images/' . ($cat['image'] ?: 'harvest.png')) ?>" alt="<?= trans($cat, 'name') ?>" class="category-tile-img">
                    <span class="category-tile-title"><?= trans($cat, 'name') ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Favourites / Featured Produce -->
    <section style="margin-bottom: 48px;">
        <h2 class="section-title"><?= __('favourites_title', 'The favourites, freshly picked') ?></h2>
        <div class="product-grid">
            <?php foreach ($favourites as $prod): ?>
                <?php require __DIR__ . '/product_card.php'; ?>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Promo Highlights 2-Cards Grid -->
    <section class="promo-banners-grid">
        <div class="promo-card">
            <h3 class="promo-card-title"><?= __('promo_card1_title', 'Your first harvest, 10% off') ?></h3>
            <p class="promo-card-desc"><?= __('promo_card1_desc', 'Use FRESH10 at checkout · Terms apply') ?></p>
            <a href="<?= url('/shop') ?>" class="promo-card-link"><?= __('explore_offers', 'Explore offers') ?> →</a>
        </div>
        <div class="promo-card" style="background: #fdf8ea; border-color: #f7ecd2;">
            <h3 class="promo-card-title"><?= __('promo_card2_title', 'A box full of possibilities') ?></h3>
            <p class="promo-card-desc"><?= __('promo_card2_desc', 'Veggie Mystery Box · Seasonal variety') ?></p>
            <a href="<?= url('/product/veggie-mystery-box') ?>" class="promo-card-link"><?= __('explore_offers', 'Explore offers') ?> →</a>
        </div>
    </section>

    <!-- B2B Wholesale Banner -->
    <section class="b2b-highlight-banner">
        <div class="b2b-highlight-left">
            <h3><?= __('b2b_banner_title', 'A fresher partner for your kitchen.') ?></h3>
            <p><?= __('b2b_banner_desc', 'Bulk prices, standing orders and dependable weekly supply.') ?></p>
            <a href="<?= url('/wholesale') ?>" class="btn btn-primary" style="background-color: var(--primary-green); border-color: var(--primary-green);"><?= __('b2b_banner_btn', 'Explore wholesale') ?></a>
        </div>
        <div class="b2b-highlight-right">
            <?= __('b2b_banner_sub', 'FOR RESTAURANTS, CAFÉS & KITCHENS') ?>
        </div>
    </section>

    <!-- Recipes Section -->
    <section style="margin-bottom: 60px;">
        <h2 class="section-title"><?= __('recipes_section_title', 'Good ingredients. Better ideas.') ?></h2>
        <div class="recipes-grid">
            <?php foreach ($recipes as $rec): ?>
                <div class="recipe-card">
                    <div class="recipe-card-media">
                        <a href="<?= url('/recipes/' . $rec['slug']) ?>">
                            <img src="<?= asset('images/' . ($rec['image'] ?: 'salad.png')) ?>" alt="<?= trans($rec, 'title') ?>">
                        </a>
                    </div>
                    <div class="recipe-card-body">
                        <div class="recipe-card-meta">
                            <?= $rec['category'] === 'health_nourishment' ? 'Health Nourishment' : 'Veggie Recipe' ?> · <?= $rec['prep_time_minutes'] + $rec['cooking_time_minutes'] ?> min
                        </div>
                        <h3 class="recipe-card-title">
                            <a href="<?= url('/recipes/' . $rec['slug']) ?>"><?= trans($rec, 'title') ?></a>
                        </h3>
                        <div style="margin-top: auto;">
                            <a href="<?= url('/recipes/' . $rec['slug']) ?>" class="btn btn-secondary"><?= __('read_recipe', 'Read recipe') ?> →</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>
