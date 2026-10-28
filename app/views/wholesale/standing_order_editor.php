<!-- Wholesale Subnavigation Bar -->
<div style="background: var(--service-blue); color: #ffffff; margin-bottom: 32px;">
    <div class="container" style="display: flex; gap: 24px; overflow-x: auto; padding-top: 10px; padding-bottom: 10px; font-size: 14px; font-weight: 500;">
        <a href="<?= url('/wholesale/dashboard') ?>" style="color: #e2e8f0;">Wholesale dashboard</a>
        <a href="<?= url('/wholesale/catalog') ?>" style="color: #e2e8f0;">Bulk catalogue</a>
        <a href="<?= url('/wholesale/quick-order') ?>" style="color: #e2e8f0;">Quick order</a>
        <a href="<?= url('/wholesale/standing-orders') ?>" style="color: #ffffff; font-weight: 700; border-bottom: 2px solid #ffffff; padding-bottom: 4px;">Standing orders</a>
        <a href="<?= url('/wholesale/orders') ?>" style="color: #e2e8f0;">Wholesale orders</a>
        <a href="<?= url('/wholesale/invoices') ?>" style="color: #e2e8f0;">Invoices</a>
    </div>
</div>

<div class="container" style="padding-bottom: 60px;">
    <!-- Top Tag -->
    <div style="margin-bottom: 24px;">
        <span class="badge" style="background: #eef6f9; color: var(--service-blue-dark); font-size: 12px; padding: 4px 10px; margin-bottom: 8px;">
            APPROVED ACCOUNT · <?= strtoupper(htmlspecialchars($wsAccount['tier_name'] ?? 'Tier A')) ?>
        </span>
        <h1 style="font-size: 34px; margin-bottom: 6px;">Standing Order Editor</h1>
        <p style="color: var(--text-muted); font-size: 14px;">Consistent supply. Transparent pricing. A kitchen that keeps moving.</p>
    </div>

    <form action="<?= url('/wholesale/standing-orders/save') ?>" method="POST" id="standing-order-form">
        <?= csrf_field() ?>
        <input type="hidden" name="standing_order_id" value="<?= htmlspecialchars($order['id'] ?? 0) ?>">

        <div style="background: var(--surface-white); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 32px; margin-bottom: 32px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 20px;">
                <div class="form-group">
                    <label class="form-label">Schedule name</label>
                    <input type="text" name="schedule_name" class="form-input" value="<?= htmlspecialchars($order['name'] ?? 'Monday essentials') ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Weekly delivery day</label>
                    <select name="weekly_delivery_day" class="form-select">
                        <?php foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $d): ?>
                            <option value="<?= $d ?>" <?= ($order['delivery_day'] ?? 'Monday') === $d ? 'selected' : '' ?>><?= $d ?> ▾</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 32px;">
                <div class="form-group">
                    <label class="form-label">Start date</label>
                    <input type="date" name="start_date" class="form-input" value="<?= htmlspecialchars($order['start_date'] ?? date('Y-m-d')) ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Delivery address</label>
                    <input type="text" name="delivery_address" class="form-input" value="<?= htmlspecialchars($order['delivery_address'] ?? ($addresses[0]['address_line1'] ?? 'Green Table Kitchen, 88 Jalan Telawi 2, Bangsar, 59100 KL')) ?>" required>
                </div>
            </div>

            <!-- Items Table -->
            <div style="border: 1px solid var(--border-color); border-radius: var(--radius-sm); overflow: hidden; margin-bottom: 20px;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;" id="standing-items-table">
                    <thead>
                        <tr style="background: var(--warm-paper); border-bottom: 1px solid var(--border-color);">
                            <th style="padding: 12px 18px;">Product</th>
                            <th style="padding: 12px 18px;">Pack</th>
                            <th style="padding: 12px 18px;">Quantity</th>
                            <th style="padding: 12px 18px; text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="standing-items-tbody">
                        <?php if (!empty($orderItems)): ?>
                            <?php foreach ($orderItems as $it): ?>
                                <tr style="border-bottom: 1px solid var(--border-color);" class="standing-item-row" data-price="<?= $it['price'] ?>">
                                    <td style="padding: 14px 18px; font-weight: 600;">
                                        <?= htmlspecialchars($it['name_en']) ?>
                                        <input type="hidden" name="products[<?= $it['product_id'] ?>]" value="<?= $it['quantity'] ?>" class="item-qty-hidden">
                                    </td>
                                    <td style="padding: 14px 18px; color: var(--text-muted);"><?= htmlspecialchars($it['unit_weight']) ?></td>
                                    <td style="padding: 14px 18px;">
                                        <input type="number" value="<?= $it['quantity'] ?>" min="1" class="form-input item-qty-input" style="width: 80px; padding: 6px;" onchange="updateQty(this, <?= $it['product_id'] ?>)">
                                    </td>
                                    <td style="padding: 14px 18px; text-align: right;">
                                        <button type="button" onclick="this.closest('tr').remove(); recalculateStanding();" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 13px;">Remove</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <!-- Default mock seed rows -->
                            <tr style="border-bottom: 1px solid var(--border-color);" class="standing-item-row" data-price="6.50">
                                <td style="padding: 14px 18px; font-weight: 600;">Broccoli <input type="hidden" name="products[1]" value="10" class="item-qty-hidden"></td>
                                <td style="padding: 14px 18px; color: var(--text-muted);">300g</td>
                                <td style="padding: 14px 18px;"><input type="number" value="10" min="1" class="form-input item-qty-input" style="width: 80px; padding: 6px;" onchange="updateQty(this, 1)"></td>
                                <td style="padding: 14px 18px; text-align: right;"><button type="button" onclick="this.closest('tr').remove(); recalculateStanding();" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 13px;">Remove</button></td>
                            </tr>
                            <tr style="border-bottom: 1px solid var(--border-color);" class="standing-item-row" data-price="5.90">
                                <td style="padding: 14px 18px; font-weight: 600;">Vine Tomatoes <input type="hidden" name="products[2]" value="20" class="item-qty-hidden"></td>
                                <td style="padding: 14px 18px; color: var(--text-muted);">500g</td>
                                <td style="padding: 14px 18px;"><input type="number" value="20" min="1" class="form-input item-qty-input" style="width: 80px; padding: 6px;" onchange="updateQty(this, 2)"></td>
                                <td style="padding: 14px 18px; text-align: right;"><button type="button" onclick="this.closest('tr').remove(); recalculateStanding();" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 13px;">Remove</button></td>
                            </tr>
                            <tr style="border-bottom: 1px solid var(--border-color);" class="standing-item-row" data-price="4.20">
                                <td style="padding: 14px 18px; font-weight: 600;">Sweet Carrots <input type="hidden" name="products[3]" value="10" class="item-qty-hidden"></td>
                                <td style="padding: 14px 18px; color: var(--text-muted);">500g</td>
                                <td style="padding: 14px 18px;"><input type="number" value="10" min="1" class="form-input item-qty-input" style="width: 80px; padding: 6px;" onchange="updateQty(this, 3)"></td>
                                <td style="padding: 14px 18px; text-align: right;"><button type="button" onclick="this.closest('tr').remove(); recalculateStanding();" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 13px;">Remove</button></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Add Product Selector -->
            <div style="display: flex; gap: 12px; margin-bottom: 24px; align-items: center;">
                <select id="add-product-select" class="form-select" style="max-width: 260px;">
                    <?php foreach ($allProducts as $ap): ?>
                        <option value="<?= $ap['id'] ?>" data-name="<?= htmlspecialchars($ap['name_en']) ?>" data-pack="<?= htmlspecialchars($ap['unit_weight']) ?>" data-price="<?= $ap['price'] ?>">
                            <?= htmlspecialchars($ap['name_en']) ?> (<?= htmlspecialchars($ap['unit_weight']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="button" id="btn-add-product" class="btn btn-secondary">+ Add product</button>
            </div>

            <!-- Order Notes -->
            <div class="form-group" style="margin-bottom: 24px;">
                <label class="form-label">Order notes</label>
                <input type="text" name="order_notes" class="form-input" value="<?= htmlspecialchars($order['notes'] ?? 'Call the kitchen before delivery') ?>" placeholder="Special instructions for kitchen dispatch">
            </div>

            <!-- Estimated Calculation Strip -->
            <div style="background: #e8f5e9; border: 1px solid #c8e6c9; border-radius: var(--radius-sm); padding: 16px 20px; font-size: 14px; color: #1b5e20; margin-bottom: 28px;" id="standing-calc-strip">
                Estimated produce RM 180.00 + flat shipping RM 8.00 = RM 188.00. Final prices and stock are checked when generated.
            </div>

            <!-- Action Buttons Row -->
            <div style="display: flex; gap: 14px; flex-wrap: wrap;">
                <button type="submit" class="btn btn-secondary">Save schedule</button>
                <button type="button" onclick="updateStatus('paused')" class="btn btn-secondary">Pause schedule</button>
                <button type="button" onclick="updateStatus('cancelled')" class="btn btn-primary" style="background-color: var(--primary-green);">Cancel standing order</button>
            </div>
        </div>
    </form>

    <div style="margin-top: 20px;">
        <a href="<?= url('/wholesale/standing-orders') ?>" class="btn btn-secondary">Return to standing orders</a>
    </div>
</div>

<form id="status-form" action="<?= url('/wholesale/standing-orders/status') ?>" method="POST" style="display: none;">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= htmlspecialchars($order['id'] ?? 0) ?>">
    <input type="hidden" name="status" id="status-input" value="active">
</form>

<script>
function updateQty(input, pid) {
    const row = input.closest('tr');
    const hidden = row.querySelector('.item-qty-hidden');
    if (hidden) hidden.value = input.value;
    recalculateStanding();
}

function updateStatus(status) {
    if (confirm('Are you sure you want to update this standing order status to ' + status + '?')) {
        document.getElementById('status-input').value = status;
        document.getElementById('status-form').submit();
    }
}

function recalculateStanding() {
    let subtotal = 0;
    const rows = document.querySelectorAll('.standing-item-row');
    rows.forEach(r => {
        const price = parseFloat(r.dataset.price || '0');
        const qty = parseInt(r.querySelector('.item-qty-input').value || '0', 10);
        subtotal += (price * qty);
    });
    const total = subtotal + 8.00;
    const strip = document.getElementById('standing-calc-strip');
    if (strip) {
        strip.innerHTML = `Estimated produce RM ${subtotal.toFixed(2)} + flat shipping RM 8.00 = RM ${total.toFixed(2)}. Final prices and stock are checked when generated.`;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const addBtn = document.getElementById('btn-add-product');
    if (addBtn) {
        addBtn.addEventListener('click', () => {
            const sel = document.getElementById('add-product-select');
            const opt = sel.options[sel.selectedIndex];
            const pid = opt.value;
            const name = opt.dataset.name;
            const pack = opt.dataset.pack;
            const price = opt.dataset.price;

            const tbody = document.getElementById('standing-items-tbody');
            const tr = document.createElement('tr');
            tr.className = 'standing-item-row';
            tr.dataset.price = price;
            tr.style.borderBottom = '1px solid var(--border-color)';
            tr.innerHTML = `
                <td style="padding: 14px 18px; font-weight: 600;">
                    ${name}
                    <input type="hidden" name="products[${pid}]" value="10" class="item-qty-hidden">
                </td>
                <td style="padding: 14px 18px; color: var(--text-muted);">${pack}</td>
                <td style="padding: 14px 18px;">
                    <input type="number" value="10" min="1" class="form-input item-qty-input" style="width: 80px; padding: 6px;" onchange="updateQty(this, ${pid})">
                </td>
                <td style="padding: 14px 18px; text-align: right;">
                    <button type="button" onclick="this.closest('tr').remove(); recalculateStanding();" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 13px;">Remove</button>
                </td>
            `;
            tbody.appendChild(tr);
            recalculateStanding();
        });
    }
    recalculateStanding();
});
</script>
