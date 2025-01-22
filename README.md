# Laravel E-Commerce Website



composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan migrate --seed
php artisan serve




## Web Routes Documentation

### Admin Routes
- `GET /view_category` – Admin view category.
- `POST /add_category` – Admin add category.
- `GET /delete_category/{id}` – Admin delete category by ID.
- `GET /view_product` – Admin view product.
- `POST /add_product` – Admin add product.
- `GET /show_product` – Admin show product.
- `GET /delete_product/{id}` – Admin delete product by ID.
- `GET /edit_product/{id}` – Admin edit product by ID.
- `POST /update_product/{id}` – Admin update product by ID.
- `GET /search-product` – Admin search product.
- `GET /search-order` – Admin search order.
- `GET /user-orders` – Admin user orders.
- `GET /update-order/{user_id}/{order_id}/{delivery_status}` – Admin update order status.
- `GET /print-bill/{order_id}` – Admin print bill for order.
- `GET /customers` – Admin view customers.
- `GET /delete-user/{id}` – Admin delete user by ID.
- `GET /search-user` – Admin search user.

### Coupon Routes
- `GET /view_coupon` – View coupons.
- `POST /add_coupon` – Add a new coupon.
- `GET /delete_coupon/{id}` – Delete a coupon by ID.

### User Routes
- `GET /` – Homepage.
- `GET /home` – User home page (requires authentication).
- `GET /my-account` – User account page.
- `GET /user/logout` – User logout.
- `GET /product_details/{id}` – View product details by ID.
- `GET /shop` – User shop page.
- `GET /contact` – User contact page.
- `POST /add-to-cart/{id}` – Add product to cart.
- `GET /my-cart` – View cart.
- `GET /remove-product-from-cart/{id}` – Remove product from cart by ID.
- `GET /clear-cart` – Clear all items from the cart.
- `GET /checkout` – Checkout page.
- `GET /orders` – View user orders.
- `GET /order-received/{id}` – Mark order as received.
- `GET /cancel-order/{id}` – Cancel order by ID.
- `GET /search-a-product` – Search for a product.
- `GET /update-password` – Update user password.

### Stripe Routes
- `GET /stripe/{totalPrice}` – Stripe payment page.
- `POST /stripe/{totalPrice}` – Process Stripe payment.

### Coupon Apply Route
- `POST /apply-coupon` – Apply a coupon to the cart.
