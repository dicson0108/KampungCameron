<!DOCTYPE html>
<html lang="<?= current_lang() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Kampung Cameron') ?></title>
    <link rel="icon" type="image/x-icon" href="<?= url('/favicon.ico') ?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?= url('/favicon.ico') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= asset('images/favicon-32x32.png') ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= asset('images/apple-touch-icon.png') ?>">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <script>window.BASE_URL = '<?= BASE_URL ?>';</script>
</head>
<body>
    <!-- Topbar -->
    <aside class="topbar" aria-label="Announcement & language">
        <div class="container topbar-content">
            <div><?= __('topbar_tagline', 'Fresh from Cameron Highlands. Delivered to your door.') ?></div>
            <div class="lang-switch">
                <a href="?lang=en" class="<?= current_lang() === 'en' ? 'active' : '' ?>">EN</a>
                <span>/</span>
                <a href="?lang=zh" class="<?= current_lang() === 'zh' ? 'active' : '' ?>">中文</a>
            </div>
        </div>
    </aside>

    <!-- Global Main Header -->
    <header class="main-header">
        <div class="container">
            <div class="header-row">
                <a href="<?= url('/') ?>" class="site-logo" aria-label="Kampung Cameron Home">
                    <img src="<?= asset('images/logo-tight.png') ?>" alt="Kampung Cameron" class="site-logo-img">
                </a>

                <!-- Search Bar -->
                <div class="search-container">
                    <div class="search-input-wrapper">
                        <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input type="text" id="global-search" class="search-input" placeholder="<?= __('search_placeholder', 'Search fresh produce & recipes') ?>" autocomplete="off">
                    </div>
                    <div id="search-dropdown" class="search-dropdown"></div>
                </div>

                <!-- Utilities -->
                <div class="header-actions">
                    <a href="<?= url('/account/wishlist') ?>" class="wishlist-link">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                        <span class="wishlist-count"><?= get_wishlist_count() ?></span>
                    </a>

                    <a href="<?= is_logged_in() ? url('/account/dashboard') : url('/account/login') ?>" class="account-link">
                        <?= is_logged_in() ? htmlspecialchars(explode(' ', auth_user()['name'])[0]) : __('my_account', 'My account') ?>
                    </a>

                    <a href="<?= url('/cart') ?>" class="bag-link" data-action="open-mini-cart" aria-label="Shopping bag">
                        <?= __('bag', 'Bag') ?> <span class="bag-count"><?= get_cart_count() ?></span>
                        <span class="bag-total"><?= format_currency(get_cart_subtotal()) ?></span>
                    </a>

                    <!-- Mobile Navigation Toggle -->
                    <button type="button" class="mobile-menu-btn" id="mobile-menu-toggle" aria-label="Toggle navigation menu">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Main Nav -->
            <nav class="nav-bar">
                <a href="<?= url('/') ?>" class="nav-link <?= ($_SERVER['REQUEST_URI'] ?? '') === url('/') ? 'active' : '' ?>"><?= __('nav_home', 'Home') ?></a>
                <a href="<?= url('/wholesale') ?>" class="nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/wholesale') ? 'active' : '' ?>"><?= __('nav_wholesale', 'Wholesale') ?></a>
                <a href="<?= url('/shop') ?>" class="nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/shop') ? 'active' : '' ?>"><?= __('nav_shop', 'Shop') ?></a>
                <a href="<?= url('/recipes') ?>" class="nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/recipes') ? 'active' : '' ?>"><?= __('nav_recipes', 'Recipes') ?></a>
                <a href="<?= url('/about') ?>" class="nav-link"><?= __('nav_about', 'About') ?></a>
                <a href="<?= url('/contact') ?>" class="nav-link"><?= __('nav_contact', 'Contact') ?></a>
            </nav>
        </div>
    </header>

    <!-- Flash Notifications -->
    <div class="container flash-container">
        <?php foreach (get_flashes() as $type => $messages): ?>
            <?php foreach ($messages as $msg): ?>
                <div class="alert alert-<?= $type === 'error' ? 'error' : 'success' ?>">
                    <span><?= htmlspecialchars($msg) ?></span>
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>

    <!-- Main Page Content -->
    <main>
        <?= $content ?>
    </main>

    <!-- Global Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h4><?= __('footer_slogan_title', 'Kampung Cameron') ?></h4>
                    <p><?= __('footer_slogan_desc', 'Good food starts with fresh roots. Fresh produce for your home and kitchen.') ?></p>
                </div>
                <div class="footer-col">
                    <h4><?= __('footer_explore', 'Explore') ?></h4>
                    <ul class="footer-links">
                        <li><a href="<?= url('/shop') ?>">Shop produce</a></li>
                        <li><a href="<?= url('/wholesale') ?>">Wholesale services</a></li>
                        <li><a href="<?= url('/recipes') ?>">Recipes</a></li>
                        <li><a href="<?= url('/about') ?>">About us · Contact</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4><?= __('footer_customer_care', 'Customer care') ?></h4>
                    <ul class="footer-links">
                        <li><a href="<?= url('/terms') ?>"><?= __('footer_terms', 'Terms & Conditions') ?></a></li>
                        <li><a href="<?= url('/privacy') ?>"><?= __('footer_privacy', 'Privacy Policy') ?></a></li>
                        <li><a href="<?= url('/shipping') ?>"><?= __('footer_shipping', 'Shipping & Delivery') ?></a></li>
                        <li><a href="<?= url('/return-policy') ?>"><?= __('footer_return', 'Return Policy') ?></a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4><?= __('footer_stay_connected', 'Stay connected') ?></h4>
                    <p style="margin-bottom: 8px;">WhatsApp: +60 12 345 6789</p>
                    <p style="margin-bottom: 8px;">Email: hello@kampungcameron.com</p>
                    <div class="footer-social-links" aria-label="Social media links">
                        <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="social-icon-btn" aria-label="Facebook">
                            <img src="<?= asset('images/social/facebook.png') ?>" alt="Facebook" class="social-icon-img">
                        </a>
                        <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="social-icon-btn" aria-label="Instagram">
                            <img src="<?= asset('images/social/instagram.png') ?>" alt="Instagram" class="social-icon-img">
                        </a>
                        <a href="https://tiktok.com" target="_blank" rel="noopener noreferrer" class="social-icon-btn" aria-label="TikTok">
                            <img src="<?= asset('images/social/tiktok.png') ?>" alt="TikTok" class="social-icon-img">
                        </a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <div class="copyright"><?= __('footer_copyright', '© 2026 Kampung Cameron · Fresh from the highlands') ?></div>
                <div class="payment-badges" aria-label="Accepted payment methods">
                    <img src="<?= asset('images/payments/fpx.png') ?>" alt="FPX Online Banking" class="payment-logo" style="height: 18px;">
                    <img src="<?= asset('images/payments/visa.png') ?>" alt="Visa" class="payment-logo" style="height: 17px;">
                    <img src="<?= asset('images/payments/mastercard.svg') ?>" alt="Mastercard" class="payment-logo" style="height: 20px;">
                    <img src="<?= asset('images/payments/grabpay.png') ?>" alt="GrabPay" class="payment-logo" style="height: 20px;">
                    <img src="<?= asset('images/payments/tng.svg') ?>" alt="Touch 'n Go eWallet" class="payment-logo" style="height: 24px;">
                </div>
            </div>
        </div>
    </footer>

    <!-- Mini Cart Slide-over Drawer -->
    <div id="mini-cart-backdrop" class="mini-cart-backdrop">
        <div class="mini-cart-drawer">
            <div class="mini-cart-header">
                <h3 id="mini-cart-title"><?= sprintf(__('your_basket', 'Your basket (%d items)'), get_cart_count()) ?></h3>
                <button type="button" id="mini-cart-close" class="mini-cart-close" aria-label="Close basket">&times;</button>
            </div>
            <div id="mini-cart-items" class="mini-cart-body"></div>
            <div class="mini-cart-footer">
                <div class="mini-cart-subtotal-row">
                    <span><?= __('subtotal', 'Subtotal') ?></span>
                    <span id="mini-cart-subtotal"><?= format_currency(get_cart_subtotal()) ?></span>
                </div>
                <a href="<?= url('/cart') ?>" class="btn btn-secondary btn-block" style="margin-bottom: 8px;"><?= __('btn_view_cart', 'View cart') ?></a>
                <a href="<?= url('/checkout') ?>" class="btn btn-primary btn-block"><?= __('btn_checkout', 'Checkout') ?></a>
            </div>
        </div>
    </div>

    <!-- Mobile Slide-over Navigation Sheet -->
    <div id="mobile-nav-backdrop" class="mobile-nav-backdrop">
        <div class="mobile-nav-drawer">
            <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 16px; border-bottom: 1px solid var(--border-color);">
                <a href="<?= url('/') ?>" class="site-logo" aria-label="Kampung Cameron Home">
                    <img src="<?= asset('images/logo-tight.png') ?>" alt="Kampung Cameron" class="site-logo-img" style="height: 40px;">
                </a>
                <button type="button" id="mobile-nav-close" class="mini-cart-close" aria-label="Close menu">&times;</button>
            </div>
            <div class="mobile-nav-links">
                <a href="<?= url('/') ?>" class="mobile-nav-item <?= ($_SERVER['REQUEST_URI'] ?? '') === url('/') ? 'active' : '' ?>"><?= __('nav_home', 'Home') ?></a>
                <a href="<?= url('/shop') ?>" class="mobile-nav-item <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/shop') ? 'active' : '' ?>"><?= __('nav_shop', 'Shop Produce') ?></a>
                <a href="<?= url('/wholesale') ?>" class="mobile-nav-item <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/wholesale') ? 'active' : '' ?>"><?= __('nav_wholesale', 'Wholesale B2B') ?></a>
                <a href="<?= url('/recipes') ?>" class="mobile-nav-item <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/recipes') ? 'active' : '' ?>"><?= __('nav_recipes', 'Recipes') ?></a>
                <a href="<?= url('/about') ?>" class="mobile-nav-item"><?= __('nav_about', 'About Us') ?></a>
                <a href="<?= url('/contact') ?>" class="mobile-nav-item"><?= __('nav_contact', 'Contact') ?></a>
                <a href="<?= url('/account/dashboard') ?>" class="mobile-nav-item" style="margin-top: 12px; border-top: 1px solid var(--border-color); padding-top: 16px;"><?= __('my_account', 'My Account') ?></a>
            </div>
            <div style="margin-top: auto; padding-top: 20px; border-top: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; font-size: 13px;">
                <span>Language:</span>
                <div class="lang-switch" style="color: var(--text-main);">
                    <a href="?lang=en" class="<?= current_lang() === 'en' ? 'active' : '' ?>" style="color: var(--primary-green); font-weight: 700;">EN</a>
                    <span>/</span>
                    <a href="?lang=zh" class="<?= current_lang() === 'zh' ? 'active' : '' ?>" style="color: var(--primary-green); font-weight: 700;">中文</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Apple-style Floating Back-to-Top Button -->
    <button type="button" id="back-to-top" class="back-to-top-btn" aria-label="Back to top" title="Back to top" hidden>
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="18 15 12 9 6 15"></polyline>
        </svg>
    </button>

    <script src="<?= asset('js/main.js') ?>"></script>
</body>
</html>
