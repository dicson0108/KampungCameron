<div style="max-width: 860px;">
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title"><?= $recipe ? 'Edit Recipe' : 'New Recipe' ?></h1>
            <a href="<?= url('/admin/recipes') ?>" style="font-size: 13px; color: var(--admin-text-muted);">← Back to recipes list</a>
        </div>
    </div>

    <div class="admin-card" style="padding: 32px;">
        <form action="<?= url('/admin/recipes/save') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= htmlspecialchars($recipe['id'] ?? 0) ?>">

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">Recipe Title (EN) *</label>
                    <input type="text" name="title_en" class="form-input" value="<?= htmlspecialchars($recipe['title_en'] ?? '') ?>" placeholder="e.g. Highland harvest salad" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Recipe Title (中文) *</label>
                    <input type="text" name="title_zh" class="form-input" value="<?= htmlspecialchars($recipe['title_zh'] ?? '') ?>" placeholder="e.g. 高地丰收沙拉" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select">
                        <option value="veggie_recipe" <?= ($recipe['category'] ?? '') === 'veggie_recipe' ? 'selected' : '' ?>>Veggie Recipe</option>
                        <option value="health_nourishment" <?= ($recipe['category'] ?? '') === 'health_nourishment' ? 'selected' : '' ?>>Health Nourishment</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Prep Time (min)</label>
                    <input type="number" name="prep_time_minutes" class="form-input" value="<?= htmlspecialchars($recipe['prep_time_minutes'] ?? '15') ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Cook Time (min)</label>
                    <input type="number" name="cooking_time_minutes" class="form-input" value="<?= htmlspecialchars($recipe['cooking_time_minutes'] ?? '15') ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Servings</label>
                    <input type="text" name="servings" class="form-input" value="<?= htmlspecialchars($recipe['servings'] ?? '2-4 portions') ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Linked Fresh Produce Ingredients (Enables "Add All to Cart")</label>
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; background: var(--warm-paper); padding: 14px; border-radius: var(--radius-sm); border: 1px solid var(--admin-border);">
                    <?php foreach ($allProducts as $ap): ?>
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; cursor: pointer;">
                            <input type="checkbox" name="products[]" value="<?= $ap['id'] ?>" <?= in_array($ap['id'], $linkedProductIds) ? 'checked' : '' ?>>
                            <span><?= htmlspecialchars($ap['name_en']) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">Ingredients (EN) *</label>
                    <textarea name="ingredients_en" rows="5" class="form-textarea" placeholder="Line by line list of ingredients" required><?= htmlspecialchars($recipe['ingredients_en'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Ingredients (中文) *</label>
                    <textarea name="ingredients_zh" rows="5" class="form-textarea" placeholder="每行一种食材及分量" required><?= htmlspecialchars($recipe['ingredients_zh'] ?? '') ?></textarea>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">Cooking Instructions (EN) *</label>
                    <textarea name="steps_en" rows="6" class="form-textarea" placeholder="Step 1, Step 2..." required><?= htmlspecialchars($recipe['steps_en'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Cooking Instructions (中文) *</label>
                    <textarea name="steps_zh" rows="6" class="form-textarea" placeholder="步骤 1，步骤 2..." required><?= htmlspecialchars($recipe['steps_zh'] ?? '') ?></textarea>
                </div>
            </div>

            <div style="display: flex; gap: 14px; margin-top: 12px;">
                <button type="submit" class="btn btn-primary btn-lg">Save Recipe</button>
                <a href="<?= url('/admin/recipes') ?>" class="btn btn-secondary btn-lg">Cancel</a>
            </div>
        </form>
    </div>
</div>
