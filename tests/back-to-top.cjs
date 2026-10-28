// Run: node tests/back-to-top.cjs
const assert = require('node:assert/strict');
const { readFileSync } = require('node:fs');
const vm = require('node:vm');
const source = readFileSync(require('node:path').join(__dirname, '../public/assets/js/main.js'), 'utf8');

for (const reducedMotion of [false, true]) {
  for (const initialScroll of [0, 500]) {
    const listeners = new Map();
    const classes = new Set();
    function element(id) {
        return {
            addEventListener: (type, handler) => listeners.set(`${id}:${type}`, handler),
            classList: { add: name => classes.add(name), remove: name => classes.delete(name) }
        };
    }
    const elements = Object.fromEntries(
        ['mini-cart-backdrop', 'mini-cart-close', 'back-to-top'].map(id => [id, element(id)])
    );
    let scrollOptions;
    const context = vm.createContext({
        document: {
            addEventListener: (type, handler) => listeners.set(`document:${type}`, handler),
            getElementById: id => elements[id] || null,
            querySelectorAll: () => []
        },
        scrollY: initialScroll,
        addEventListener: (type, handler) => listeners.set(`window:${type}`, handler),
        matchMedia: () => ({ matches: reducedMotion }),
        scrollTo: options => { scrollOptions = options; }
    });
    context.window = context;
    vm.runInContext(source, context);
    assert.doesNotThrow(() => listeners.get('document:DOMContentLoaded')(), 'Storefront startup must finish');
    const button = elements['back-to-top'];
    assert.equal(button.hidden, initialScroll === 0, 'Visibility must match initial or restored scroll position');
    context.scrollY = 1;
    listeners.get('window:scroll')();
    assert.equal(button.hidden, false, 'Button must appear after scrolling');
    context.scrollY = 0;
    listeners.get('window:scroll')();
    assert.equal(button.hidden, true, 'Button must hide again at top');
    classes.add('open');
    listeners.get('mini-cart-close:click')();
    assert.equal(classes.has('open'), false, 'Mini-cart close must still work');
    listeners.get('back-to-top:click')({ preventDefault() {} });
    assert.equal(scrollOptions.top, 0);
    assert.equal(scrollOptions.behavior, reducedMotion ? 'instant' : 'smooth');
    delete elements['back-to-top'];
    assert.doesNotThrow(() => context.initBackToTop(), 'Layouts without button must be safe');
  }
}
console.log('Back-to-top visibility, restored scroll, click, reduced motion, missing button, and mini-cart close passed.');
