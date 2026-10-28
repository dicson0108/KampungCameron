<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Administrator Sign In — Kampung Cameron</title>
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body style="background-color: var(--service-blue); display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 20px;">
    <div style="background: var(--surface-white); border-radius: var(--radius-md); padding: 48px 40px; width: 100%; max-width: 440px; box-shadow: var(--shadow-lg);">
        <div style="margin-bottom: 24px; text-align: center;">
            <h1 style="font-size: 28px; color: var(--service-blue); margin-bottom: 6px;">Kampung Cameron</h1>
            <div style="font-size: 12px; letter-spacing: 0.08em; text-transform: uppercase; color: var(--text-muted); font-weight: 700;">Back-Office Store Administration</div>
        </div>

        <?php foreach (get_flashes() as $type => $messages): ?>
            <?php foreach ($messages as $msg): ?>
                <div class="alert alert-error"><span><?= htmlspecialchars($msg) ?></span></div>
            <?php endforeach; ?>
        <?php endforeach; ?>

        <form action="<?= url('/admin/login') ?>" method="POST">
            <?= csrf_field() ?>

            <div class="form-group">
                <label class="form-label">Administrator Email</label>
                <input type="email" name="email" class="form-input" value="admin@kampungcameron.com" required>
            </div>

            <div class="form-group">
                <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                    <label class="form-label" style="margin-bottom: 0;">Password</label>
                    <span style="font-size: 12px; color: var(--text-muted);">Admin@123</span>
                </div>
                <input type="password" name="password" class="form-input" value="Admin@123" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top: 12px; background-color: var(--service-blue); border-color: var(--service-blue);">
                Sign in to Dashboard
            </button>
        </form>

        <div style="text-align: center; margin-top: 24px;">
            <a href="<?= url('/') ?>" style="font-size: 13px; color: var(--text-muted);">← Return to public storefront</a>
        </div>
    </div>
</body>
</html>
