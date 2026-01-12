# Wishlist Application

A simple Laravel application that allows users to browse products and manage a personal wishlist.

---

## Tech Stack

* **Backend:** PHP 8.2+, Laravel 12
* **Frontend:** React, Inertia.js
* **Database:** SQLite (default) / MySQL / PostgreSQL
* **Authentication:** Laravel Fortify, Laravel Sanctum (session-based)
* **Testing:** Pest

---

## Requirements

* PHP 8.2+
* Composer
* Node.js 18+
* SQLite (or MySQL/PostgreSQL)

---

## Setup Instructions

### 1. Clone the repository

```bash
git clone <repository-url>
cd wishlist
```

### 2. Install dependencies

```bash
composer install
npm install
```

### 3. Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database setup

For SQLite:

```bash
touch database/database.sqlite
```

Run migrations and seed data:

```bash
php artisan migrate
php artisan db:seed
```

### 5. Build frontend assets

```bash
npm run build
```

### 6. Run the application

```bash
php artisan serve
```

The app will be available at `http://localhost:8000`.

---

## Database Overview

### products

* `id`
* `name`
* `price` (decimal)
* `description`
* timestamps

### wishlists

* `id`
* `user_id`
* `product_id`
* timestamps
* Unique constraint on `(user_id, product_id)`

The `wishlists` table acts as a join table linking users to products they have wishlisted.

---

## API Overview

All API endpoints are prefixed with `/api/v1`.

Authentication for protected endpoints is handled using **Laravel Sanctum (session-based)**.

Responses are returned as JSON with a consistent envelope containing a status indicator and the primary data payload.

---

## API Endpoints

### Products

#### List Products

```
GET /api/v1/products
```

* Returns all available products (newest first)
* Authentication: **Not required**

---

### Wishlist

*All wishlist endpoints require authentication.*

#### Get User Wishlist

```
GET /api/v1/wishlist
```

* Returns products in the authenticated user’s wishlist
* Authentication: **Required**

---

#### Add Product to Wishlist

```
POST /api/v1/wishlist/{product_id}
```

* Adds a product to the authenticated user’s wishlist

Possible responses:

* `201 Created`
* `401 Unauthorized`
* `404 Not Found`
* `409 Conflict` (already wishlisted)

---

#### Remove Product from Wishlist

```
DELETE /api/v1/wishlist/{product_id}
```

* Removes a product from the authenticated user’s wishlist
* Operation is idempotent

Possible responses:

* `200 OK`
* `401 Unauthorized`
* `404 Not Found`

---

## Testing

The project uses **Pest** for feature testing.

### Run all tests

```bash
php artisan test
```

### Run specific tests

```bash
php artisan test tests/Feature/Api/V1/ProductControllerTest.php
php artisan test tests/Feature/Api/V1/WishlistControllerTest.php
```

### Covered scenarios

* Product listing and empty state
* Wishlist add / list / remove flows
* Duplicate wishlist prevention
* Authentication enforcement
* User data isolation

---

## Project Structure

```
app/
 ├── Http/Controllers/Api/V1
 │   ├── ProductController.php
 │   └── WishlistController.php
 ├── Models
 │   ├── Product.php
 │   ├── User.php
 │   └── Wishlist.php

database/
 ├── factories
 ├── migrations
 └── seeders

resources/js/
 ├── components
 │   ├── Products.tsx
 │   └── Wishlist.tsx
 └── pages
     └── Welcome.tsx

routes/
 ├── api.php
 └── web.php

tests/
 └── Feature/Api/V1
```

---

## Notes on Design Choices

* Laravel’s built-in authentication was used to provide a secure, minimal auth system without reimplementing framework primitives.
* Wishlist functionality is modeled as a join between users and products, with database-level constraints to enforce uniqueness.
