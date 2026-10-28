<div class="container" style="padding-top: 60px; padding-bottom: 80px; max-width: 600px; text-align: center;">
    <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 48px 36px;">
        <div style="width: 60px; height: 60px; border-radius: 50%; background: #eef6f9; color: var(--service-blue); display: flex; align-items: center; justify-content: center; font-size: 28px; margin: 0 auto 20px;">
            ⏳
        </div>
        <h1 style="font-size: 28px; margin-bottom: 12px;">Application Pending Review</h1>
        <p style="color: var(--text-muted); font-size: 15px; line-height: 1.6; margin-bottom: 24px;">
            Thank you for applying for wholesale supply for <strong><?= htmlspecialchars($businessName ?? 'your business') ?></strong>.
            Our farm team will review your business credentials within 1 business day and assign your tier pricing.
        </p>

        <div style="background: var(--warm-paper); border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 18px; font-size: 14px; text-align: left; margin-bottom: 28px;">
            <div style="font-weight: 600; margin-bottom: 4px;">Need urgent supplies for tomorrow?</div>
            <div style="color: var(--text-muted);">Contact our kitchen dispatch hotline directly on WhatsApp.</div>
        </div>

        <div style="display: flex; gap: 14px; justify-content: center;">
            <a href="https://wa.me/60123456789" target="_blank" class="btn btn-secondary">Chat on WhatsApp</a>
            <a href="<?= url('/shop') ?>" class="btn btn-primary">Browse retail shop</a>
        </div>
    </div>
</div>
