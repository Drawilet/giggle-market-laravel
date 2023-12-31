<div style="display: flex; align-items: center;">
    <img src="./public/icon.svg" style="width: 180px; margin-right: 50px">
  <h1>Giggle Market</h1>
</div>

Giggle Market is an online marketplace application developed using Laravel, Livewire, and PHP. It provides a platform for sellers to list their products and for customers to browse, shop, and make purchases.

<div style=" background-color: #f44336; color: white;  padding: 5px; padding-top:15px;  text-align: center;   font-size: 16px; font-weight: bold; border-radius: 5px;">
  <p><strong>Warning:</strong> This project is currently under development and is not recommended for production use.</p>
</div>

## Features

### Seller View

-   **CRUD Operations**: Sellers can manage taxes, categories, products, and users.
-   **Sales Tracking**: Sellers can view their sales.

### Customer View

-   Customers can browse all sellers' products.
-   They can add products to their cart and modify quantities.
-   Checkout displays the final price and allows purchases.
-   Customers can view their purchase history.

## Installation

1. Clone the repository: `git clone https://github.com/Drawilet/giggle-market-laravel`
2. Change to the project directory: `cd giggle-market`
3. Install dependencies using Yarn: `yarn install`
4. Set up the Laravel environment and database.
5. Run migrations and seed the database: `php artisan migrate --seed`
6. Start the development server: `php artisan serve`
7. Start vite: `npm run dev`
8. Start the websocket server: `php artisan websockets:serve`
9. Access Giggle Market at `http://localhost:8000` in your web browser.

## Usage

### Seller Account

-   Create or log in to your seller account.
-   Manage products, categories, taxes, and track sales.

### Customer Account

-   Browse products from various sellers.
-   Add products to your cart and proceed to checkout.
-   Complete purchases and view your purchase history.

## Contributing

If you'd like to contribute to Giggle Market, please follow our [contribution guidelines](CONTRIBUTING.md).

## License

This project is open-source and available under the [MIT License](LICENSE).
