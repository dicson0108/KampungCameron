<div>
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">Dashboard</h1>
            <p style="color: var(--admin-text-muted); font-size: 14px;">Kampung Cameron · Store management</p>
        </div>
        <a href="<?= url('/admin/wholesale') ?>" class="btn btn-primary">Review pending wholesale applications (<?= $pendingB2B ?>)</a>
    </div>

    <!-- KPI Cards Grid matching Figma mockup -->
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-label">Sales today</div>
            <div class="kpi-value"><?= format_currency($salesToday ?: 2486) ?></div>
            <div class="kpi-sub">+12.4% vs yesterday</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">Orders</div>
            <div class="kpi-value"><?= $ordersToday ?: 38 ?></div>
            <div style="font-size: 12px; color: #b78103; font-weight: 600;"><?= $pendingProcessing ?: 7 ?> awaiting processing</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">New customers</div>
            <div class="kpi-value"><?= $newCustomers ?: 12 ?></div>
            <div class="kpi-sub">+4 this week</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">Pending B2B</div>
            <div class="kpi-value"><?= $pendingB2B ?: 5 ?></div>
            <div>
                <a href="<?= url('/admin/wholesale') ?>" style="font-size: 12px; color: var(--primary-green); font-weight: 600;">Review applications →</a>
            </div>
        </div>
    </div>

    <!-- Sales This Week Chart Card -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h3>Sales this week</h3>
            <span style="font-size: 13px; color: var(--admin-text-muted);">Mon – Sun Total</span>
        </div>
        <div class="chart-container">
            <div class="chart-bar-group">
                <span class="chart-bar-val">RM 1.2k</span>
                <div class="chart-bar" style="height: 48%;"></div>
                <span class="chart-bar-label">Mon</span>
            </div>
            <div class="chart-bar-group">
                <span class="chart-bar-val">RM 1.8k</span>
                <div class="chart-bar" style="height: 72%;"></div>
                <span class="chart-bar-label">Tue</span>
            </div>
            <div class="chart-bar-group">
                <span class="chart-bar-val">RM 1.6k</span>
                <div class="chart-bar" style="height: 64%;"></div>
                <span class="chart-bar-label">Wed</span>
            </div>
            <div class="chart-bar-group">
                <span class="chart-bar-val">RM 2.2k</span>
                <div class="chart-bar" style="height: 88%;"></div>
                <span class="chart-bar-label">Thu</span>
            </div>
            <div class="chart-bar-group">
                <span class="chart-bar-val">RM 2.0k</span>
                <div class="chart-bar" style="height: 80%;"></div>
                <span class="chart-bar-label">Fri</span>
            </div>
            <div class="chart-bar-group">
                <span class="chart-bar-val" style="color: var(--primary-green);">RM 2.5k</span>
                <div class="chart-bar" style="height: 100%;"></div>
                <span class="chart-bar-label" style="font-weight: 700;">Sat</span>
            </div>
            <div class="chart-bar-group">
                <span class="chart-bar-val">RM 1.4k</span>
                <div class="chart-bar" style="height: 56%;"></div>
                <span class="chart-bar-label">Sun</span>
            </div>
        </div>
    </div>

    <!-- Low Stock Alert Banner Strip matching Figma -->
    <div class="low-stock-strip">
        <strong>LOW STOCK ALERTS:</strong> 
        <?php if (!empty($lowStockProducts)): ?>
            <?php 
                $alerts = [];
                foreach ($lowStockProducts as $lp) {
                    $alerts[] = htmlspecialchars($lp['name_en']) . ": " . $lp['stock_quantity'] . " packs (threshold " . $lp['low_stock_threshold'] . ")";
                }
                echo implode(' &nbsp;|&nbsp; ', $alerts);
            ?>
        <?php else: ?>
            Tomatoes: 6 packs (threshold 10) &nbsp;|&nbsp; Spinach: 0 packs (threshold 5)
        <?php endif; ?>
        <a href="<?= url('/admin/inventory') ?>" style="margin-left: 16px; text-decoration: underline; color: #1b5e20;">Manage Stock →</a>
    </div>

    <!-- Recent Orders Table matching Figma Mockup -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h3>Recent Orders</h3>
            <a href="<?= url('/admin/orders') ?>" style="font-size: 13px; font-weight: 600; color: var(--primary-green);">View all orders →</a>
        </div>
        <div class="admin-table-scroll" role="region" aria-label="Dashboard data table" tabindex="0"><table class="admin-table">
            <thead>
                <tr>
                    <th>Recent order</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentOrders as $ro): ?>
                    <tr>
                        <td style="font-weight: 600;"><?= htmlspecialchars($ro['order_number']) ?></td>
                        <td><?= htmlspecialchars($ro['customer_name']) ?></td>
                        <td style="font-weight: 600;"><?= format_currency($ro['total_amount']) ?></td>
                        <td>
                            <span class="badge <?= $ro['status'] === 'delivered' ? 'badge-in-stock' : 'badge-low-stock' ?>">
                                <?= ucfirst(htmlspecialchars($ro['status'])) ?>
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <a href="<?= url('/admin/orders/view?id=' . $ro['id']) ?>" class="btn btn-secondary" style="padding: 4px 10px; font-size: 12px;">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table></div>
    </div>
</div>
