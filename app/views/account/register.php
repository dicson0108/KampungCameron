<div class="container" style="padding-top: 48px; padding-bottom: 80px; max-width: 480px;">
    <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 40px 32px;">
        <h1 style="font-size: 28px; margin-bottom: 6px;"><?= __('register', 'Create an account') ?></h1>
        <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 24px;">Enjoy fast checkout, order history and saved delivery addresses.</p>

        <form action="<?= url('/account/register') ?>" method="POST">
            <?= csrf_field() ?>

            <div class="form-group">
                <label class="form-label"><?= __('full_name', 'Full name') ?> *</label>
                <input type="text" name="name" class="form-input" placeholder="e.g. Aisha Tan" required>
            </div>

            <div class="form-group">
                <label class="form-label"><?= __('email_address', 'Email address') ?> *</label>
                <input type="email" name="email" class="form-input" placeholder="e.g. aisha@example.com" required>
            </div>

            <div class="form-group">
                <label class="form-label"><?= __('phone_number', 'Phone number') ?></label>
                <input type="text" name="phone" class="form-input" placeholder="e.g. +60 12 345 6789">
            </div>

            <div class="form-group">
                <label class="form-label">Password *</label>
                <input type="password" name="password" class="form-input" placeholder="Choose a secure password" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top: 10px; margin-bottom: 20px;">
                <?= __('register', 'Create account') ?>
            </button>

            <div style="text-align: center; font-size: 14px; color: var(--text-muted);">
                Already have an account? <a href="<?= url('/account/login') ?>" style="font-weight: 600; color: var(--primary-green);">Sign in</a>
            </div>
        </form>
    </div>
</div>
