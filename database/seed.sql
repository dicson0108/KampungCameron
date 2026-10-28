-- Kampung Cameron Seed Data
USE `kampung_cameron`;

-- Users
INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `phone`, `role`, `is_active`) VALUES
(1, 'Store Administrator', 'admin@kampungcameron.com', '$2y$10$XU4LdVaCvM.7SVzmyBAtFOAMiItBZKIy4Nd6hbZKcVImCKODPjVMC', '+60 12 345 6789', 'admin', 1),
(2, 'Aisha Tan', 'aisha@example.com', '$2y$10$WIW66CkVPMeREdOkNzjfAOFStuEf9I9WOl0OPC1XhedoJzOyZ5XDe', '+60 12 345 6789', 'customer', 1),
(3, 'Green Table Kitchen', 'kitchen@greentable.com', '$2y$10$m8OKgrKdQYqD9OtgKpCeo.sRzktEilwhKapcnzqHfd0E5eILWXt3C', '+60 17 889 4412', 'wholesale', 1)
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- Addresses
INSERT INTO `addresses` (`id`, `user_id`, `label`, `recipient_name`, `phone`, `address_line1`, `address_line2`, `city`, `state`, `postcode`, `is_default`) VALUES
(1, 2, 'Home', 'Aisha Tan', '+60 12 345 6789', '18, Jalan SS 21/3', 'Damansara Utama', 'Petaling Jaya', 'Selangor', '47800', 1),
(2, 3, 'Kitchen 1', 'Chef Wong', '+60 17 889 4412', '88, Jalan Telawi 2', 'Bangsar Baru', 'Kuala Lumpur', 'Wilayah Persekutuan', '59100', 1)
ON DUPLICATE KEY UPDATE `recipient_name`=VALUES(`recipient_name`);

-- Pricing Tiers
INSERT INTO `pricing_tiers` (`id`, `name`, `code`, `discount_percent`, `description`, `min_spend`) VALUES
(1, 'Tier A · Essential', 'tier_a', 15.00, '15% wholesale discount for daily restaurant kitchens', 200.00),
(2, 'Tier B · Growth', 'tier_b', 20.00, '20% wholesale discount for multi-outlet caterers', 500.00),
(3, 'Tier C · Premier', 'tier_c', 25.00, '25% wholesale discount for commercial hotel kitchens', 1000.00)
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- Wholesale Accounts
INSERT INTO `wholesale_accounts` (`id`, `user_id`, `business_name`, `registration_number`, `contact_person`, `contact_email`, `contact_phone`, `business_type`, `tier_id`, `status`, `admin_notes`, `approved_at`) VALUES
(1, 3, 'Green Table Kitchen', '202401089201 (150821-X)', 'Chef Michael Wong', 'kitchen@greentable.com', '+60 17 889 4412', 'Restaurant / Kitchen', 1, 'approved', 'Verified SSM registration and culinary business license.', NOW())
ON DUPLICATE KEY UPDATE `business_name`=VALUES(`business_name`);

-- Categories
INSERT INTO `categories` (`id`, `slug`, `name_en`, `name_zh`, `description_en`, `description_zh`, `image`, `sort_order`, `is_active`) VALUES
(1, 'leafy-greens', 'Leafy greens', '叶菜类', 'Fresh crisp greens harvested daily from highland mist.', '每日高山晨雾中采摘的鲜脆绿叶时蔬。', 'leafy-greens.png', 1, 1),
(2, 'beans-pods', 'Beans & pods', '豆类', 'Sweet tender peas, French beans and highland pods.', '清甜嫩滑的法国豆、蜜豆及高山豆类。', 'harvest.png', 2, 1),
(3, 'cruciferous', 'Cruciferous', '十字花科', 'Dense crisp broccoli, cauliflower and hearty stems.', '紧实清脆的西兰花、花椰菜与营养茎菜。', 'broccoli.png', 3, 1),
(4, 'roots-alliums', 'Roots & alliums', '根茎与葱蒜', 'Farm-dug carrots, radishes, sweet potatoes and spring alliums.', '新鲜出土的甜胡萝卜、水萝卜与甘美葱蒜。', 'carrots.png', 4, 1),
(5, 'herbs', 'Fresh herbs', '新鲜香草', 'Fragrant rosemary, mint, basil and mountain thyme.', '香气馥郁的迷迭香、薄荷、罗勒与高山百里香。', 'leafy-greens.png', 5, 1),
(6, 'boxes-combos', 'Boxes & combos', '蔬菜盲盒与组合', 'Curated highland harvests and seasonal chef mystery boxes.', '精选高地应季蔬菜盲盒与超值家庭组合箱。', 'harvest.png', 6, 1)
ON DUPLICATE KEY UPDATE `name_en`=VALUES(`name_en`);

-- Products
INSERT INTO `products` (`id`, `category_id`, `sku`, `slug`, `name_en`, `name_zh`, `unit_weight`, `price`, `sale_price`, `stock_quantity`, `low_stock_threshold`, `description_en`, `description_zh`, `image`, `is_best_seller`, `is_active`) VALUES
(1, 3, 'KC-BRO-01', 'cameron-broccoli', 'Cameron Broccoli', '金马仑西兰花', '300g', 6.50, NULL, 45, 10, 'Grown in high-altitude cool mist, our Cameron broccoli is remarkably crisp, tender, and naturally sweet without bitterness.', '在高海拔凉爽晨雾中培育，金马仑西兰花格外脆嫩清甜，毫无苦涩涩感。', 'broccoli.png', 1, 1),
(2, 4, 'KC-TOM-01', 'vine-tomatoes', 'Vine Tomatoes', '新鲜番茄', '500g', 7.20, 5.90, 6, 10, 'Sun-ripened on the vine in greenhouse terraces, balancing rich floral acidity with succulent sweetness.', '温室梯田自然吊藤成熟，酸甜浓郁，多汁鲜美，营养丰富。', 'tomatoes.png', 1, 1),
(3, 4, 'KC-CAR-01', 'sweet-carrots', 'Sweet Carrots', '甜胡萝卜', '500g', 4.20, NULL, 30, 8, 'Deep orange, tender core with natural sugars nurtured by volcanic organic soil in the high hills.', '高山肥沃有机土滋润生长，色泽鲜亮橙红，肉质细嫩清脆甜爽。', 'carrots.png', 1, 1),
(4, 1, 'KC-SPN-01', 'baby-spinach', 'Baby Spinach', '嫩菠菜', '200g', 4.90, NULL, 0, 5, 'Tender early-harvest leaves perfect for wholesome salads, green smoothies, or quick garlic sautés.', '初采嫩叶，质地柔嫩甘甜，适宜拌制营养沙拉或蒜蓉快炒。', 'leafy-greens.png', 0, 1),
(5, 1, 'KC-ROM-01', 'highlands-romaine-lettuce', 'Highlands Romaine Lettuce', '金马仑罗马生菜', '400g', 5.20, NULL, 28, 6, 'Exceptionally crunchy with succulent ribbed leaves. The quintessential base for a true Caesar salad.', '叶梗爽脆清多汁，口感清甜微凉，制作经典凯撒沙拉的上佳之选。', 'leafy-greens.png', 1, 1),
(6, 6, 'KC-BOX-01', 'veggie-mystery-box', 'Veggie Mystery Box', '时令蔬菜盲盒', '3kg', 38.00, 34.00, 15, 5, 'A seasonal variety pack of 6-8 freshly harvested vegetables hand-picked by our farmers on the morning of dispatch.', '由农人于出货清晨亲手甄选采摘的6至8款当季新鲜蔬菜精装组合箱。', 'harvest.png', 1, 1)
ON DUPLICATE KEY UPDATE `name_en`=VALUES(`name_en`);

-- Stock Log Initial Seeds
INSERT INTO `stock_log` (`product_id`, `change_qty`, `balance_qty`, `reason`, `reference_id`, `created_by`) VALUES
(1, 45, 45, 'Initial highland harvest inventory', 'HARVEST-2026-001', 'admin'),
(2, 6, 6, 'Low inventory stock count check', 'AUDIT-2026-002', 'admin'),
(3, 30, 30, 'Initial highland harvest inventory', 'HARVEST-2026-001', 'admin'),
(4, 0, 0, 'Out of stock pending harvest', 'RESTOCK-PENDING', 'admin'),
(5, 28, 28, 'Initial highland harvest inventory', 'HARVEST-2026-001', 'admin'),
(6, 15, 15, 'Curated box assembly batch', 'BOX-BATCH-10', 'admin');

-- Standing Orders
INSERT INTO `standing_orders` (`id`, `wholesale_account_id`, `name`, `delivery_day`, `delivery_address`, `start_date`, `notes`, `status`) VALUES
(1, 1, 'Monday essentials', 'Monday', 'Green Table Kitchen, 88 Jalan Telawi 2, Bangsar, 59100 KL', '2026-10-19', 'Call the kitchen before delivery.', 'active')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- Standing Order Items
INSERT INTO `standing_order_items` (`standing_order_id`, `product_id`, `quantity`) VALUES
(1, 1, 10),
(1, 2, 20),
(1, 3, 10)
ON DUPLICATE KEY UPDATE `quantity`=VALUES(`quantity`);

-- Promo Codes
INSERT INTO `promo_codes` (`id`, `code`, `discount_type`, `discount_value`, `min_order_amount`, `max_uses`, `uses_count`, `is_active`) VALUES
(1, 'FRESH10', 'percentage', 10.00, 20.00, 500, 18, 1),
(2, 'WELCOME5', 'fixed', 5.00, 30.00, 200, 5, 1)
ON DUPLICATE KEY UPDATE `code`=VALUES(`code`);

-- Orders
INSERT INTO `orders` (`id`, `order_number`, `user_id`, `order_type`, `status`, `subtotal`, `discount_amount`, `shipping_fee`, `total_amount`, `payment_method`, `payment_status`, `customer_name`, `customer_email`, `customer_phone`, `delivery_address`, `delivery_city`, `delivery_state`, `delivery_postcode`, `delivery_notes`, `promo_code`, `created_at`) VALUES
(1, 'KC-1042', 2, 'retail', 'pending', 29.00, 2.90, 8.00, 34.10, 'fpx', 'selected', 'Aisha Tan', 'aisha@example.com', '+60 12 345 6789', '18, Jalan SS 21/3, Damansara Utama', 'Petaling Jaya', 'Selangor', '47800', 'Please call when you arrive.', 'FRESH10', '2026-10-17 11:30:00'),
(2, 'WH-2048', 3, 'wholesale', 'processing', 360.00, 0.00, 8.00, 368.00, 'fpx', 'confirmed', 'Green Table Kitchen', 'kitchen@greentable.com', '+60 17 889 4412', '88, Jalan Telawi 2, Bangsar Baru', 'Kuala Lumpur', 'Wilayah Persekutuan', '59100', 'Deliver to rear kitchen service entrance.', NULL, '2026-10-17 08:45:00')
ON DUPLICATE KEY UPDATE `order_number`=VALUES(`order_number`);

-- Order Items
INSERT INTO `order_items` (`order_id`, `product_id`, `product_name`, `unit_weight`, `unit_price`, `quantity`, `subtotal`) VALUES
(1, 1, 'Cameron Broccoli', '300g', 6.50, 2, 13.00),
(1, 2, 'Vine Tomatoes', '500g', 5.90, 2, 11.80),
(1, 3, 'Sweet Carrots', '500g', 4.20, 1, 4.20),
(2, 1, 'Cameron Broccoli', '300g', 5.52, 20, 110.40),
(2, 2, 'Vine Tomatoes', '500g', 5.01, 30, 150.30),
(2, 3, 'Sweet Carrots', '500g', 3.57, 28, 99.96);

-- Reviews
INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `order_id`, `rating`, `review_text`, `status`, `created_at`) VALUES
(1, 1, 2, 1, 5, 'Crisp, exceptionally sweet and fresh. Arrived cold and firm!', 'approved', '2026-10-16 14:00:00'),
(2, 2, 2, 1, 5, 'Juicy and fragrant. Makes remarkable fresh pasta sauce.', 'approved', '2026-10-16 14:15:00'),
(3, 3, 2, 1, 4, 'Very sweet and crunchy, perfect for dipping.', 'approved', '2026-10-16 14:20:00')
ON DUPLICATE KEY UPDATE `review_text`=VALUES(`review_text`);

-- Wishlist
INSERT INTO `wishlist` (`user_id`, `product_id`) VALUES
(2, 1),
(2, 2)
ON DUPLICATE KEY UPDATE `user_id`=VALUES(`user_id`);

-- Recipes
INSERT INTO `recipes` (`id`, `slug`, `category`, `title_en`, `title_zh`, `prep_time_minutes`, `cooking_time_minutes`, `servings`, `image`, `excerpt_en`, `excerpt_zh`, `ingredients_en`, `ingredients_zh`, `steps_en`, `steps_zh`, `is_published`) VALUES
(1, 'highland-harvest-salad', 'veggie_recipe', 'Highland harvest salad', '高地丰收沙拉', 15, 0, '2 portions', 'salad.png',
'Crisp Cameron broccoli florets, sweet vine tomatoes, and shaved carrots tossed in cold-pressed virgin olive oil and lemon.',
'鲜脆金马仑西兰花、清甜藤茄与细刨胡萝卜丝，淋上冷压初榨橄榄油与鲜柠檬汁，清新可口。',
'Cameron Broccoli - 1 head (300g)\nVine Tomatoes - 200g (diced)\nSweet Carrots - 1 medium (ribboned)\nAvocado - 1 sliced\nExtra virgin olive oil - 2 tbsp\nLemon juice - 1 tbsp\nSea salt & black pepper to taste',
'金马仑西兰花 - 1颗 (300克)\n新鲜番茄 - 200克 (切块)\n甜胡萝卜 - 1根 (刨薄片)\n牛油果 - 1个 (切片)\n特级初榨橄榄油 - 2汤匙\n鲜柠檬汁 - 1汤匙\n海盐与现磨黑胡椒 适量',
'1. Blanch broccoli florets in boiling salted water for 60 seconds, then plunge into ice water to preserve bright emerald colour.\n2. In a wide bowl, toss diced vine tomatoes, shaved carrots, and drained broccoli.\n3. Drizzle with extra virgin olive oil and fresh lemon juice.\n4. Season with sea salt and cracked pepper. Top with avocado and serve immediately.',
'1. 西兰花切小朵，放入沸盐水中焯烫60秒，捞出立即投入冰水中保持鲜绿爽脆。\n2. 在大碗中混合切块番茄、胡萝卜薄片与沥干水分的西兰花。\n3. 淋入初榨橄榄油与鲜柠檬汁，轻柔翻拌均匀。\n4. 撒少许海盐与黑胡椒调味，铺上牛油果切片即可享用。', 1),

(2, 'ginger-greens-soup', 'health_nourishment', 'Ginger greens soup', '生姜鲜青菜汤', 10, 15, '3-4 portions', 'salad.png',
'A restorative, gentle highland broth infused with aged ginger, baby spinach, and sweet sliced carrots.',
'老姜清炖高地温润清汤，融入鲜嫩菠菜与清甜胡萝卜片，暖胃滋养。',
'Baby Spinach - 200g\nSweet Carrots - 1 sliced\nAged Bentong Ginger - 30g (crushed)\nVegetable broth - 4 cups\nSesame oil - 1 tsp\nWhite pepper - 1 pinch\nSea salt to taste',
'嫩菠菜 - 200克\n甜胡萝卜 - 1根 (切片)\n文冬老姜 - 30克 (拍扁)\n高汤/清汤 - 4碗\n纯麻油 - 1茶匙\n白胡椒粉 - 1撮\n海盐 适量',
'1. Bring vegetable broth and crushed ginger to a rolling simmer for 10 minutes.\n2. Add sliced carrots and cook until tender (approx. 5 minutes).\n3. Turn off heat and immediately fold in baby spinach leaves until gently wilted.\n4. Drizzle with sesame oil and a touch of white pepper before ladling warm into bowls.',
'1. 将高汤与拍扁老姜一同煮沸，小火慢煨10分钟释放姜香。\n2. 加入胡萝卜片，煮约5分钟至熟软。\n3. 关火，立刻加入嫩菠菜叶，利用余温轻焯至断生。\n4. 滴入香麻油与白胡椒粉，即可趁热盛碗品尝。', 1),

(3, 'roasted-garden-vegetables', 'veggie_recipe', 'Roasted garden vegetables', '香烤田园时蔬', 10, 25, '4 portions', 'salad.png',
'Caramelised sweet carrots, blistered vine tomatoes and charred broccoli roasted with garlic and mountain thyme.',
'焦香微甜的胡萝卜、自然爆汁的藤茄与微焦西兰花，配以蒜香与高山百里香烘烤。',
'Cameron Broccoli - 1 head (cut into medium florets)\nVine Tomatoes - 250g (on the vine)\nSweet Carrots - 2 large (halved lengthwise)\nGarlic cloves - 4 unpeeled\nFresh rosemary/thyme - 2 sprigs\nOlive oil - 3 tbsp',
'金马仑西兰花 - 1颗 (切块)\n新鲜番茄 - 250克 (连藤整串)\n甜胡萝卜 - 2根 (对半纵剖)\n蒜瓣 - 4瓣 (带皮)\n新鲜迷迭香/百里香 - 2枝\n橄榄油 - 3汤匙',
'1. Preheat oven to 200°C (395°F).\n2. Arrange broccoli, halved carrots, and whole tomatoes onto a rimmed baking sheet.\n3. Drizzle generously with olive oil, scatter garlic and fresh herb sprigs.\n4. Roast for 22-25 minutes until carrots are tender and edges of broccoli are deliciously charred.',
'1. 烤箱预热至200°C。\n2. 将西兰花朵、胡萝卜半块与整串藤茄平铺在烤盘上。\n3. 淋上橄榄油，散放带皮蒜瓣与新鲜香草。\n4. 放入烤箱烘烤22-25分钟，直至胡萝卜熟软、西兰花边缘呈现微焦诱人香气。', 1)
ON DUPLICATE KEY UPDATE `title_en`=VALUES(`title_en`);

-- Recipe Products mapping
INSERT INTO `recipe_products` (`recipe_id`, `product_id`, `quantity`) VALUES
(1, 1, 1),
(1, 2, 1),
(1, 3, 1),
(2, 4, 1),
(2, 3, 1),
(3, 1, 1),
(3, 2, 1),
(3, 3, 1)
ON DUPLICATE KEY UPDATE `quantity`=VALUES(`quantity`);

-- Settings
INSERT INTO `settings` (`setting_key`, `setting_value`, `description`) VALUES
('flat_shipping_fee', '8.00', 'Flat standard shipping fee in MYR across West Malaysia'),
('store_name', 'Kampung Cameron', 'Brand Store Name'),
('contact_email', 'hello@kampungcameron.com', 'Primary store support email'),
('contact_phone', '+60 12 345 6789', 'Direct hotline phone number'),
('contact_whatsapp', '+60 12 345 6789', 'Customer service WhatsApp line'),
('enabled_payment_methods', 'fpx,card,grabpay,tng,cod', 'Active selectable checkout payment channels')
ON DUPLICATE KEY UPDATE `setting_value`=VALUES(`setting_value`);
