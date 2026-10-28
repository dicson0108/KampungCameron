<div>
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Reviews Moderation</h1>
            <p style="color: var(--admin-text-muted); font-size: 14px;">Approve or reject customer product feedback before publishing on product pages.</p>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-table-scroll" role="region" aria-label="Reviews data table" tabindex="0"><table class="admin-table">
            <thead>
                <tr>
                    <th>Produce Item</th>
                    <th>Customer</th>
                    <th>Rating</th>
                    <th>Review Content</th>
                    <th>Status</th>
                    <th style="text-align: right; width: 180px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reviews)): ?>
                    <tr><td colspan="6" style="padding: 32px; text-align: center; color: var(--admin-text-muted);">No reviews submitted yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($reviews as $rev): ?>
                        <tr>
                            <td style="font-weight: 600;"><?= htmlspecialchars($rev['product_name']) ?></td>
                            <td><?= htmlspecialchars($rev['reviewer_name']) ?></td>
                            <td style="color: #b78103; font-weight: 700;"><?= str_repeat('★', $rev['rating']) ?></td>
                            <td style="max-width: 320px; font-size: 13px; line-height: 1.5;">
                                "<?= htmlspecialchars($rev['review_text']) ?>"
                            </td>
                            <td>
                                <?php if ($rev['status'] === 'approved'): ?>
                                    <span class="badge badge-in-stock">Approved</span>
                                <?php elseif ($rev['status'] === 'pending'): ?>
                                    <span class="badge badge-low-stock">Pending</span>
                                <?php else: ?>
                                    <span class="badge badge-sold-out">Rejected</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: right;">
                                <form action="<?= url('/admin/reviews/action') ?>" method="POST" style="display: flex; gap: 6px; justify-content: flex-end;">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= $rev['id'] ?>">

                                    <?php if ($rev['status'] !== 'approved'): ?>
                                        <button type="submit" name="action" value="approve" class="btn btn-primary" style="padding: 4px 8px; font-size: 12px;">Approve</button>
                                    <?php endif; ?>
                                    <?php if ($rev['status'] !== 'rejected'): ?>
                                        <button type="submit" name="action" value="reject" class="btn btn-secondary" style="padding: 4px 8px; font-size: 12px;">Reject</button>
                                    <?php endif; ?>
                                    <button type="submit" name="action" value="delete" class="btn btn-secondary" style="padding: 4px 6px; font-size: 12px; color: #ef4444;" title="Delete">✕</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table></div>
    </div>
</div>
