<div class="container" style="padding-top: 32px; padding-bottom: 60px; max-width: 600px;">
    <!-- Account Breadcrumbs -->
    <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 24px;">
        <h1 style="font-size: 34px;">Profile & Password</h1>
        <a href="<?= url('/account/dashboard') ?>" class="btn btn-secondary" style="font-size: 13px;">← Back to dashboard</a>
    </div>

    <?php $activeAccountTab = 'profile'; require __DIR__ . '/nav.php'; ?>

    <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 36px 32px;">
        <form action="<?= url('/account/profile') ?>" method="POST">
            <?= csrf_field() ?>

            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-input" value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Email Address (Read-only)</label>
                <input type="email" class="form-input" value="<?= htmlspecialchars($user['email']) ?>" disabled style="background-color: var(--warm-paper); cursor: not-allowed;">
            </div>

            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-input" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
            </div>

            <div style="border-top: 1px solid var(--border-color); margin: 28px 0; padding-top: 24px;">
                <h3 style="font-size: 18px; margin-bottom: 6px;">Change Password</h3>
                <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 16px;">Leave blank if you do not wish to change your password.</p>

                <div class="form-group">
                    <label class="form-label">New Password</label>
                    <input type="password" name="new_password" class="form-input" placeholder="Enter new password">
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg">Save Profile</button>
        </form>
    </div>
</div>
