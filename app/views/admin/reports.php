<div class="admin-page-header">
    <div>
        <h1><?= e($title) ?></h1>
        <p class="admin-page-subtitle">Aggregate revenue metrics, channel breakdown, and exportable ledgers.</p>
    </div>
    <div style="display:flex; gap:0.5rem;">
        <a href="<?= url('/admin/reports/export?type=sales') ?>" class="btn btn-secondary btn-sm" download>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-2px; margin-right:4px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Export Sales (CSV)
        </a>
        <a href="<?= url('/admin/reports/export?type=stock') ?>" class="btn btn-primary btn-sm" download>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-2px; margin-right:4px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Export Inventory (CSV)
        </a>
    </div>
</div>

<!-- Revenue Split Cards -->
<div class="kpi-grid" style="margin-bottom: 2rem;">
    <div class="kpi-card">
        <span class="kpi-label">Gross Revenue (Excl. Cancelled)</span>
        <div class="kpi-value" style="color:var(--primary-green);">RM <?= number_format($totalSales, 2) ?></div>
        <span class="kpi-sub">Total lifetime transactions</span>
    </div>
    <div class="kpi-card">
        <span class="kpi-label">Retail Direct Revenue</span>
        <div class="kpi-value">RM <?= number_format($totalRetail, 2) ?></div>
        <span class="kpi-sub">
            <?= $totalSales > 0 ? number_format(($totalRetail / $totalSales) * 100, 1) : 0 ?>% of total revenue
        </span>
    </div>
    <div class="kpi-card">
        <span class="kpi-label">Wholesale B2B Revenue</span>
        <div class="kpi-value" style="color:var(--service-blue);">RM <?= number_format($totalWholesale, 2) ?></div>
        <span class="kpi-sub">
            <?= $totalSales > 0 ? number_format(($totalWholesale / $totalSales) * 100, 1) : 0 ?>% of total revenue
        </span>
    </div>
</div>

<!-- Best Sellers Table -->
<div class="admin-card">
    <div class="admin-card-header">
        <h3 style="margin:0;">Top 10 Best-Selling Products</h3>
        <span style="font-size:0.8rem; color:var(--admin-text-muted);">By units fulfilled</span>
    </div>
    <div class="admin-table-wrap">
        <div class="admin-table-scroll" role="region" aria-label="Reports data table" tabindex="0"><table class="admin-table">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Product</th>
                    <th style="text-align:right;">Units Sold</th>
                    <th style="text-align:right;">Total Revenue Generated</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($bestSellers)): ?>
                    <tr><td colspan="4" style="text-align:center; padding:2rem; color:var(--admin-text-muted);">No sales data available yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($bestSellers as $idx => $item): ?>
                        <tr>
                            <td><strong>#<?= $idx + 1 ?></strong></td>
                            <td><strong><?= e($item['name_en']) ?></strong></td>
                            <td style="text-align:right; font-weight:600;"><?= number_format($item['total_qty']) ?></td>
                            <td style="text-align:right; font-weight:700; color:var(--primary-green);">RM <?= number_format($item['total_revenue'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table></div>
    </div>
</div>
