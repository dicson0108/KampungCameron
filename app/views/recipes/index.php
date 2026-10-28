<div class="container" style="padding-top: 32px; padding-bottom: 60px;">
    <div style="margin-bottom: 28px;">
        <h1 style="font-size: 38px; margin-bottom: 8px;">Highland Recipes & Cooking Inspiration</h1>
        <p style="color: var(--text-muted); font-size: 15px;">Healthy, wholesome meals created with fresh ingredients straight from Cameron Highlands.</p>
    </div>

    <!-- Category Tabs -->
    <div style="display: flex; gap: 10px; margin-bottom: 36px;">
        <a href="<?= url('/recipes') ?>" class="btn <?= empty($selectedCat) ? 'btn-primary' : 'btn-secondary' ?>" style="border-radius: var(--radius-pill); font-size: 13px; padding: 6px 18px;">
            All Recipes
        </a>
        <a href="<?= url('/recipes?cat=veggie_recipe') ?>" class="btn <?= $selectedCat === 'veggie_recipe' ? 'btn-primary' : 'btn-secondary' ?>" style="border-radius: var(--radius-pill); font-size: 13px; padding: 6px 18px;">
            Veggie Recipes
        </a>
        <a href="<?= url('/recipes?cat=health_nourishment') ?>" class="btn <?= $selectedCat === 'health_nourishment' ? 'btn-primary' : 'btn-secondary' ?>" style="border-radius: var(--radius-pill); font-size: 13px; padding: 6px 18px;">
            Health Nourishment
        </a>
    </div>

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
                        <?= $rec['category'] === 'health_nourishment' ? 'Health Nourishment' : 'Veggie Recipe' ?> · <?= $rec['prep_time_minutes'] + $rec['cooking_time_minutes'] ?> min · <?= htmlspecialchars($rec['servings']) ?>
                    </div>
                    <h3 class="recipe-card-title">
                        <a href="<?= url('/recipes/' . $rec['slug']) ?>"><?= trans($rec, 'title') ?></a>
                    </h3>
                    <p style="color: var(--text-muted); font-size: 14px; line-height: 1.6; margin-bottom: 16px;">
                        <?= htmlspecialchars(trans($rec, 'excerpt')) ?>
                    </p>
                    <div style="margin-top: auto;">
                        <a href="<?= url('/recipes/' . $rec['slug']) ?>" class="btn btn-secondary"><?= __('read_recipe', 'Read recipe') ?> →</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
