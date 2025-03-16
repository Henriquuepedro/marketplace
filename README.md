# Marketplace Application

This is a PHP-based marketplace application where stores can publish their products and customers can make purchases. The system supports authentication, product categories, user feedback, cart management, and more. Below are the features and routes available in the system.

## Features

- **Store Management**: Create, view, and manage stores and their products.
- **Product Listings**: Customers can browse products by categories and subcategories.
- **Authentication**: Login, registration, and password management for users.
- **User Profile**: Users can manage their account, address details, and wishlist.
- **Cart & Checkout**: Manage the cart and proceed with checkout to make purchases.
- **Shipping**: Calculate shipping costs based on the customer’s address.
- **Orders & Payments**: Manage orders and view purchase history.
- **Ratings & Feedback**: Leave feedback on stores and products.
- **Admin Panel**: Manage stores, products, sales, and users for administrators.
- **Master Admin**: Full access for the platform owner to manage stores and users.

---

## Route Documentation

### Public Routes

- **Home Page**:  
  `GET /` - Displays the homepage of the marketplace.

- **Authentication Routes**:  
  `POST /login` - Handle user login.  
  `ANY /logout` - Logout the user.  
  `GET /esqueci-a-senha` - Display the password reset page.  
  `POST /esqueci-a-senha` - Process the password reset request.  
  `GET /redefinir-senha` - Display the password reset page.  
  `POST /redefinir-senha` - Reset the user’s password.

- **User Routes**:  
  `GET /entrar` - Display the login page.  
  `GET /cadastro` - Display the registration page.  
  `GET /users/validate` - Validate user email.  
  `GET /cadastro/ok` - Registration success feedback.  
  `GET /cadastro/validado` - Registration validation feedback.  
  `GET /cadastro/falha` - Registration failure feedback.

- **Store Routes**:  
  `GET /criar-loja` - Create a new store.  
  `GET /lojas` - List all stores.  
  `GET /lojas/{slug}` - View a specific store.  
  `GET /categoria/{level0}` - View a product category.  
  `GET /categoria/{level0}/{level1?}/{level2?}` - View a subcategory of products.  
  `GET /products` - List all products.  
  `GET /busca` - Search for products.  
  `GET /novidades` - View new products.

- **Contact Us**:  
  `GET /fale-conosco` - Display the contact form.  
  `POST /fale-conosco` - Submit the contact form.  
  `GET /fale-conosco/sucesso` - Success feedback after submitting the contact form.

- **Cart Routes**:  
  `GET /carts/count` - Get the count of items in the cart.  
  `GET /carrinho` - View the cart.  
  `POST /carts` - Add items to the cart.

- **Shipping & Checkout**:  
  `GET /shipping` - Calculate the shipping cost.  
  `GET /checkout` - Proceed to checkout.  
  `POST /checkout` - Process the checkout request.  
  `GET /checkout/completed` - Display the checkout completion page.

---

### Authenticated User Routes

- **User Profile & Account Management**:  
  `GET /minha-conta` - View the user account page.  
  `GET /mudar-senha` - Change the user’s password.  
  `POST /mudar-senha` - Update the user’s password.

- **Orders**:  
  `GET /meus-pedidos` - List all orders of the authenticated user.

- **Addresses**:  
  `GET /cobranca` - View billing addresses.  
  `GET /entrega` - View delivery addresses.  
  `POST /addresses` - Add or update addresses.

- **Wishlist**:  
  `GET /wishlist` - View the user’s wishlist.  
  `POST /wishlist` - Add or remove items from the wishlist.

- **Questions**:  
  `POST /questions` - Submit a question about a product.

- **Ratings**:  
  `POST /store-ratings` - Submit a rating for a store.

---

### Admin Routes

- **Store Management**:  
  `GET /minha-loja/bank` - Manage store bank details.  
  `POST /minha-loja/bank` - Save store bank details.

- **Product Management**:  
  `GET /esgotados` - View sold-out products.  
  `GET /produtos` - List all products in the store.  
  `POST /produtos` - Add or edit products.

- **Orders Management**:  
  `GET /pedidos` - List all orders for the store.

- **Sales & Promotions**:  
  `GET /sales` - View sales data and charts.  
  `GET /vendas` - View sales details.

- **Inventory & Stock**:  
  `GET /estoque` - View store inventory.  
  `POST /estoque` - Update inventory.

- **Coupons & Promotions**:  
  `GET /promocao` - Manage promotions.  
  `GET /cupons` - Manage coupons.

---

### Master Admin Routes

- **Dashboard**:  
  `GET /dashboard` - View the master dashboard.

- **Store & Product Management**:  
  `POST /stores/status` - Change store status.  
  `GET /products` - View all products across stores.

- **Pages & Menus**:  
  `GET /pages` - Manage pages.  
  `GET /menus` - Manage menus and menu items.

- **Slugify**:  
  `GET /slugify` - Generate slugs for URLs.

---


## Installation

Follow these steps to set up the project locally:

1. Clone the repository:
```bash
git clone https://github.com/Henriquuepedro/marketplace.git
```

2. Navigate to the project directory:
```bash
cd marketplace
```

3. Install dependencies using Composer:
```bash
composer install
```

4. Set up the environment variables. Copy the `.env.example` file to `.env` and update the necessary details (such as your database and API keys):
```bash
cp .env.example .env
```

5. Generate the application key:
```bash
php artisan key:generate
```

6. Run migrations to set up the database:
```bash
php artisan migrate
```

7. Run the application:
```bash
php artisan serve
```

The application should now be running at `http://localhost:8000`.

---

## Contributing

Feel free to fork this project and submit pull requests. If you encounter any bugs or have suggestions for improvements, please open an issue on GitHub.

## License
This project is licensed under the MIT License - see the LICENSE file for details.
