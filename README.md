# E-Commerce Website Laravel

Website e-commerce lengkap yang dibangun menggunakan Laravel, MySQL, dan Bootstrap dengan tema hijau/orange yang modern dan responsif.

## 🚀 Fitur Utama

### Fitur Customer

-   **Homepage** - Hero section dengan featured products
-   **Product Catalog** - Listing produk dengan filter dan search
-   **Product Detail** - Informasi lengkap produk dengan stok
-   **Shopping Cart** - Keranjang belanja (dalam pengembangan)
-   **User Authentication** - Login/Register system
-   **About Us** - Halaman tentang perusahaan
-   **Contact** - Form kontak dengan FAQ

### Fitur Admin

-   **Dashboard** - Statistik dan overview bisnis
-   **Product Management** - CRUD produk dengan kategori
-   **Order Management** - Kelola pesanan (dalam pengembangan)
-   **Reports** - Laporan penjualan (dalam pengembangan)
-   **User Management** - Role-based access control

## 🛠 Teknologi yang Digunakan

-   **Backend:** Laravel 10.x
-   **Database:** MySQL 8.0
-   **Frontend:** Bootstrap 5.3, Custom CSS
-   **Authentication:** Laravel Breeze
-   **Icons:** Bootstrap Icons
-   **Server:** PHP 8.1

## 📦 Instalasi

### Prerequisites

-   PHP 8.1 atau lebih tinggi
-   Composer
-   MySQL 8.0
-   Node.js & NPM

### Langkah Instalasi

1. **Clone Repository**

    ```bash
    git clone <repository-url>
    cd ecommerce-app
    ```

2. **Install Dependencies**

    ```bash
    composer install
    npm install
    ```

3. **Environment Setup**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Database Configuration**
   Edit file `.env`:

    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=ecommerce_db
    DB_USERNAME=root
    DB_PASSWORD=
    ```

5. **Database Migration & Seeding**

    ```bash
    php artisan migrate
    php artisan db:seed --class=AdminUserSeeder
    php artisan db:seed --class=CategorySeeder
    php artisan db:seed --class=ProductSeeder
    ```

6. **Build Assets**

    ```bash
    npm run build
    ```

7. **Start Development Server**
    ```bash
    php artisan serve
    ```

## 👤 Default Users

### Admin Account

-   **Email:** admin@ecommerce.com
-   **Password:** password
-   **Role:** admin

### Customer Account

-   **Email:** customer@test.com
-   **Password:** password
-   **Role:** customer

## 🗄 Database Schema

### Users Table

-   id, name, email, password, role, phone, address, timestamps

### Categories Table

-   id, name, description, is_active, timestamps

### Products Table

-   id, category_id, name, description, price, stock, sku, image, is_active, weight, timestamps

### Orders Table

-   id, user_id, order_number, total_amount, status, payment_status, shipping_address, timestamps

### Order Items Table

-   id, order_id, product_id, quantity, price, timestamps

### Cart Table

-   id, user_id, product_id, quantity, timestamps

## 🎨 Design System

### Color Palette

-   **Primary:** #28a745 (Green)
-   **Secondary:** #fd7e14 (Orange)
-   **Success:** #28a745
-   **Warning:** #ffc107
-   **Danger:** #dc3545
-   **Info:** #17a2b8

### Typography

-   **Font Family:** System fonts (San Francisco, Segoe UI, Roboto)
-   **Headings:** Bold weights
-   **Body:** Regular weight

### Components

-   Cards dengan shadow-sm
-   Buttons dengan hover effects
-   Form controls dengan validation states
-   Navigation dengan active states

## 📱 Responsive Design

Website dioptimalkan untuk:

-   **Desktop:** 1200px+
-   **Tablet:** 768px - 1199px
-   **Mobile:** 320px - 767px

## 🔐 Security Features

-   CSRF Protection
-   SQL Injection Prevention
-   XSS Protection
-   Role-based Access Control
-   Password Hashing
-   Session Management

## 📊 Sample Data

### Categories

1. Elektronik
2. Fashion
3. Rumah Tangga
4. Olahraga

### Products

1. Smartphone Android - Rp 2.500.000 (50 stok)
2. Laptop Gaming - Rp 15.000.000 (20 stok)
3. Kaos Polo - Rp 150.000 (100 stok)
4. Sepatu Sneakers - Rp 500.000 (75 stok)
5. Rice Cooker - Rp 800.000 (30 stok)
6. Matras Yoga - Rp 200.000 (40 stok)

## 🚧 Development Roadmap

### Phase 1 ✅ Completed

-   Laravel setup dan database
-   Authentication system
-   Basic CRUD operations
-   Frontend dengan Bootstrap
-   Admin dashboard

### Phase 2 🔄 In Progress

-   Shopping cart functionality
-   Checkout process
-   Payment gateway integration (BNI, QRIS, BCA)
-   Order management
-   Email notifications

### Phase 3 📋 Planned

-   Advanced reporting
-   Inventory management
-   Customer reviews
-   Wishlist functionality
-   SEO optimization

## 🧪 Testing

### Manual Testing Completed

-   ✅ Homepage navigation
-   ✅ Product catalog
-   ✅ User authentication
-   ✅ Admin dashboard
-   ✅ Product management
-   ✅ Responsive design

### Test Accounts

Gunakan credentials di atas untuk testing fitur admin dan customer.

## 📝 API Documentation

### Product Endpoints

-   `GET /api/products` - List all products
-   `GET /api/products/{id}` - Get product detail
-   `POST /api/products` - Create product (admin only)
-   `PUT /api/products/{id}` - Update product (admin only)
-   `DELETE /api/products/{id}` - Delete product (admin only)

### Category Endpoints

-   `GET /api/categories` - List all categories
-   `GET /api/categories/{id}` - Get category detail

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📄 License

This project is licensed under the MIT License.

## 📞 Support

Untuk support dan pertanyaan:

-   Email: support@ecommerce.com
-   Phone: +62 851-7310-2302
-   WhatsApp: +62 812 3456 7890

## 🙏 Acknowledgments

-   Laravel Framework
-   Bootstrap CSS Framework
-   Bootstrap Icons
-   MySQL Database
-   PHP Community

---

**Developed by Manus AI** | Version 1.0 | 2025
