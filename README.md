# Kampung Cameron — E-Commerce Web Application

A modern, bilingual e-commerce platform bridging Cameron Highlands fresh produce growers directly to retail households and B2B culinary businesses. Built with native PHP 8, MariaDB, and a custom Apple-inspired minimalist design system adhering to the 78-screen UI/UX specification.

---

## 🌿 Key Features

### 1. Retail Direct (B2C)
- **Live Search & Autocomplete**: Instant search overlay for produce and recipe ideas with debounce handling.
- **Bilingual Interface**: Seamless instant toggle between English (`EN`) and Simplified Chinese (`中文`).
- **Interactive Cart & Mini-Drawer**: Slide-over cart preview, dynamic quantity steppers, and promo code support (e.g. `FRESH10`).
- **High-Integrity Checkout**: Database row locks (`SELECT ... FOR UPDATE`) in ACID transactions prevent negative inventory.
- **Recipe-to-Cart Engine**: One-click "Add all ingredients to basket" linking highland recipes directly to fresh harvest inventory.
- **Customer Account Suite**: Order history tracking, address book manager, product review submissions, and personal wishlist.

### 2. Wholesale Portal (B2B)
- **SSM Business Verification**: Corporate registration workflow for cafés, grocers, and restaurants.
- **Dynamic Tiered Pricing**: Automated tier discounts (Tier A: 15%, Tier B: 20%, Tier C: 25%) computed live across catalogue.
- **Matrix Quick Order**: Rapid bulk-order sheet allowing multi-product SKU entry with real-time total updates.
- **Standing Recurring Orders**: Automated recurring order schedules with preferred delivery day intervals.
- **Corporate Invoicing**: Printable, tax-compliant invoice generator (`/admin/orders/invoice?id={id}`).

### 3. Back-Office Administration
- **KPI Command Center**: Today's revenue, order volume, pending applications, and low-stock alert notifications.
- **Catalog & Inventory Engine**: Full CRUD for products/categories with SKU tracking, safety thresholds, and audit logging (`stock_log`).
- **Stock Safeguard**: Cancelling an order automatically replenishes inventory and writes audit logs.
- **B2B Application Management**: One-click approval/rejection with tier assignment.
- **Business Reporting & Ledgers**: Channel revenue breakdown (retail vs wholesale) and 1-click CSV exports for sales and inventory.
- **Store Configuration**: Live control over flat shipping rates, contacts, and checkout payment gateway options.

---

## 🛠️ Architecture & Tech Stack

- **Runtime**: PHP 8.2+ (No heavy third-party runtime frameworks; clean MVC architecture).
- **Database**: MariaDB / MySQL 10.4+ with PDO singleton wrapper.
- **Frontend**: Vanilla ES6+ JavaScript, CSS3 variables, tactile Apple-inspired micro-interactions (`scale(0.97)`).
- **Typography & Aesthetics**: Lora Serif + DM Sans, Highland Green (`#2D6A4F`), Service Blue (`#184E68`), Warm Paper (`#FBF9F5`).

---

## 🚀 Setup & Installation (XAMPP)

### Prerequisites
1. [XAMPP](https://www.apachefriends.org/) with **Apache** and **MySQL / MariaDB** running.
2. PHP 8.1+ enabled in environment.

### 1. Database Setup
1. Launch **MySQL** via XAMPP Control Panel.
2. Open terminal and run:
```bash
# Create database and tables
mysql -u root < database/schema.sql

# Seed initial products, tiers, accounts, and demo orders
mysql -u root < database/seed.sql
```

### 2. Web Server Configuration

#### Option A: Run directly on `localhost:8001` (Recommended)
Open a terminal in the project root folder and run:
```bash
php -S localhost:8001 router.php
```
The application will be live at:
- **Direct Localhost URL**: `http://localhost:8001`

#### Option B: Apache via XAMPP
Create a directory junction in your XAMPP `htdocs` folder:
```powershell
cmd /c mklink /J "C:\xampp\htdocs\kampungcameron" "C:\path\to\KampungCameron"
```
Accessible at: `http://localhost/kampungcameron/public/`

---

## 🔐 Seed Accounts & Credentials

| Role | Email | Password | Access Highlights |
|---|---|---|---|
| **Administrator** | `admin@kampungcameron.com` | `Admin@123` | Full back-office dashboard, inventory, orders, wholesale approvals, CSV reports |
| **Wholesale Account** | `kitchen@greentable.com` | `Wholesale@123` | B2B catalog with tier discounts, quick order matrix, standing weekly orders |
| **Retail Customer** | `aisha@example.com` | `Customer@123` | B2C storefront, cart, order history, address book, wishlist |

---

## 📂 Project Directory Structure

```text
KampungCameron/
├── app/
│   ├── controllers/         # MVC Controllers (Shop, Cart, Wholesale, Admin, etc.)
│   ├── helpers/             # auth.php, i18n.php, view.php
│   └── views/               # Presentation templates
│       ├── layouts/         # main.php (Storefront), admin.php (Back-office)
│       ├── shop/            # Catalog, detail, cart, checkout, confirmation
│       ├── wholesale/       # Landing, apply, catalog, quick-order, standing-orders
│       ├── account/         # Customer profile, orders, addresses, wishlist
│       ├── recipes/         # Highland recipe cards & ingredient integration
│       ├── admin/           # Dashboard, products, inventory, orders, reports, settings
│       └── pages/           # About, contact, legal, policy, and 404
├── config/
│   ├── config.php           # Base URL auto-detection, paths, DB credentials
│   └── db.php               # PDO singleton database connection
├── database/
│   ├── schema.sql           # Complete 21-table MariaDB schema
│   └── seed.sql             # Demo catalogue, users, tiers, and orders
├── public/                  # Document root
│   ├── index.php            # Front controller & central request router
│   ├── .htaccess            # Apache URL rewrite rules
│   └── assets/
│       ├── css/             # style.css (Storefront), admin.css (Admin UI)
│       ├── js/              # main.js (Search, Mini-Cart, AJAX interactions)
│       └── images/          # Produce, banners, and recipe assets
└── README.md                # Project documentation
```

---

## 📄 License
Kampung Cameron E-Commerce Platform. All rights reserved.
