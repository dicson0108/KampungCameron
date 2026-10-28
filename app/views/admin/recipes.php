<div>
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Blog & Recipes</h1>
            <p style="color: var(--admin-text-muted); font-size: 14px;">Highland cooking guides and linked fresh produce ingredients.</p>
        </div>
        <a href="<?= url('/admin/recipes/edit') ?>" class="btn btn-primary">+ Add New Recipe</a>
    </div>

    <div class="admin-card">
        <div class="admin-table-scroll" role="region" aria-label="Recipes data table" tabindex="0"><table class="admin-table">
            <thead>
                <tr>
                    <th>Recipe Title</th>
                    <th>Chinese Title (中文)</th>
                    <th>Category</th>
                    <th>Time</th>
                    <th>Servings</th>
                    <th>Status</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recipes as $r): ?>
                    <tr>
                        <td style="font-weight: 600;"><?= htmlspecialchars($r['title_en']) ?></td>
                        <td><?= htmlspecialchars($r['title_zh']) ?></td>
                        <td><?= $r['category'] === 'health_nourishment' ? 'Health Nourishment' : 'Veggie Recipe' ?></td>
                        <td><?= $r['prep_time_minutes'] + $r['cooking_time_minutes'] ?> mins</td>
                        <td><?= htmlspecialchars($r['servings']) ?></td>
                        <td>
                            <span class="badge <?= $r['is_published'] ? 'badge-in-stock' : 'badge-sold-out' ?>">
                                <?= $r['is_published'] ? 'Published' : 'Draft' ?>
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <a href="<?= url('/admin/recipes/edit?id=' . $r['id']) ?>" class="btn btn-secondary" style="padding: 4px 10px; font-size: 12px;">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table></div>
    </div>
</div>
