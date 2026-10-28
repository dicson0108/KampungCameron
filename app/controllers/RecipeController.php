<?php

class RecipeController {
    public function index(): void {
        $cat = $_GET['cat'] ?? null;
        $sql = "SELECT * FROM recipes WHERE is_published = 1";
        $params = [];

        if ($cat && in_array($cat, ['veggie_recipe', 'health_nourishment'])) {
            $sql .= " AND category = :cat";
            $params['cat'] = $cat;
        }

        $sql .= " ORDER BY id ASC";
        $recipes = DB::fetchAll($sql, $params);

        render_view('recipes/index', [
            'recipes'     => $recipes,
            'selectedCat' => $cat,
            'title'       => 'Highland Recipes & Health Nourishment — Kampung Cameron'
        ]);
    }

    public function show(string|int $slugOrId): void {
        $column = is_numeric($slugOrId) ? 'id' : 'slug';
        $recipe = DB::fetch("SELECT * FROM recipes WHERE $column = :val AND is_published = 1", ['val' => $column === 'id' ? (int)$slugOrId : $slugOrId]);

        if (!$recipe) {
            http_response_code(404);
            render_view('pages/404', ['title' => 'Recipe Not Found']);
            return;
        }

        // Linked products
        $linkedProducts = DB::fetchAll(
            "SELECT p.*, rp.quantity as required_quantity
             FROM products p
             JOIN recipe_products rp ON p.id = rp.product_id
             WHERE rp.recipe_id = :rid AND p.is_active = 1",
            ['rid' => $recipe['id']]
        );

        render_view('recipes/detail', [
            'recipe'         => $recipe,
            'linkedProducts' => $linkedProducts,
            'title'          => trans($recipe, 'title') . ' — Kampung Cameron'
        ]);
    }

    public function addAllToCart(): void {
        $recipeId = (int)($_POST['recipe_id'] ?? 0);
        $linkedProducts = DB::fetchAll(
            "SELECT p.id, p.stock_quantity, rp.quantity
             FROM products p
             JOIN recipe_products rp ON p.id = rp.product_id
             WHERE rp.recipe_id = :rid AND p.is_active = 1",
            ['rid' => $recipeId]
        );

        if (empty($linkedProducts)) {
            set_flash('error', 'No products available for this recipe.');
            redirect('/recipes');
        }

        $cart = get_active_cart();
        $added = 0;

        foreach ($linkedProducts as $p) {
            if ($p['stock_quantity'] > 0) {
                $qty = min($p['quantity'], $p['stock_quantity']);
                $existing = DB::fetch("SELECT id, quantity FROM cart_items WHERE cart_id = :cid AND product_id = :pid", [
                    'cid' => $cart['id'],
                    'pid' => $p['id']
                ]);

                if ($existing) {
                    DB::update('cart_items', ['quantity' => $existing['quantity'] + $qty], 'id = :id', ['id' => $existing['id']]);
                } else {
                    DB::insert('cart_items', [
                        'cart_id'    => $cart['id'],
                        'product_id' => $p['id'],
                        'quantity'   => $qty
                    ]);
                }
                $added++;
            }
        }

        set_flash('success', sprintf('Added %d fresh recipe ingredients to your bag!', $added));
        redirect('/cart');
    }
}
