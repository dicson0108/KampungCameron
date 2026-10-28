<?php
// Bilingual Translation & Internationalization Helper (EN / 中文)

if (isset($_GET['lang'])) {
    $lang = strtolower(trim($_GET['lang']));
    if (in_array($lang, ['en', 'zh'])) {
        $_SESSION['lang'] = $lang;
        setcookie('kc_lang', $lang, time() + (86400 * 30), '/');
    }
}

function current_lang(): string {
    if (!empty($_SESSION['lang'])) {
        return $_SESSION['lang'];
    }
    if (!empty($_COOKIE['kc_lang']) && in_array($_COOKIE['kc_lang'], ['en', 'zh'])) {
        return $_COOKIE['kc_lang'];
    }
    return 'en';
}

function trans(?array $item, string $field): string {
    if (!$item) return '';
    $lang = current_lang();
    $key = "{$field}_{$lang}";
    if (!empty($item[$key])) {
        return (string)$item[$key];
    }
    // Fallback to English or other language
    if (!empty($item["{$field}_en"])) {
        return (string)$item["{$field}_en"];
    }
    if (!empty($item["{$field}_zh"])) {
        return (string)$item["{$field}_zh"];
    }
    return (string)($item[$field] ?? '');
}

$GLOBALS['kc_translations'] = [
    'en' => [
        // Global Header
        'topbar_tagline'        => 'Fresh from Cameron Highlands. Delivered to your door.',
        'search_placeholder'    => 'Search fresh produce & recipes',
        'bag'                   => 'Bag',
        'my_account'            => 'My account',
        'nav_home'              => 'Home',
        'nav_wholesale'         => 'Wholesale',
        'nav_shop'              => 'Shop',
        'nav_recipes'           => 'Recipes',
        'nav_about'             => 'About',
        'nav_contact'           => 'Contact',

        // Global Footer
        'footer_slogan_title'   => 'Kampung Cameron',
        'footer_slogan_desc'    => 'Good food starts with fresh roots. Fresh produce for your home and kitchen.',
        'footer_explore'        => 'Explore',
        'footer_customer_care'  => 'Customer care',
        'footer_stay_connected' => 'Stay connected',
        'footer_terms'          => 'Terms & Conditions',
        'footer_privacy'        => 'Privacy Policy',
        'footer_shipping'       => 'Shipping & Delivery',
        'footer_return'         => 'Return Policy',
        'footer_copyright'      => '© 2026 Kampung Cameron · Fresh from the highlands',

        // Home Page
        'hero_tag'              => 'FROM THE HIGHLANDS, WITH CARE',
        'hero_title'            => 'Fresh roots. Happy kitchens.',
        'hero_desc'             => 'Bring the goodness of Cameron Highlands home. Seasonal vegetables, hand-picked for your everyday.',
        'hero_cta'              => 'Shop this week’s harvest',
        'usp_highland'          => 'Highland grown',
        'usp_packed'            => 'Carefully packed',
        'usp_shipping'          => 'Flat delivery RM 8',
        'usp_kitchens'          => 'For homes & kitchens',
        'cat_section_title'     => 'A little freshness for every meal',
        'favourites_title'      => 'The favourites, freshly picked',
        'promo_card1_title'     => 'Your first harvest, 10% off',
        'promo_card1_desc'      => 'Use FRESH10 at checkout · Terms apply',
        'promo_card2_title'     => 'A box full of possibilities',
        'promo_card2_desc'      => 'Veggie Mystery Box · Seasonal variety',
        'explore_offers'        => 'Explore offers',
        'b2b_banner_title'      => 'A fresher partner for your kitchen.',
        'b2b_banner_sub'        => 'FOR RESTAURANTS, CAFÉS & KITCHENS',
        'b2b_banner_desc'       => 'Bulk prices, standing orders and dependable weekly supply.',
        'b2b_banner_btn'        => 'Explore wholesale',
        'recipes_section_title' => 'Good ingredients. Better ideas.',
        'read_recipe'           => 'Read recipe',

        // Product Badges & Actions
        'badge_in_stock'        => 'In stock',
        'badge_low_stock'       => 'Low · %d left',
        'badge_sold_out'        => 'Sold out',
        'btn_add_to_cart'       => '+ Add to cart',
        'btn_notify_me'         => 'Notify me',
        'btn_view_cart'         => 'View cart',
        'btn_checkout'          => 'Checkout',
        'subtotal'              => 'Subtotal',
        'your_basket'           => 'Your basket (%d items)',

        // Shop & Catalogue
        'filter_all'            => 'All categories',
        'sort_popular'          => 'Popularity',
        'sort_newest'           => 'Newest',
        'sort_price_low'        => 'Price: Low to High',
        'sort_price_high'       => 'Price: High to Low',
        'in_stock_only'         => 'In stock only',
        'no_products_found'     => 'No products found matching your search.',
        'clear_filters'         => 'Clear all filters',

        // Product Detail
        'unit'                  => 'Unit',
        'quantity'              => 'Quantity',
        'related_recipes'       => 'Cook with this harvest',
        'customer_reviews'      => 'Customer Reviews',
        'write_review'          => 'Write a review',
        'rating'                => 'Rating',
        'submit_review'         => 'Submit Review',

        // Cart & Checkout
        'shopping_cart'         => 'Shopping Cart',
        'empty_cart'            => 'Your shopping bag is empty.',
        'start_shopping'        => 'Explore fresh produce',
        'item'                  => 'Item',
        'price'                 => 'Price',
        'total'                 => 'Total',
        'promo_code'            => 'Promo code',
        'apply'                 => 'Apply',
        'discount'              => 'Discount',
        'shipping'              => 'Flat shipping',
        'saved_address'         => 'Saved address',
        'full_name'             => 'Full name',
        'email_address'         => 'Email',
        'phone_number'          => 'Phone',
        'address_line1'         => 'Address line 1',
        'address_line2'         => 'Address line 2',
        'city'                  => 'City',
        'state'                 => 'State',
        'postcode'              => 'Postcode',
        'delivery_notes'        => 'Delivery notes (optional)',
        'payment_method'        => 'Payment method',
        'payment_note'          => 'Payment choice is recorded with your order. No payment is processed on this website.',
        'agree_terms'           => 'I agree to the Terms & Conditions and Privacy Policy.',
        'place_order'           => 'Place order',
        'order_confirmed'       => 'Thank you! Your order is placed.',
        'order_number'          => 'Order number',

        // Wholesale Portal
        'ws_hero_title'         => 'Your kitchen. Our fresh promise.',
        'ws_hero_desc'          => 'Plan the week with tier-based prices, scheduled supply and fresh produce your team can count on.',
        'ws_apply_btn'          => 'Apply for a wholesale account',
        'ws_whatsapp_btn'       => 'Chat on WhatsApp',

        // Account
        'login'                 => 'Log in',
        'register'              => 'Register',
        'logout'                => 'Log out',

        // Flash & System
        'msg_login_required'    => 'Please log in to continue.',
        'msg_access_denied'     => 'Access denied.',
    ],

    'zh' => [
        // Global Header
        'topbar_tagline'        => '金马仑高原新鲜食材，直送您的家。',
        'search_placeholder'    => '搜索新鲜蔬菜与食谱',
        'bag'                   => '购物袋',
        'my_account'            => '我的账户',
        'nav_home'              => '首页',
        'nav_wholesale'         => '批发服务',
        'nav_shop'              => '选购蔬菜',
        'nav_recipes'           => '食谱',
        'nav_about'             => '关于我们',
        'nav_contact'           => '联系我们',

        // Global Footer
        'footer_slogan_title'   => 'Kampung Cameron',
        'footer_slogan_desc'    => '美味源于新鲜。为您的家庭与厨房精选优质蔬菜。',
        'footer_explore'        => '探索',
        'footer_customer_care'  => '客户服务',
        'footer_stay_connected' => '关注我们',
        'footer_terms'          => '条款与条件',
        'footer_privacy'        => '隐私政策',
        'footer_shipping'       => '运输与配送',
        'footer_return'         => '退换货政策',
        'footer_copyright'      => '© 2026 Kampung Cameron · 高原新鲜直送',

        // Home Page
        'hero_tag'              => '来自金马仑高原的新鲜好滋味',
        'hero_title'            => '新鲜食材，幸福厨房。',
        'hero_desc'             => '精选时令蔬菜与香草，从高原直送您的餐桌。',
        'hero_cta'              => '立即选购',
        'usp_highland'          => '高原直供',
        'usp_packed'            => '精心包装',
        'usp_shipping'          => '统一运费 RM 8',
        'usp_kitchens'          => '家庭与商厨优选',
        'cat_section_title'     => '每一餐的鲜甜好滋味',
        'favourites_title'      => '本周人气精选',
        'promo_card1_title'     => '首单优惠 9折',
        'promo_card1_desc'      => '结账时输入 FRESH10 · 需符合条款',
        'promo_card2_title'     => '开启美味盲盒',
        'promo_card2_desc'      => '时令蔬菜盲盒 · 甄选当季农家时蔬',
        'explore_offers'        => '查看优惠',
        'b2b_banner_title'      => '为您的厨房提供稳定新鲜的食材。',
        'b2b_banner_sub'        => '餐厅、咖啡厅与商厨优选供货',
        'b2b_banner_desc'       => '批发阶梯价格、定期配送、品质稳定可靠。',
        'b2b_banner_btn'        => '了解批发服务',
        'recipes_section_title' => '新鲜好食材，烹饪好灵感。',
        'read_recipe'           => '查看食谱',

        // Product Badges & Actions
        'badge_in_stock'        => '有货',
        'badge_low_stock'       => '热卖中 · 仅剩%d份',
        'badge_sold_out'        => '已售罄',
        'btn_add_to_cart'       => '+ 加入购物车',
        'btn_notify_me'         => '到货通知',
        'btn_view_cart'         => '查看购物车',
        'btn_checkout'          => '立即结账',
        'subtotal'              => '小计',
        'your_basket'           => '您的购物袋 (%d 件商品)',

        // Shop & Catalogue
        'filter_all'            => '全部分类',
        'sort_popular'          => '热销排行',
        'sort_newest'           => '最新上架',
        'sort_price_low'        => '价格：从低到高',
        'sort_price_high'       => '价格：从高到低',
        'in_stock_only'         => '仅显示有货',
        'no_products_found'     => '抱歉，没有找到匹配的蔬菜食材。',
        'clear_filters'         => '清除筛选条件',

        // Product Detail
        'unit'                  => '规格',
        'quantity'              => '数量',
        'related_recipes'       => '相关推荐食谱',
        'customer_reviews'      => '顾客评价',
        'write_review'          => '发表评价',
        'rating'                => '评分',
        'submit_review'         => '提交评价',

        // Cart & Checkout
        'shopping_cart'         => '购物车',
        'empty_cart'            => '您的购物车是空的。',
        'start_shopping'        => '开始选购蔬菜',
        'item'                  => '商品',
        'price'                 => '单价',
        'total'                 => '合计',
        'promo_code'            => '优惠码',
        'apply'                 => '使用',
        'discount'              => '优惠折扣',
        'shipping'              => '标准运费',
        'saved_address'         => '常用地址',
        'full_name'             => '姓名',
        'email_address'         => '电子邮箱',
        'phone_number'          => '联系电话',
        'address_line1'         => '详细地址 1',
        'address_line2'         => '详细地址 2',
        'city'                  => '城市',
        'state'                 => '州属 / 省份',
        'postcode'              => '邮政编码',
        'delivery_notes'        => '配送备注（可选）',
        'payment_method'        => '支付方式',
        'payment_note'          => '支付方式将记录在您的订单中。本测试站点不进行真实扣款。',
        'agree_terms'           => '我已阅读并同意《条款与条件》及《隐私政策》。',
        'place_order'           => '确认下单',
        'order_confirmed'       => '感谢您的订购！订单已生成。',
        'order_number'          => '订单编号',

        // Wholesale Portal
        'ws_hero_title'         => '新鲜承诺，安心厨房。',
        'ws_hero_desc'          => '专为餐饮团队打造：阶梯批发特惠、每周定期排期、高山直供新鲜保障。',
        'ws_apply_btn'          => '申请批发采购账户',
        'ws_whatsapp_btn'       => 'WhatsApp 咨询',

        // Account
        'login'                 => '登录',
        'register'              => '注册',
        'logout'                => '退出登录',

        // Flash & System
        'msg_login_required'    => '请先登录您的账户。',
        'msg_access_denied'     => '权限不足，无法访问该页面。',
    ]
];

function __(string $key, string $default = ''): string {
    $lang = current_lang();
    return $GLOBALS['kc_translations'][$lang][$key] 
        ?? $GLOBALS['kc_translations']['en'][$key] 
        ?? $default 
        ?? $key;
}
