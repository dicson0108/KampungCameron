<?php

class ShopController {
    public function index(): void {
        $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;
        $inStockOnly = !empty($_GET['in_stock']);
        $sort = $_GET['sort'] ?? 'popular';
        $search = trim($_GET['q'] ?? '');

        $sql = "SELECT p.*, c.slug as category_slug, c.name_en as category_name_en, c.name_zh as category_name_zh,
                       (SELECT AVG(rating) FROM reviews WHERE product_id = p.id AND status = 'approved') as avg_rating,
                       (SELECT COUNT(*) FROM reviews WHERE product_id = p.id AND status = 'approved') as review_count
                FROM products p
                JOIN categories c ON p.category_id = c.id
                WHERE p.is_active = 1";
        $params = [];

        if ($categoryId) {
            $sql .= " AND p.category_id = :cat_id";
            $params['cat_id'] = $categoryId;
        }

        if ($inStockOnly) {
            $sql .= " AND p.stock_quantity > 0";
        }

        if (!empty($search)) {
            $sql .= " AND (p.name_en LIKE :search1 OR p.name_zh LIKE :search2 OR p.description_en LIKE :search3 OR p.description_zh LIKE :search4)";
            $searchTerm = "%$search%";
            $params['search1'] = $searchTerm;
            $params['search2'] = $searchTerm;
            $params['search3'] = $searchTerm;
            $params['search4'] = $searchTerm;
        }

        switch ($sort) {
            case 'price_low':
                $sql .= " ORDER BY COALESCE(p.sale_price, p.price) ASC";
                break;
            case 'price_high':
                $sql .= " ORDER BY COALESCE(p.sale_price, p.price) DESC";
                break;
            case 'newest':
                $sql .= " ORDER BY p.created_at DESC";
                break;
            case 'popular':
            default:
                $sql .= " ORDER BY p.is_best_seller DESC, p.id ASC";
                break;
        }

        $products = DB::fetchAll($sql, $params);
        $categories = DB::fetchAll("SELECT * FROM categories WHERE is_active = 1 ORDER BY sort_order ASC");


        render_view('shop/index', [
            'products'     => $products,
            'categories'   => $categories,
            'selectedCat'  => $categoryId,
            'inStockOnly'  => $inStockOnly,
            'currentSort'  => $sort,
            'searchQuery'  => $search,
            'title'        => __('nav_shop', 'Shop Fresh Produce')
        ]);
    }

    public function category(string $slug): void {
        $cat = DB::fetch("SELECT * FROM categories WHERE slug = :slug AND is_active = 1", ['slug' => $slug]);
        if (!$cat) {
            redirect('/shop');
        }
        $_GET['category'] = $cat['id'];
        $this->index();
    }

    public function searchSuggestions(): void {
        header('Content-Type: application/json');
        $q = trim($_GET['q'] ?? '');
        if (mb_strlen($q) < 1) {
            echo json_encode(['products' => [], 'recipes' => []]);
            exit;
        }

        $term = "%$q%";
        $products = DB::fetchAll(
            "SELECT id, slug, name_en, name_zh, unit_weight, price, sale_price, image, stock_quantity
             FROM products
             WHERE is_active = 1 AND (name_en LIKE :q1 OR name_zh LIKE :q2)
             LIMIT 6",
            ['q1' => $term, 'q2' => $term]
        );

        $recipes = DB::fetchAll(
            "SELECT id, slug, title_en, title_zh, category, image, prep_time_minutes, cooking_time_minutes
             FROM recipes
             WHERE is_published = 1 AND (title_en LIKE :q1 OR title_zh LIKE :q2)
             LIMIT 4",
            ['q1' => $term, 'q2' => $term]
        );

        foreach ($products as &$p) {
            $p['name'] = trans($p, 'name');
            $p['url'] = url('/product/' . $p['slug']);
            $p['image_url'] = asset('images/' . $p['image']);
            $p['formatted_price'] = format_currency($p['sale_price'] ?? $p['price']);
        }
        unset($p);

        foreach ($recipes as &$r) {
            $r['title'] = trans($r, 'title');
            $r['url'] = url('/recipes/' . $r['slug']);
            $r['image_url'] = asset('images/' . $r['image']);
        }
        unset($r);

        echo json_encode([
            'products' => $products,
            'recipes'  => $recipes
        ]);
        exit;
    }
}
