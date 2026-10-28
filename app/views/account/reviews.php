<div class="container" style="padding-top: 32px; padding-bottom: 60px;">
    <!-- Account Breadcrumbs -->
    <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 24px;">
        <h1 style="font-size: 34px;">My Reviews</h1>
        <a href="<?= url('/account/dashboard') ?>" class="btn btn-secondary" style="font-size: 13px;">← Back to dashboard</a>
    </div>

    <?php $activeAccountTab = 'reviews'; require __DIR__ . '/nav.php'; ?>

    <?php if (empty($reviews)): ?>
        <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 60px 20px; text-align: center;">
            <p style="font-size: 16px; color: var(--text-muted); margin-bottom: 16px;">You haven't submitted any reviews yet.</p>
            <p style="font-size: 14px; color: var(--text-muted);">Reviews can be posted on product pages after purchasing fresh produce.</p>
        </div>
    <?php else: ?>
        <div style="display: flex; flex-direction: column; gap: 16px;">
            <?php foreach ($reviews as $rev): ?>
                <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 20px; display: flex; gap: 18px; align-items: flex-start;">
                    <img src="<?= asset('images/' . ($rev['image'] ?: 'harvest.png')) ?>" alt="<?= trans($rev, 'name') ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: var(--radius-sm);">
                    <div style="flex: 1;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                            <h3 style="font-size: 16px; font-weight: 600;">
                                <a href="<?= url('/product/' . $rev['slug']) ?>"><?= trans($rev, 'name') ?></a>
                            </h3>
                            <div>
                                <?php if ($rev['status'] === 'approved'): ?>
                                    <span class="badge badge-in-stock">Published</span>
                                <?php elseif ($rev['status'] === 'pending'): ?>
                                    <span class="badge badge-low-stock">Pending Moderation</span>
                                <?php else: ?>
                                    <span class="badge badge-sold-out">Rejected</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div style="color: #b78103; font-weight: 700; margin-bottom: 6px; font-size: 14px;">
                            <?= str_repeat('★', $rev['rating']) ?>
                        </div>
                        <p style="color: var(--text-main); font-size: 14px; line-height: 1.6;">
                            <?= htmlspecialchars($rev['review_text']) ?>
                        </p>
                        <div style="font-size: 12px; color: var(--text-muted); margin-top: 8px;">
                            Submitted on <?= date('d M Y', strtotime($rev['created_at'])) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
