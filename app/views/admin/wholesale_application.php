<div class="admin-page-header">
    <div><h1 class="admin-page-title">Review Wholesale Application</h1><p><?= e($application['business_name']) ?></p></div>
    <a class="btn btn-secondary" href="<?= url('/admin/wholesale') ?>">Back to Applications</a>
</div>
<section class="admin-card" style="padding: 24px;">
    <dl class="application-details">
        <?php foreach (['business_name' => 'Business', 'business_type' => 'Business Type', 'registration_number' => 'SSM Registration', 'contact_person' => 'Contact', 'contact_email' => 'Email', 'contact_phone' => 'Phone', 'status' => 'Status'] as $key => $label): ?>
            <div><dt><?= e($label) ?></dt><dd><?= e($application[$key]) ?></dd></div>
        <?php endforeach; ?>
    </dl>
    <?php if (!empty($application['document_path'])): ?>
        <p><a href="<?= url('/uploads/' . rawurlencode(basename($application['document_path']))) ?>" target="_blank" rel="noopener">View Supporting Document ↗</a></p>
    <?php else: ?><p>No supporting document uploaded.</p><?php endif; ?>
    <form action="<?= url('/admin/wholesale/application/action') ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="app_id" value="<?= (int)$application['id'] ?>">
        <div class="form-group"><label class="form-label" for="application-tier">Pricing Tier</label>
            <select id="application-tier" name="tier_id" class="form-select">
                <option value="">Choose a Tier</option>
                <?php foreach ($tiers as $tier): ?><option value="<?= (int)$tier['id'] ?>" <?= ($application['tier_id'] ?? 0) == $tier['id'] ? 'selected' : '' ?>><?= e($tier['name']) ?></option><?php endforeach; ?>
            </select>
        </div>
        <div class="form-group"><label class="form-label" for="application-notes">Review Notes</label><textarea id="application-notes" name="notes" class="form-input" rows="4"><?= e($application['admin_notes'] ?? '') ?></textarea></div>
        <button class="btn btn-primary" name="action" value="approve">Approve &amp; Assign Tier</button>
        <button class="btn btn-secondary" name="action" value="reject" onclick="return confirm('Reject this application and remove wholesale access?')">Reject Application</button>
    </form>
</section>
