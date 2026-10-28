<div class="container" style="padding-top: 48px; padding-bottom: 80px; max-width: 460px;">
    <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 40px 32px;">
        <h1 style="font-size: 28px; margin-bottom: 6px;"><?= __('login', 'Sign in') ?></h1>
        <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 24px;">Welcome back to Kampung Cameron.</p>

        <form action="<?= url('/account/login') ?>" method="POST">
            <?= csrf_field() ?>

            <div class="form-group">
                <label class="form-label"><?= __('email_address', 'Email address') ?></label>
                <input type="email" name="email" class="form-input" placeholder="you@example.com" value="aisha@example.com" required>
            </div>

            <div class="form-group">
                <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                    <label class="form-label" style="margin-bottom: 0;">Password</label>
                    <span style="font-size: 12px; color: var(--text-muted);">Customer@123</span>
                </div>
                <input type="password" name="password" class="form-input" value="Customer@123" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top: 10px; margin-bottom: 20px;">
                <?= __('login', 'Sign in') ?>
            </button>

            <div style="text-align: center; font-size: 14px; color: var(--text-muted);">
                Don't have an account? <a href="<?= url('/account/register') ?>" style="font-weight: 600; color: var(--primary-green);">Register here</a>
            </div>

            <div style="border-top: 1px solid var(--border-color); margin-top: 24px; padding-top: 16px; font-size: 12px; color: var(--text-muted); text-align: center;">
                Wholesale customer? Sign in with your business credentials above.
            </div>
        </form>
    </div>
</div>
