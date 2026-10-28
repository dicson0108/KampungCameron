// Kampung Cameron Interactive Storefront Engine

document.addEventListener('DOMContentLoaded', () => {
    initSearch();
    initMiniCart();
    initMobileNav();
    initBackToTop();
    initAddToCart();
    initWishlist();
    initQuickOrder();
});

// Live Search with Autocomplete Dropdown
function initSearch() {
    const searchInput = document.getElementById('global-search');
    const dropdown = document.getElementById('search-dropdown');
    if (!searchInput || !dropdown) return;

    let debounceTimer;

    searchInput.addEventListener('input', (e) => {
        clearTimeout(debounceTimer);
        const query = e.target.value.trim();

        if (query.length < 1) {
            dropdown.classList.remove('active');
            dropdown.innerHTML = '';
            return;
        }

        debounceTimer = setTimeout(async () => {
            try {
                const res = await fetch(`${window.BASE_URL || ''}/api/search?q=${encodeURIComponent(query)}`);
                const data = await res.json();
                renderSearchSuggestions(data, query);
            } catch (err) {
                console.error('Search error:', err);
            }
        }, 200);
    });

    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.remove('active');
        }
    });

    function renderSearchSuggestions(data, query) {
        let html = '';

        if (data.products && data.products.length > 0) {
            html += `<div class="search-section-title">Fresh Produce</div>`;
            data.products.forEach(p => {
                html += `
                    <a href="${p.url}" class="search-item">
                        <img src="${p.image_url}" alt="${p.name}">
                        <div class="search-item-info">
                            <div class="search-item-name">${p.name}</div>
                            <div class="search-item-meta">${p.unit_weight} · ${p.formatted_price}</div>
                        </div>
                    </a>
                `;
            });
        }

        if (data.recipes && data.recipes.length > 0) {
            html += `<div class="search-section-title">Highland Recipes</div>`;
            data.recipes.forEach(r => {
                html += `
                    <a href="${r.url}" class="search-item">
                        <img src="${r.image_url}" alt="${r.title}">
                        <div class="search-item-info">
                            <div class="search-item-name">${r.title}</div>
                            <div class="search-item-meta">${r.prep_time_minutes + r.cooking_time_minutes} mins total</div>
                        </div>
                    </a>
                `;
            });
        }

        if (!html) {
            html = `<div style="padding: 16px; font-size: 13px; color: #64748b; text-align: center;">No matches found for "${query}"</div>`;
        }

        dropdown.innerHTML = html;
        dropdown.classList.add('active');
    }
}

// Mini-Cart Drawer
function initMiniCart() {
    const backdrop = document.getElementById('mini-cart-backdrop');
    const triggers = document.querySelectorAll('[data-action="open-mini-cart"]');
    const closeBtn = document.getElementById('mini-cart-close');

    if (!backdrop) return;

    window.openMiniCart = async function() {
        backdrop.classList.add('open');
        await refreshMiniCart();
    };

    window.closeMiniCart = function() {
        backdrop.classList.remove('open');
    };

    triggers.forEach(tr => tr.addEventListener('click', (e) => {
        e.preventDefault();
        openMiniCart();
    }));

    if (closeBtn) {
        closeBtn.addEventListener('click', closeMiniCart);
    }

    backdrop.addEventListener('click', (e) => {
        if (e.target === backdrop) {
            closeMiniCart();
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && backdrop.classList.contains('open')) {
            closeMiniCart();
        }
    });

}

async function refreshMiniCart() {
    const body = document.getElementById('mini-cart-items');
    const subtotalEl = document.getElementById('mini-cart-subtotal');
    const headerTitle = document.getElementById('mini-cart-title');

    if (!body) return;

    try {
        const res = await fetch(`${window.BASE_URL || ''}/api/mini-cart`);
        const data = await res.json();

        if (headerTitle) {
            headerTitle.textContent = `Your basket (${data.count} items)`;
        }
        if (subtotalEl) {
            subtotalEl.textContent = data.subtotal;
        }

        // Update header badges
        updateBagBadge(data.count, data.subtotal);

        if (!data.items || data.items.length === 0) {
            body.innerHTML = `
                <div style="text-align: center; padding: 40px 0; color: #64748b;">
                    <p style="font-size: 15px; margin-bottom: 16px;">Your basket is empty.</p>
                    <a href="${window.BASE_URL || ''}/shop" class="btn btn-primary" onclick="closeMiniCart()">Shop fresh produce</a>
                </div>
            `;
            return;
        }

        let html = '';
        data.items.forEach(it => {
            html += `
                <div class="mini-cart-item">
                    <img src="${it.image_url}" alt="${it.name}">
                    <div class="mini-cart-item-details">
                        <div class="mini-cart-item-title">${it.name}</div>
                        <div class="mini-cart-item-sub">${it.formatted_price} × ${it.quantity}</div>
                        <div style="font-weight: 700; margin-top: 4px;">${it.formatted_total}</div>
                    </div>
                </div>
            `;
        });
        body.innerHTML = html;

    } catch (err) {
        console.error('Error fetching mini-cart:', err);
    }
}

function updateBagBadge(count, subtotal) {
    const countEls = document.querySelectorAll('.bag-count');
    const totalEls = document.querySelectorAll('.bag-total');
    countEls.forEach(el => el.textContent = count);
    totalEls.forEach(el => el.textContent = subtotal);
}

// AJAX Add to Cart
function initAddToCart() {
    document.addEventListener('submit', async (e) => {
        const form = e.target.closest('form[data-ajax-cart]');
        if (!form) return;

        e.preventDefault();
        const submitBtn = form.querySelector('button[type="submit"]');
        const origText = submitBtn ? submitBtn.innerHTML : '';
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Adding...';
        }

        try {
            const formData = new FormData(form);
            formData.append('ajax', '1');

            const res = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();

            if (data.success) {
                updateBagBadge(data.cart_count, data.subtotal);
                window.openMiniCart();
            } else {
                alert(data.message || 'Could not add item to bag.');
            }
        } catch (err) {
            console.error('Add to cart failed:', err);
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = origText;
            }
        }
    });
}

// Wishlist Toggle
function initWishlist() {
    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('.wishlist-toggle-btn');
        if (!btn) return;

        e.preventDefault();
        const productId = btn.dataset.productId;
        if (!productId) return;

        try {
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('ajax', '1');

            const res = await fetch(`${window.BASE_URL || ''}/wishlist/toggle`, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();

            if (data.redirect) {
                window.location.href = data.redirect;
                return;
            }

            if (data.success) {
                btn.classList.toggle('active', data.added);
                const badge = document.querySelector('.wishlist-count');
                if (badge) badge.textContent = data.count;
            }
        } catch (err) {
            console.error('Wishlist error:', err);
        }
    });
}

// Wholesale Quick Order calculation
function initQuickOrder() {
    const form = document.getElementById('wholesale-quick-order-form');
    if (!form) return;

    const rows = form.querySelectorAll('.quick-order-row');
    const grandTotalEl = document.getElementById('quick-order-grand-total');

    function recalculate() {
        let total = 0;
        rows.forEach(row => {
            const price = parseFloat(row.dataset.price || '0');
            const input = row.querySelector('.quick-order-qty');
            const subEl = row.querySelector('.quick-order-line-total');
            const qty = parseInt(input.value || '0', 10);
            const lineTotal = price * qty;
            if (subEl) subEl.textContent = 'RM ' + lineTotal.toFixed(2);
            total += lineTotal;
        });
        if (grandTotalEl) grandTotalEl.textContent = 'RM ' + total.toFixed(2);
    }

    form.addEventListener('input', (e) => {
        if (e.target.classList.contains('quick-order-qty')) {
            recalculate();
        }
    });

    recalculate();
}

// Mobile Slide-over Navigation Sheet
function initMobileNav() {
    const toggleBtn = document.getElementById('mobile-menu-toggle');
    const backdrop = document.getElementById('mobile-nav-backdrop');
    const closeBtn = document.getElementById('mobile-nav-close');

    if (!backdrop || !toggleBtn) return;

    function openMobileNav() {
        backdrop.classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileNav() {
        backdrop.classList.remove('open');
        document.body.style.overflow = '';
    }

    toggleBtn.addEventListener('click', openMobileNav);

    if (closeBtn) {
        closeBtn.addEventListener('click', closeMobileNav);
    }

    backdrop.addEventListener('click', (e) => {
        if (e.target === backdrop) {
            closeMobileNav();
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && backdrop.classList.contains('open')) {
            closeMobileNav();
        }
    });
}

// Apple-style Floating Back-to-Top Button
function initBackToTop() {
    const floatBtn = document.getElementById('back-to-top');
    if (!floatBtn) return;

    function updateVisibility() {
        floatBtn.hidden = window.scrollY <= 0;
    }

    window.addEventListener('scroll', updateVisibility, { passive: true });
    updateVisibility();

    floatBtn.addEventListener('click', (e) => {
        e.preventDefault();
        window.scrollTo({
            top: 0,
            behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'instant' : 'smooth'
        });
    });
}
