<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title"><?= e($title) ?></h1>
        <p class="admin-page-subtitle">Manage store details, delivery fees, and payment choices shown at checkout.</p>
    </div>
</div>

<div class="admin-card admin-settings-card">
    <form action="<?= url('/admin/settings/save') ?>" method="POST">
        <?= csrf_field() ?>

        <?php $settingsMap = array_column($settings, null, 'setting_key'); ?>

        <div style="display:flex; flex-direction:column; gap:1.5rem;">
            <!-- Store Identity -->
            <div style="border-bottom: 1px solid var(--admin-border); padding-bottom: 1.5rem;">
                <h2 style="margin-top:0; margin-bottom:1rem; font-size:1.1rem; color:var(--primary-green);">Brand & Identity</h2>
                
                <div class="form-group" style="margin-bottom:1rem;">
                    <label class="form-label" for="setting_store_name">Store Name</label>
                    <input type="text" id="setting_store_name" name="settings[store_name]" class="form-input" value="<?= e($settingsMap['store_name']['setting_value'] ?? '') ?>" required>
                    <small style="color:var(--admin-text-muted); font-size:0.8rem;"><?= e($settingsMap['store_name']['description'] ?? '') ?></small>
                </div>
            </div>

            <!-- Logistics & Fulfillment -->
            <div style="border-bottom: 1px solid var(--admin-border); padding-bottom: 1.5rem;">
                <h2 style="margin-top:0; margin-bottom:1rem; font-size:1.1rem; color:var(--primary-green);">Fulfillment & Logistics</h2>
                
                <div class="form-group" style="margin-bottom:1rem;">
                    <label class="form-label" for="setting_flat_shipping_fee">Flat Standard Shipping Fee (RM)</label>
                    <div style="position:relative; max-width:200px;">
                        <span style="position:absolute; left:12px; top:10px; color:var(--admin-text-muted); font-weight:600;">RM</span>
                        <input type="number" step="0.01" min="0" id="setting_flat_shipping_fee" name="settings[flat_shipping_fee]" class="form-input" style="padding-left:45px;" value="<?= e($settingsMap['flat_shipping_fee']['setting_value'] ?? '8.00') ?>" required>
                    </div>
                    <small style="color:var(--admin-text-muted); font-size:0.8rem;"><?= e($settingsMap['flat_shipping_fee']['description'] ?? '') ?></small>
                </div>
            </div>

            <!-- Contact & Support -->
            <div style="border-bottom: 1px solid var(--admin-border); padding-bottom: 1.5rem;">
                <h2 style="margin-top:0; margin-bottom:1rem; font-size:1.1rem; color:var(--primary-green);">Customer Support Contacts</h2>
                
                <div class="portal-form-grid" style="gap:1rem; margin-bottom:1rem;">
                    <div class="form-group">
                        <label class="form-label" for="setting_contact_email">Support Email</label>
                        <input type="email" autocomplete="email" id="setting_contact_email" name="settings[contact_email]" class="form-input" value="<?= e($settingsMap['contact_email']['setting_value'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="setting_contact_phone">Hotline Phone</label>
                        <input type="tel" autocomplete="tel" id="setting_contact_phone" name="settings[contact_phone]" class="form-input" value="<?= e($settingsMap['contact_phone']['setting_value'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label" for="setting_contact_whatsapp">WhatsApp Hotline</label>
                    <input type="tel" autocomplete="tel" id="setting_contact_whatsapp" name="settings[contact_whatsapp]" class="form-input" style="max-width:380px;" value="<?= e($settingsMap['contact_whatsapp']['setting_value'] ?? '') ?>" required>
                </div>
            </div>

            <!-- Payment Methods -->
            <div>
                <h2 style="margin-top:0; margin-bottom:1rem; font-size:1.1rem; color:var(--primary-green);">Payment Channels</h2>
                <div class="form-group">
                    <label class="form-label" for="setting_enabled_payment_methods">Checkout Payment Options (comma-separated codes)</label>
                    <input type="text" id="setting_enabled_payment_methods" name="settings[enabled_payment_methods]" class="form-input" value="<?= e($settingsMap['enabled_payment_methods']['setting_value'] ?? 'fpx,card,grabpay,tng,cod') ?>" required>
                    <small style="color:var(--admin-text-muted); font-size:0.8rem;">These options record a payment choice; they do not connect a payment gateway. Supported codes: <code>fpx</code> (Online Banking), <code>card</code> (Credit/Debit Card), <code>grabpay</code> (GrabPay), <code>tng</code> (Touch 'n Go eWallet), <code>cod</code> (Cash on Delivery)</small>
                </div>
            </div>

            <div style="margin-top:1rem; display:flex; justify-content:flex-end;">
                <button type="submit" class="btn btn-primary">Save Settings</button>
            </div>
        </div>
    </form>
</div>
