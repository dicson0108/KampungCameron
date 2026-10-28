<?php

class ProductController {
    public function show(string|int $slugOrId): void {
        if (is_numeric($slugOrId)) {
            $product = DB::fetch(
                "SELECT p.*, c.name_en as category_name_en, c.name_zh as category_name_zh, c.slug as category_slug
                 FROM products p
                 JOIN categories c ON p.category_id = c.id
                 WHERE p.id = :val AND p.is_active = 1",
                ['val' => (int)$slugOrId]
            );
        } else {
            $product = DB::fetch(
                "SELECT p.*, c.name_en as category_name_en, c.name_zh as category_name_zh, c.slug as category_slug
                 FROM products p
                 JOIN categories c ON p.category_id = c.id
                 WHERE p.slug = :val AND p.is_active = 1",
                ['val' => $slugOrId]
            );
        }

        if (!$product) {
            http_response_code(404);
            render_view('pages/404', ['title' => 'Product Not Found']);
            return;
        }

        // Wholesale discount
        $wsDiscount = $_SESSION['wholesale']['discount_percent'] ?? 0;
        $product['display_price'] = product_price($product);
        if ($wsDiscount > 0) {
            $product['wholesale_price'] = round($product['display_price'] * (1 - ($wsDiscount / 100)), 2);
        }

        // Reviews
        $reviews = DB::fetchAll(
            "SELECT r.*, u.name as reviewer_name
             FROM reviews r
             JOIN users u ON r.user_id = u.id
             WHERE r.product_id = :pid AND r.status = 'approved'
             ORDER BY r.created_at DESC",
            ['pid' => $product['id']]
        );

        $avgRating = 0.0;
        if (!empty($reviews)) {
            $ratings = array_column($reviews, 'rating');
            $avgRating = round(array_sum($ratings) / count($ratings), 1);
        }

        // Related recipes (recipes that include this product)
        $relatedRecipes = DB::fetchAll(
            "SELECT r.*
             FROM recipes r
             JOIN recipe_products rp ON r.id = rp.recipe_id
             WHERE rp.product_id = :pid AND r.is_published = 1",
            ['pid' => $product['id']]
        );

        // Related products in same category
        $relatedProducts = DB::fetchAll(
            "SELECT * FROM products 
             WHERE category_id = :cat_id AND id != :pid AND is_active = 1
             LIMIT 4",
            ['cat_id' => $product['category_id'], 'pid' => $product['id']]
        );

        // Check if current user has purchased this product (verified buyer check)
        $canReview = false;
        if (is_logged_in()) {
            $hasPurchased = DB::fetch(
                "SELECT oi.id 
                 FROM order_items oi
                 JOIN orders o ON oi.order_id = o.id
                 WHERE o.user_id = :uid AND oi.product_id = :pid",
                ['uid' => auth_id(), 'pid' => $product['id']]
            );
            $canReview = !empty($hasPurchased);
        }

        render_view('shop/detail', [
            'product'         => $product,
            'reviews'         => $reviews,
            'avgRating'       => $avgRating,
            'relatedRecipes'  => $relatedRecipes,
            'relatedProducts' => $relatedProducts,
            'canReview'       => $canReview,
            'title'           => trans($product, 'name') . ' — Kampung Cameron'
        ]);
    }

    public function submitReview(): void {
        require_login();
        if (!verify_csrf()) {
            set_flash('error', 'Security check failed. Please refresh and try again.');
            redirect('/shop');
        }

        $productId = (int)($_POST['product_id'] ?? 0);
        $rating = (int)($_POST['rating'] ?? 5);
        $reviewText = trim($_POST['review_text'] ?? '');

        if ($productId <= 0 || empty($reviewText)) {
            set_flash('error', 'Please complete your review before submitting.');
            redirect('/product?id=' . $productId);
        }

        $rating = max(1, min(5, $rating));

        DB::insert('reviews', [
            'product_id'  => $productId,
            'user_id'     => auth_id(),
            'rating'      => $rating,
            'review_text' => $reviewText,
            'status'      => 'pending' // Admin moderation required per FR-15
        ]);

        set_flash('success', 'Thank you! Your review has been submitted for admin moderation.');
        redirect('/product?id=' . $productId);
    }
}
