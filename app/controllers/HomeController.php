<?php

class HomeController {
    public function index(): void {
        // Active Categories
        $categories = DB::fetchAll(
            "SELECT * FROM categories WHERE is_active = 1 ORDER BY sort_order ASC"
        );

        // Featured products (favourites)
        $favourites = DB::fetchAll(
            "SELECT p.*, c.slug as category_slug,
                    (SELECT AVG(rating) FROM reviews WHERE product_id = p.id AND status = 'approved') as avg_rating,
                    (SELECT COUNT(*) FROM reviews WHERE product_id = p.id AND status = 'approved') as review_count
             FROM products p
             JOIN categories c ON p.category_id = c.id
             WHERE p.is_active = 1
             ORDER BY p.is_best_seller DESC, p.id ASC
             LIMIT 8"
        );


        // Recipes for bottom section
        $recipes = DB::fetchAll(
            "SELECT * FROM recipes WHERE is_published = 1 ORDER BY id ASC LIMIT 3"
        );

        render_view('shop/home', [
            'categories' => $categories,
            'favourites' => $favourites,
            'recipes'    => $recipes,
            'title'      => 'Kampung Cameron — Fresh from Cameron Highlands'
        ]);
    }
}
