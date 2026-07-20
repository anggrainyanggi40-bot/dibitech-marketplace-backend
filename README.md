# DibiTech Backend API

DibiTech Backend is a REST API for the DibiTech digital product marketplace.

The API handles authentication, users, products, categories, shopping carts, orders, seller management, and payment processing.

## Features

### Authentication

- User registration
- User login
- User logout
- Token-based authentication using Laravel Sanctum

### Products

- Get all products
- Get product details
- Create products
- Update products
- Delete products
- Seller product management

### Categories

- Get product categories
- Create categories
- Update categories
- Delete categories

### Cart

- Add products to cart
- View cart
- Remove products from cart
- Checkout cart

### Orders

- Create orders
- View orders
- View order details
- Cancel pending orders

### Payments

- Create Midtrans Snap transactions
- Process Midtrans webhook notifications
- Update payment status
- Update order status after successful payment

### User Management

- View users
- View user details
- Delete users
- Role-based access control

## User Roles

The API supports:

- `buyer`
- `seller`
- `admin`

## Tech Stack

- PHP
- Laravel
- Laravel Sanctum
- MySQL
- Midtrans Snap
- REST API

## Installation

Clone the repository:

```bash
git clone https://github.com/anggrainyanggi40-bot/dibitech-marketplace-backend.git
```

Install dependencies:

```bash
composer install
```

Create the environment file:

```bash
cp .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

Configure the database in `.env`:

```env
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Configure Midtrans:

```env
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false
```

Run database migrations:

```bash
php artisan migrate
```

Start the Laravel development server:

```bash
php artisan serve
```

## API Authentication

Protected endpoints use Laravel Sanctum.

Authenticated requests must include:

```text
Authorization: Bearer YOUR_TOKEN
```

## Payment Integration

The API integrates with Midtrans Snap for payment processing.

Payment status is updated through Midtrans webhook notifications after a transaction status changes.

## Current Limitations

- Digital product file upload and storage are not yet implemented.
- Secure product downloads after purchase are planned for future development.

## Author

Dwi Pangestu Anggrainy
