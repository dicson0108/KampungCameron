<div class="container" style="padding-top: 32px; padding-bottom: 60px; max-width: 680px;">
    <h1 style="font-size: 34px; margin-bottom: 8px;">Apply for a wholesale account</h1>
    <p style="color: var(--text-muted); font-size: 15px; margin-bottom: 32px;">
        Register your restaurant, kitchen or café to unlock tier-based wholesale pricing, quick order forms and scheduled standing orders.
    </p>

    <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 36px 32px;">
        <form action="<?= url('/wholesale/apply') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="form-group">
                <label class="form-label" for="business_name">Business / Kitchen Name *</label>
                <input id="business_name" type="text" name="business_name" class="form-input" placeholder="e.g. Green Table Kitchen" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="registration_number">Business Registration Number (SSM) *</label>
                <input id="registration_number" type="text" name="registration_number" class="form-input" placeholder="e.g. 202401089201 (150821-X)" required>
            </div>

            <div class="portal-form-grid">
                <div class="form-group">
                    <label class="form-label" for="contact_person">Contact Person *</label>
                <input id="contact_person" type="text" name="contact_person" class="form-input" placeholder="e.g. Chef Michael Wong" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="business_type">Business Type</label>
                <select id="business_type" name="business_type" class="form-select">
                        <option value="Restaurant / Kitchen">Restaurant / Commercial Kitchen</option>
                        <option value="Café / Bakery">Café / Bakery</option>
                        <option value="Hotel & Resort">Hotel & Resort</option>
                        <option value="Caterer">Institutional Caterer</option>
                        <option value="Specialty Grocer">Specialty Grocer</option>
                    </select>
                </div>
            </div>

            <div class="portal-form-grid">
                <div class="form-group">
                    <label class="form-label" for="contact_email">Business Email *</label>
                <input id="contact_email" type="email" name="contact_email" autocomplete="email" class="form-input" placeholder="e.g. kitchen@greentable.com" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="contact_phone">Direct Phone / WhatsApp *</label>
                <input id="contact_phone" type="tel" name="contact_phone" autocomplete="tel" class="form-input" placeholder="+60 17 889 4412" required>
                </div>
            </div>

            <?php if (!is_logged_in()): ?>
                <div class="form-group">
                    <label class="form-label" for="password">Account Password *</label>
                <input id="password" type="password" name="password" minlength="8" autocomplete="new-password" class="form-input" placeholder="Create a secure password" required>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label class="form-label" for="document">Supporting Document (SSM Certificate / Business License)</label>
                <input id="document" type="file" name="document" class="form-input" accept=".pdf,.png,.jpg,.jpeg">
                <span style="font-size: 12px; color: var(--text-muted); margin-top: 4px; display: block;">PDF or image format (Max 5MB)</span>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top: 12px;">Submit application</button>
        </form>
    </div>
</div>
