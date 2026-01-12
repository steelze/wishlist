# Wishlist Application

A Laravel application that allows users to browse products and manage their personal wishlists.

## Tech Stack

- **Backend:** PHP 8.2+, Laravel 12
- **Frontend:** React 19, Inertia.js v2, Tailwind CSS v4
- **Database:** SQLite (default) / MySQL / PostgreSQL
- **Authentication:** Laravel Fortify, Laravel Sanctum
- **Testing:** Pest v4

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- SQLite (or MySQL/PostgreSQL)

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

### 3. Environment configuration

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database setup

Create the SQLite database file (if using SQLite):

```bash
touch database/database.sqlite
```

Run migrations and seed the database:

```bash
php artisan migrate
php artisan db:seed
```

### 5. Build frontend assets

```bash
npm run build
```

### 6. Start the development server

```bash
composer run dev
```

Or manually:

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## Database Migrations

The application includes the following key migrations:

| Migration | Description |
|-----------|-------------|
| `create_users_table` | User accounts with authentication fields |
| `create_products_table` | Products with name, price, and description |
| `create_wishlists_table` | Pivot table linking users to their wishlisted products |
| `create_personal_access_tokens_table` | API tokens for Sanctum authentication |

### Database Schema

**products**
| Column | Type | Description |
|--------|------|-------------|
| id | integer | Primary key |
| name | string | Product name |
| price | decimal(10,2) | Product price |
| description | text | Product description |
| created_at | datetime | Timestamp |
| updated_at | datetime | Timestamp |

**wishlists**
| Column | Type | Description |
|--------|------|-------------|
| id | integer | Primary key |
| user_id | integer | Foreign key to users |
| product_id | integer | Foreign key to products |
| created_at | datetime | Timestamp |
| updated_at | datetime | Timestamp |

- Unique constraint on `(user_id, product_id)` to prevent duplicates
- Cascade delete when user or product is deleted

## API Documentation

All API endpoints are prefixed with `/api/v1`. Authentication is handled via Laravel Sanctum (cookie-based for SPA).

### Response Format

All responses follow a consistent structure:

**Success Response:**
```json
{
  "status": "success",
  "message": "Successful",
  "data": [],
  "meta": []
}
```

**Error Response:**
```json
{
  "status": "error",
  "message": "Error message",
  "errors": [],
  "meta": []
}
```

### Endpoints

#### Products

##### List All Products

```
GET /api/v1/products
```

Returns all products sorted by newest first.

**Authentication:** Not required

**Response:** `200 OK`
```json
{
  "status": "success",
  "message": "Successful",
  "data": [
    {
      "id": 1,
      "name": "Product Name",
      "price": "29.99",
      "description": "Product description",
      "created_at": "2024-01-11T14:21:23.000000Z",
      "updated_at": "2024-01-11T14:21:23.000000Z"
    }
  ],
  "meta": []
}
```

---

#### Wishlist

All wishlist endpoints require authentication.

##### Get User's Wishlist

```
GET /api/v1/wishlist
```

Returns all products in the authenticated user's wishlist.

**Authentication:** Required (Sanctum)

**Response:** `200 OK`
```json
{
  "status": "success",
  "message": "Successful",
  "data": [
    {
      "id": 1,
      "name": "Product Name",
      "price": "29.99",
      "description": "Product description",
      "created_at": "2024-01-11T14:21:23.000000Z",
      "updated_at": "2024-01-11T14:21:23.000000Z"
    }
  ],
  "meta": []
}
```

**Error Response:** `401 Unauthorized` (if not authenticated)

---

##### Add Product to Wishlist

```
POST /api/v1/wishlist/{product_id}
```

Adds a product to the authenticated user's wishlist.

**Authentication:** Required (Sanctum)

**Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| product_id | integer | The ID of the product to add |

**Response:** `201 Created`
```json
{
  "status": "success",
  "message": "Product added to wishlist",
  "data": [],
  "meta": []
}
```

**Error Responses:**
- `401 Unauthorized` - User not authenticated
- `404 Not Found` - Product does not exist
- `409 Conflict` - Product already in wishlist

```json
{
  "status": "error",
  "message": "Product already in wishlist",
  "errors": [],
  "meta": []
}
```

---

##### Remove Product from Wishlist

```
DELETE /api/v1/wishlist/{product_id}
```

Removes a product from the authenticated user's wishlist.

**Authentication:** Required (Sanctum)

**Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| product_id | integer | The ID of the product to remove |

**Response:** `200 OK`
```json
{
  "status": "success",
  "message": "Product removed from wishlist",
  "data": [],
  "meta": []
}
```

**Error Responses:**
- `401 Unauthorized` - User not authenticated
- `404 Not Found` - Product does not exist

---

### Authentication Endpoints

The application uses Laravel Fortify for authentication:

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/login` | Login page |
| POST | `/login` | Authenticate user |
| POST | `/logout` | Logout user |
| GET | `/register` | Registration page |
| POST | `/register` | Create new user |
| GET | `/forgot-password` | Password reset request page |
| POST | `/forgot-password` | Send password reset email |
| GET | `/reset-password/{token}` | Password reset page |
| POST | `/reset-password` | Reset password |

## Testing Instructions

The application uses Pest for testing.

### Run all tests

```bash
php artisan test
```

Or with compact output:

```bash
php artisan test --compact
```

### Run specific test files

```bash
# API tests
php artisan test tests/Feature/Api/V1/ProductControllerTest.php
php artisan test tests/Feature/Api/V1/WishlistControllerTest.php

# Authentication tests
php artisan test tests/Feature/Auth/
```

### Run tests with filter

```bash
php artisan test --filter="adds a product to the wishlist"
```

### Test Coverage

The API tests cover:

**ProductController:**
- Listing all products
- Empty product list handling

**WishlistController:**
- Adding products to wishlist
- Preventing duplicate wishlist entries (409 Conflict)
- Listing user's wishlist
- Removing products from wishlist
- Authentication requirements for all endpoints
- User isolation (users can only see their own wishlist)

## Project Structure

```
├── app/
│   ├── Helpers/
│   │   └── RespondWith.php          # API response helper
│   ├── Http/Controllers/
│   │   └── Api/V1/
│   │       ├── ProductController.php
│   │       └── WishlistController.php
│   └── Models/
│       ├── Product.php
│       ├── User.php
│       └── Wishlist.php
├── database/
│   ├── factories/
│   │   └── ProductFactory.php
│   ├── migrations/
│   │   ├── create_products_table.php
│   │   └── create_wishlists_table.php
│   └── seeders/
│       └── ProductSeeder.php
├── resources/js/
│   ├── components/
│   │   ├── product.tsx              # Product listing component
│   │   └── wishlist.tsx             # Wishlist component
│   └── pages/
│       └── welcome.tsx              # Main page
├── routes/
│   ├── api.php                      # API routes
│   └── web.php                      # Web routes
└── tests/
    └── Feature/Api/V1/
        ├── ProductControllerTest.php
        └── WishlistControllerTest.php
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
