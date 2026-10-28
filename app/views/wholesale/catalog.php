<?php $activeWholesaleTab = 'catalog'; require __DIR__ . '/subnav.php'; ?>

<div class="container" style="padding-bottom: 60px;">
    <div style="margin-bottom: 28px;">
        <span class="badge" style="background: #eef6f9; color: var(--service-blue-dark); font-size: 12px; padding: 4px 10px; margin-bottom: 8px;">
            <?= strtoupper(htmlspecialchars($wsAccount['tier_name'] ?? 'Tier A')) ?> (<?= number_format($wsAccount['discount_percent'] ?? 15, 0) ?>% TIER DISCOUNT)
        </span>
        <h1 style="font-size: 34px;">Bulk Produce Catalogue</h1>
        <p style="color: var(--text-muted); font-size: 14px;">Highland wholesale prices for scheduled hospitality kitchens.</p>
    </div>

    <div class="product-grid">
        <?php foreach ($products as $prod): ?>
            <div class="product-card">
                <div class="product-card-media">
                    <img src="<?= asset('images/' . ($prod['image'] ?: 'harvest.png')) ?>" alt="<?= htmlspecialchars($prod['name_en']) ?>">
                    <div class="product-badge-pos">
                        <?php if ($prod['stock_quantity'] <= 0): ?>
                            <span class="badge badge-sold-out">Sold out</span>
                        <?php else: ?>
                            <span class="badge badge-in-stock"><?= $prod['stock_quantity'] ?> in stock</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="product-card-body">
                    <h3 class="product-title"><?= htmlspecialchars($prod['name_en']) ?></h3>
                    <div class="product-specs"><?= htmlspecialchars($prod['unit_weight']) ?> · <?= htmlspecialchars($prod['category_name']) ?></div>

                    <div style="margin-top: auto; margin-bottom: 14px;">
                        <div style="display: flex; justify-content: space-between; font-size: 13px; color: var(--text-muted); margin-bottom: 2px;">
                            <span>Retail:</span>
                            <span style="text-decoration: line-through;"><?= format_currency($prod['retail_price']) ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 16px; font-weight: 700; color: var(--primary-green);">
                            <span>Wholesale Tier:</span>
                            <span><?= format_currency($prod['wholesale_price']) ?></span>
                        </div>
                    </div>

                    <?php if ($prod['stock_quantity'] > 0): ?>
                        <form action="<?= url('/cart/add') ?>" method="POST" data-ajax-cart>
                            <?= csrf_field() ?>
                            <input type="hidden" name="product_id" value="<?= $prod['id'] ?>">
                            <input type="hidden" name="quantity" value="5">
                            <button type="submit" class="btn btn-primary btn-block">+ Add 5 packs</button>
                        </form>
                    <?php else: ?>
                        <button type="button" class="btn btn-secondary btn-block" disabled>Sold out</button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
