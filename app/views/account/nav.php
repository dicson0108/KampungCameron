<!-- Account Navigation Tabs -->
<div style="display: flex; gap: 12px; overflow-x: auto; padding-bottom: 16px; margin-bottom: 32px; border-bottom: 1px solid var(--border-color);">
    <a href="<?= url('/account/dashboard') ?>" class="btn btn-<?= $activeAccountTab === 'dashboard' ? 'primary' : 'secondary' ?>" style="font-size: 13px; border-radius: var(--radius-pill); padding: 6px 18px;">Dashboard</a>
    <a href="<?= url('/account/orders') ?>" class="btn btn-<?= $activeAccountTab === 'orders' ? 'primary' : 'secondary' ?>" style="font-size: 13px; border-radius: var(--radius-pill); padding: 6px 18px;">Order history</a>
    <a href="<?= url('/account/addresses') ?>" class="btn btn-<?= $activeAccountTab === 'addresses' ? 'primary' : 'secondary' ?>" style="font-size: 13px; border-radius: var(--radius-pill); padding: 6px 18px;">Addresses</a>
    <a href="<?= url('/account/profile') ?>" class="btn btn-<?= $activeAccountTab === 'profile' ? 'primary' : 'secondary' ?>" style="font-size: 13px; border-radius: var(--radius-pill); padding: 6px 18px;">Profile & password</a>
    <a href="<?= url('/account/wishlist') ?>" class="btn btn-<?= $activeAccountTab === 'wishlist' ? 'primary' : 'secondary' ?>" style="font-size: 13px; border-radius: var(--radius-pill); padding: 6px 18px;">Wishlist<?php if ($activeAccountTab === 'dashboard'): ?> (<?= $wishlistCount ?>)<?php endif; ?></a>
    <a href="<?= url('/account/reviews') ?>" class="btn btn-<?= $activeAccountTab === 'reviews' ? 'primary' : 'secondary' ?>" style="font-size: 13px; border-radius: var(--radius-pill); padding: 6px 18px;">My reviews</a>
</div>
