<div class="container" style="padding-top: 24px; padding-bottom: 60px;">
    <!-- Wholesale Hero -->
    <section class="hero-section" style="background: #eef6f9; border-color: #cce3ed; margin-bottom: 48px;">
        <div class="hero-content">
            <span class="hero-tag" style="color: var(--service-blue);"><?= __('b2b_banner_sub', 'FOR RESTAURANTS · CAFÉS · KITCHENS') ?></span>
            <h1 class="hero-title" style="color: var(--service-blue-dark);"><?= __('ws_hero_title', 'Your kitchen.<br>Our fresh promise.') ?></h1>
            <p class="hero-desc" style="color: #4a6372;"><?= __('ws_hero_desc', 'Plan the week with tier-based prices, scheduled supply and fresh produce your team can count on.') ?></p>
            <div style="display: flex; gap: 14px; flex-wrap: wrap;">
                <a href="<?= url('/wholesale/apply') ?>" class="btn btn-primary" style="background-color: var(--primary-green);"><?= __('ws_apply_btn', 'Apply for a wholesale account') ?></a>
                <a href="https://wa.me/60123456789" target="_blank" class="btn btn-secondary"><?= __('ws_whatsapp_btn', 'Chat on WhatsApp') ?></a>
            </div>
        </div>
        <div class="hero-media">
            <img src="<?= asset('images/farm.png') ?>" alt="Highlands Farm Sun">
        </div>
    </section>

    <!-- 6 Pillar Grid -->
    <section style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 56px;">
        <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 28px;">
            <div style="font-size: 24px; font-family: 'Lora', serif; color: var(--service-blue); margin-bottom: 8px;">01</div>
            <h3 style="font-size: 20px; margin-bottom: 6px;">Kitchen supply</h3>
            <p style="color: var(--text-muted); font-size: 14px;">Fresh staples for daily service.</p>
        </div>

        <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 28px;">
            <div style="font-size: 24px; font-family: 'Lora', serif; color: var(--service-blue); margin-bottom: 8px;">02</div>
            <h3 style="font-size: 20px; margin-bottom: 6px;">Custom sourcing</h3>
            <p style="color: var(--text-muted); font-size: 14px;">Tell us what your menu needs.</p>
        </div>

        <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 28px;">
            <div style="font-size: 24px; font-family: 'Lora', serif; color: var(--service-blue); margin-bottom: 8px;">03</div>
            <h3 style="font-size: 20px; margin-bottom: 6px;">Scheduled delivery</h3>
            <p style="color: var(--text-muted); font-size: 14px;">Plan a regular weekly supply.</p>
        </div>

        <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 28px;">
            <div style="font-size: 24px; font-family: 'Lora', serif; color: var(--service-blue); margin-bottom: 8px;">04</div>
            <h3 style="font-size: 20px; margin-bottom: 6px;">Standing orders</h3>
            <p style="color: var(--text-muted); font-size: 14px;">Set it once. Keep kitchens stocked.</p>
        </div>

        <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 28px;">
            <div style="font-size: 24px; font-family: 'Lora', serif; color: var(--service-blue); margin-bottom: 8px;">05</div>
            <h3 style="font-size: 20px; margin-bottom: 6px;">Freshness handling</h3>
            <p style="color: var(--text-muted); font-size: 14px;">Careful selection and packing.</p>
        </div>

        <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 28px;">
            <div style="font-size: 24px; font-family: 'Lora', serif; color: var(--service-blue); margin-bottom: 8px;">06</div>
            <h3 style="font-size: 20px; margin-bottom: 6px;">Ongoing supply</h3>
            <p style="color: var(--text-muted); font-size: 14px;">A consistent produce partner.</p>
        </div>
    </section>

    <!-- Bottom Process Bar -->
    <section style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 48px; text-align: left;">
        <h2 style="font-size: 32px; margin-bottom: 24px;">From application to your first delivery</h2>
        <div style="display: flex; gap: 32px; align-items: center; margin-bottom: 32px; font-size: 15px; font-weight: 500; flex-wrap: wrap;">
            <span>1 Share business details</span>
            <span>→</span>
            <span>2 Get approved</span>
            <span>→</span>
            <span>3 Start ordering</span>
        </div>
        <div>
            <a href="<?= url('/wholesale/apply') ?>" class="btn btn-primary btn-lg"><?= __('ws_apply_btn', 'Apply now') ?></a>
        </div>
    </section>
</div>
