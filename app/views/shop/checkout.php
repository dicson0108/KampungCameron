<div class="container" style="padding-top: 32px; padding-bottom: 60px;">
    <!-- Breadcrumb Steps -->
    <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 16px;">
        <span style="font-weight: 600; color: var(--text-main);">1 Delivery</span> → <span>2 Review & place order</span>
    </div>

    <h1 style="font-size: 34px; margin-bottom: 24px;">Checkout</h1>

    <?php if (!is_logged_in()): ?>
        <div style="background: #eef6f9; border: 1px solid #cce3ed; border-radius: var(--radius-sm); padding: 14px 20px; font-size: 14px; margin-bottom: 28px; color: var(--service-blue-dark);">
            Checking out as a guest? Continue below or <a href="<?= url('/account/login') ?>" style="font-weight: 700; text-decoration: underline;">log in</a> to use saved addresses.
        </div>
    <?php endif; ?>

    <form action="<?= url('/checkout/process') ?>" method="POST">
        <?= csrf_field() ?>

        <div class="checkout-layout">
            <!-- Left Column: Form Details -->
            <div>
                <section style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 32px; margin-bottom: 32px;">
                    <h2 style="font-size: 24px; margin-bottom: 8px;">Delivery details</h2>
                    <p style="font-size: 14px; color: var(--text-muted); margin-bottom: 24px;">One flat shipping fee for every order.</p>

                    <?php if (!empty($addresses)): ?>
                        <div class="form-group">
                            <label class="form-label"><?= __('saved_address', 'Saved address') ?></label>
                            <select id="saved-address-select" class="form-select">
                                <option value="">-- Choose a saved address --</option>
                                <?php foreach ($addresses as $addr): ?>
                                    <option value="<?= htmlspecialchars(json_encode($addr)) ?>">
                                        <?= htmlspecialchars($addr['label']) ?> · <?= htmlspecialchars($addr['recipient_name']) ?>, <?= htmlspecialchars($addr['city']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div class="form-group">
                            <label class="form-label"><?= __('full_name', 'Full name') ?> *</label>
                            <input type="text" name="full_name" id="field-name" class="form-input" value="<?= htmlspecialchars(auth_user()['name'] ?? '') ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><?= __('email_address', 'Email') ?> *</label>
                            <input type="email" name="email" id="field-email" class="form-input" value="<?= htmlspecialchars(auth_user()['email'] ?? '') ?>" required>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div class="form-group">
                            <label class="form-label"><?= __('phone_number', 'Phone') ?> *</label>
                            <input type="text" name="phone" id="field-phone" class="form-input" value="<?= htmlspecialchars(auth_user()['phone'] ?? '') ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><?= __('postcode', 'Postcode') ?> *</label>
                            <input type="text" name="postcode" id="field-postcode" class="form-input" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><?= __('address_line1', 'Address line 1') ?> *</label>
                        <input type="text" name="address_line1" id="field-addr1" class="form-input" placeholder="House/Unit, Street name" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><?= __('address_line2', 'Address line 2') ?></label>
                        <input type="text" name="address_line2" id="field-addr2" class="form-input" placeholder="Apartment, suite, building (optional)">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div class="form-group">
                            <label class="form-label"><?= __('city', 'City') ?> *</label>
                            <input type="text" name="city" id="field-city" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><?= __('state', 'State') ?> *</label>
                            <select name="state" id="field-state" class="form-select" required>
                                <option value="Selangor">Selangor</option>
                                <option value="Kuala Lumpur">Kuala Lumpur</option>
                                <option value="Johor">Johor</option>
                                <option value="Penang">Penang</option>
                                <option value="Perak">Perak</option>
                                <option value="Pahang">Pahang</option>
                                <option value="Melaka">Melaka</option>
                                <option value="Negeri Sembilan">Negeri Sembilan</option>
                                <option value="Kedah">Kedah</option>
                                <option value="Kelantan">Kelantan</option>
                                <option value="Terengganu">Terengganu</option>
                                <option value="Perlis">Perlis</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><?= __('delivery_notes', 'Delivery notes (optional)') ?></label>
                        <input type="text" name="delivery_notes" class="form-input" placeholder="e.g. Leave by door, gate code, call upon arrival">
                    </div>
                </section>

                <!-- Payment Method Section -->
                <section style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 32px;">
                    <h2 style="font-size: 24px; margin-bottom: 16px;"><?= __('payment_method', 'Payment method') ?></h2>

                    <?php $paymentMethods = get_checkout_payment_methods(); ?>
                    <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 24px;">
                        <?php foreach ($paymentMethods as $code => $label): ?>
                            <label style="display: flex; align-items: center; gap: 10px; font-size: 15px; cursor: pointer; padding: 10px; border: 1px solid var(--border-color); border-radius: var(--radius-sm);">
                                <input type="radio" name="payment_method" value="<?= e($code) ?>" <?= $code === array_key_first($paymentMethods) ? 'checked' : '' ?> required>
                                <span><?= e($label) ?></span>
                            </label>
                        <?php endforeach; ?>
                        <?php if (!$paymentMethods): ?><p role="status">Payment options are currently unavailable. Please contact the store before ordering.</p><?php endif; ?>
                    </div>

                    <!-- Payment note per Section 5.2 / Section 11 -->
                    <div style="background: #eef6f9; border: 1px solid #cce3ed; border-radius: var(--radius-sm); padding: 14px 18px; font-size: 13px; color: var(--service-blue-dark); margin-bottom: 20px;">
                        <?= __('payment_note', 'Payment choice is recorded with your order. No payment is processed on this website.') ?>
                    </div>

                    <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; cursor: pointer;">
                        <input type="checkbox" name="agree" required checked>
                        <span><?= __('agree_terms', 'I agree to the Terms & Conditions and Privacy Policy.') ?></span>
                    </label>
                </section>
            </div>

            <!-- Right Column: Order Summary Card -->
            <div>
                <div class="order-summary-card">
                    <h3 style="font-size: 20px; margin-bottom: 20px;">Order summary</h3>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px;">
                        <span><?= __('subtotal', 'Subtotal') ?></span>
                        <strong><?= format_currency($subtotal) ?></strong>
                    </div>

                    <?php if ($discount > 0): ?>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; color: var(--primary-green);">
                            <span>Promo <?= htmlspecialchars($promo['code'] ?? '') ?></span>
                            <strong>– <?= format_currency($discount) ?></strong>
                        </div>
                    <?php endif; ?>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 18px; font-size: 14px;">
                        <span><?= __('shipping', 'Flat shipping') ?></span>
                        <strong><?= format_currency($shipping) ?></strong>
                    </div>

                    <div style="border-top: 1px solid var(--border-color); padding-top: 16px; margin-bottom: 24px; display: flex; justify-content: space-between; font-size: 22px; font-weight: 700;">
                        <span><?= __('total', 'Total') ?></span>
                        <span style="color: var(--primary-green);"><?= format_currency($total) ?></span>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-bottom: 16px;">
                        <?= __('place_order', 'Place order') ?>
                    </button>

                    <div style="font-size: 12px; color: var(--text-muted); text-align: center;">
                        Secure details · Freshly packed
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const addrSelect = document.getElementById('saved-address-select');
    if (addrSelect) {
        addrSelect.addEventListener('change', (e) => {
            if (!e.target.value) return;
            try {
                const data = JSON.parse(e.target.value);
                document.getElementById('field-name').value = data.recipient_name || '';
                document.getElementById('field-phone').value = data.phone || '';
                document.getElementById('field-addr1').value = data.address_line1 || '';
                document.getElementById('field-addr2').value = data.address_line2 || '';
                document.getElementById('field-city').value = data.city || '';
                document.getElementById('field-state').value = data.state || 'Selangor';
                document.getElementById('field-postcode').value = data.postcode || '';
            } catch(err) {
                console.error(err);
            }
        });
    }
});
</script>
