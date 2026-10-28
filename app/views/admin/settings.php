<div class="admin-page-header">
    <div>
        <h1><?= e($title) ?></h1>
        <p class="admin-page-subtitle">Configure store contact details, logistics fees, and checkout payment gateway settings.</p>
    </div>
</div>

<div class="admin-card" style="max-width: 800px;">
    <form action="<?= url('/admin/settings/save') ?>" method="POST">
        <?= csrf_field() ?>

        <?php 
        $settingsMap = [];
        foreach ($settings as $s) {
            $settingsMap[$s['setting_key']] = $s;
        }
        ?>

        <div style="display:flex; flex-direction:column; gap:1.5rem;">
            <!-- Store Identity -->
            <div style="border-bottom: 1px solid var(--c-border); padding-bottom: 1.5rem;">
                <h3 style="margin-top:0; margin-bottom:1rem; font-size:1.1rem; color:var(--c-primary);">Brand & Identity</h3>
                
                <div class="form-group" style="margin-bottom:1rem;">
                    <label class="form-label" for="setting_store_name">Store Name</label>
                    <input type="text" id="setting_store_name" name="settings[store_name]" class="form-control" value="<?= e($settingsMap['store_name']['setting_value'] ?? '') ?>" required>
                    <small style="color:var(--c-ink-muted); font-size:0.8rem;"><?= e($settingsMap['store_name']['description'] ?? '') ?></small>
                </div>
            </div>

            <!-- Logistics & Fulfillment -->
            <div style="border-bottom: 1px solid var(--c-border); padding-bottom: 1.5rem;">
                <h3 style="margin-top:0; margin-bottom:1rem; font-size:1.1rem; color:var(--c-primary);">Fulfillment & Logistics</h3>
                
                <div class="form-group" style="margin-bottom:1rem;">
                    <label class="form-label" for="setting_flat_shipping_fee">Flat Standard Shipping Fee (RM)</label>
                    <div style="position:relative; max-width:200px;">
                        <span style="position:absolute; left:12px; top:10px; color:var(--c-ink-muted); font-weight:600;">RM</span>
                        <input type="number" step="0.50" min="0" id="setting_flat_shipping_fee" name="settings[flat_shipping_fee]" class="form-control" style="padding-left:45px;" value="<?= e($settingsMap['flat_shipping_fee']['setting_value'] ?? '8.00') ?>" required>
                    </div>
                    <small style="color:var(--c-ink-muted); font-size:0.8rem;"><?= e($settingsMap['flat_shipping_fee']['description'] ?? '') ?></small>
                </div>
            </div>

            <!-- Contact & Support -->
            <div style="border-bottom: 1px solid var(--c-border); padding-bottom: 1.5rem;">
                <h3 style="margin-top:0; margin-bottom:1rem; font-size:1.1rem; color:var(--c-primary);">Customer Support Contacts</h3>
                
                <div class="grid grid-2" style="gap:1rem; margin-bottom:1rem;">
                    <div class="form-group">
                        <label class="form-label" for="setting_contact_email">Support Email</label>
                        <input type="email" id="setting_contact_email" name="settings[contact_email]" class="form-control" value="<?= e($settingsMap['contact_email']['setting_value'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="setting_contact_phone">Hotline Phone</label>
                        <input type="text" id="setting_contact_phone" name="settings[contact_phone]" class="form-control" value="<?= e($settingsMap['contact_phone']['setting_value'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label" for="setting_contact_whatsapp">WhatsApp Hotline</label>
                    <input type="text" id="setting_contact_whatsapp" name="settings[contact_whatsapp]" class="form-control" style="max-width:380px;" value="<?= e($settingsMap['contact_whatsapp']['setting_value'] ?? '') ?>" required>
                </div>
            </div>

            <!-- Payment Methods -->
            <div>
                <h3 style="margin-top:0; margin-bottom:1rem; font-size:1.1rem; color:var(--c-primary);">Payment Channels</h3>
                <div class="form-group">
                    <label class="form-label" for="setting_enabled_payment_methods">Active Payment Gateway Codes (comma-separated)</label>
                    <input type="text" id="setting_enabled_payment_methods" name="settings[enabled_payment_methods]" class="form-control" value="<?= e($settingsMap['enabled_payment_methods']['setting_value'] ?? 'fpx,card,grabpay,tng,cod') ?>" required>
                    <small style="color:var(--c-ink-muted); font-size:0.8rem;">Supported options: <code>fpx</code> (Online Banking), <code>card</code> (Credit/Debit Card), <code>grabpay</code> (GrabPay), <code>tng</code> (Touch 'n Go eWallet), <code>cod</code> (Cash on Delivery)</small>
                </div>
            </div>

            <div style="margin-top:1rem; display:flex; justify-content:flex-end;">
                <button type="submit" class="btn btn-primary">Save Settings</button>
            </div>
        </div>
    </form>
</div>
