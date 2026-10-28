<!-- Wholesale Subnavigation Bar -->
<div class="wholesale-subnav">
    <div class="container wholesale-subnav-container">
        <a href="<?= url('/wholesale/dashboard') ?>" class="wholesale-subnav-link<?= $activeWholesaleTab === 'dashboard' ? ' active' : '' ?>">Wholesale dashboard</a>
        <a href="<?= url('/wholesale/catalog') ?>" class="wholesale-subnav-link<?= $activeWholesaleTab === 'catalog' ? ' active' : '' ?>">Bulk catalogue</a>
        <a href="<?= url('/wholesale/quick-order') ?>" class="wholesale-subnav-link<?= $activeWholesaleTab === 'quick-order' ? ' active' : '' ?>">Quick order</a>
        <a href="<?= url('/wholesale/standing-orders') ?>" class="wholesale-subnav-link<?= $activeWholesaleTab === 'standing-orders' ? ' active' : '' ?>">Standing orders</a>
        <a href="<?= url('/wholesale/orders') ?>" class="wholesale-subnav-link<?= $activeWholesaleTab === 'orders' ? ' active' : '' ?>">Wholesale orders</a>
        <a href="<?= url('/wholesale/invoices') ?>" class="wholesale-subnav-link<?= $activeWholesaleTab === 'invoices' ? ' active' : '' ?>">Invoices</a>
    </div>
</div>
