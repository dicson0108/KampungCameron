// Run in an admin page's browser console, at desktop and mobile widths.
(() => {
    const sidebar = document.querySelector('.admin-sidebar');
    const main = document.querySelector('.admin-main');
    if (!sidebar || !main) throw new Error('Open an admin page before checking layout.');
    const sideRect = sidebar.getBoundingClientRect();
    const mainRect = main.getBoundingClientRect();
    if (matchMedia('(max-width: 768px)').matches) {
        if (mainRect.top < sideRect.bottom - 1) throw new Error('Mobile content overlaps navigation.');
    } else {
        if (Math.abs(mainRect.top - sideRect.top) > 1 || mainRect.left < sideRect.right - 1) {
            throw new Error('Desktop sidebar and content must sit side by side at the same top edge.');
        }
    }
    if (document.documentElement.scrollWidth > document.documentElement.clientWidth + 1) {
        throw new Error('Admin page overflows viewport horizontally.');
    }
    for (const field of document.querySelectorAll('.admin-content input:not([type=hidden]), .admin-content select, .admin-content textarea')) {
        if (field.closest('.admin-table-scroll')) continue;
        const rect = field.getBoundingClientRect();
        if (rect.width > 0 && (rect.right > mainRect.right + 1 || rect.left < mainRect.left - 1)) {
            throw new Error('Admin form field is clipped: ' + field.name);
        }
    }
    return 'Admin layout passed';
})();
