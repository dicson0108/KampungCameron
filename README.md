# Kampung Cameron — Frontend Refinement

A refined version of the current Kampung Cameron website, introducing **blue as a secondary brand color** alongside the familiar green and improving the frontend across the storefront, customer accounts, wholesale portal, and administration screens.

The visual direction keeps the fresh-produce identity of Cameron Highlands while giving the website clearer layouts, more consistent components, and a more comfortable experience on desktop and mobile.

## Design direction

| Color | Value | Role |
|---|---|---|
| Highland Green | `#256045` | Primary actions, brand accents, and selected states |
| Service Blue | `#184E68` | Secondary brand color for wholesale navigation, service areas, and the footer |
| Warm Paper | `#FAF8F5` | Main page background |
| White | `#FFFFFF` | Cards, forms, and foreground surfaces |

The interface combines Lora headings with system and DM Sans interface typography, plus Noto Sans SC for Chinese text. Shared CSS variables keep colors, spacing, corner radii, shadows, and motion consistent.

## Frontend refinements

- **Green and blue visual identity:** blue complements the primary green and gives wholesale and service sections a distinct presence.
- **Clearer page hierarchy:** refined typography, spacing, cards, forms, and navigation across the main website areas.
- **Responsive navigation:** desktop navigation and a mobile slide-over menu, with layouts that adapt to smaller screens.
- **Smoother shopping interactions:** live search suggestions, a mini-cart drawer, wishlist controls, and a floating back-to-top button.
- **Consistent branding:** updated logo placement, favicons, social icons, and payment-method artwork.
- **Shared components:** reusable product cards and account/wholesale navigation keep repeated screens consistent.
- **Accessibility details:** visible keyboard focus, labeled controls, and reduced-motion preferences.

## Website areas

| Area | Included functionality |
|---|---|
| Retail storefront | Product browsing and search, categories, cart, promo codes, checkout, and recipe ingredients added to cart |
| Customer accounts | Sign-in, registration, profiles, saved addresses, order history, wishlists, and reviews |
| Wholesale portal | Business applications, approval and pricing tiers, bulk catalogue, quick ordering, standing-order schedule management, and printable invoices |
| Administration | Products, categories, inventory and stock logs, orders, wholesale applications, promotions, reviews, recipes, reports, and settings |

English and Simplified Chinese are supported through translated interface strings and bilingual content fields.

Checkout records the selected payment method; it does not process online payments. Standing orders currently provide schedule management; automatic recurring-order generation is not implemented in this checkout.

## Technology

- **Backend:** native PHP with server-rendered views and controller-based routing.
- **Database:** MariaDB or MySQL, accessed through PDO.
- **Frontend:** HTML, custom CSS, and vanilla JavaScript.
- **Local development:** XAMPP or PHP's built-in development server.

## Run locally

### 1. Prepare the environment

Use PHP 8.2 or later with PDO MySQL and mbstring enabled, plus a running MariaDB/MySQL server. XAMPP provides the local web server, PHP, and database tools.

Update the database connection values in `config/config.php` for your environment. The included local defaults use database `kampung_cameron` on `127.0.0.1:3306`.

### 2. Import the database

From the project root, open the MySQL client:

```sh
mysql -u root
```

Then run these commands inside the client:

```sql
SOURCE database/schema.sql;
SOURCE database/seed.sql;
```

The seed includes demonstration products, recipes, pricing tiers, accounts, and orders.

### 3. Start the website

From the project root:

```sh
php -S localhost:8001 router.php
```

Open [localhost:8001](http://localhost:8001).

For Apache/XAMPP, place the project under `htdocs`, enable URL rewriting, and open its folder URL, such as `http://localhost/KampungCameron/`.

## Project structure

```text
app/
  controllers/       Request handling and database operations
  helpers/           Authentication, translations, views, and cart calculations
  views/             Storefront, account, wholesale, recipe, and admin templates
config/              Application configuration and PDO database helper
database/           Schema and demonstration data
public/
  assets/css/        Storefront and admin styles
  assets/js/         Shared frontend interactions
  assets/images/     Branding, produce, recipes, and interface artwork
  index.php          Front controller and route table
tests/               Focused PHP and JavaScript checks
router.php           PHP development-server router
```

## Checks

With PHP and Node.js available:

```sh
php tests/cart.php
node tests/back-to-top.cjs
```

These checks cover cart pricing and totals, guest/user cart selection, page rendering, shared navigation, bilingual product cards, and back-to-top behavior. The PHP checks use in-memory data and do not connect to the live database.

## License

Kampung Cameron. All rights reserved.
