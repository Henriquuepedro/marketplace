<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Store Routes
|--------------------------------------------------------------------------
*/
Route::get('/', 'Store\HomeController@home');

// Auth routes
Route::post('/login', 'Auth\AuthController@handleLogin');
Route::any('/logout', 'Auth\AuthController@handleLogout');
Route::get('/esqueci-a-senha', 'Auth\AuthController@showForgotPage');
Route::post('/esqueci-a-senha', 'Auth\AuthController@handleForgotPass');
Route::get('/redefinir-senha', 'Auth\AuthController@showResetPage');
Route::post('/redefinir-senha', 'Auth\AuthController@resetPassword');

// Users routes and resource
Route::get('/entrar', 'Auth\UserController@showLoginPage');
Route::get('/cadastro', 'Auth\UserController@create');
Route::get('/users/validate', 'Auth\UserController@validateMail');

Route::resource('/users', 'Auth\UserController');

// Feedback
Route::get('/cadastro/ok', 'Store\HomeController@registerFeedback');
Route::get('/cadastro/validado', 'Store\HomeController@validationFeedback');
Route::get('/cadastro/falha', 'Store\HomeController@errorFeedback');

// Store
Route::get('/criar-loja', 'Store\StoreController@create');
// Store index
Route::get('/lojas', 'Store\StoreController@index');
// Store view
Route::get('/lojas/{slug}', 'Store\StoreController@showPage');
Route::resource('/store', 'Store\StoreController');

// Categories
//Route::get('/categoria/{level0}', 'Store\CategoriesController@categoryHome');
//Route::get('/categoria/{level0}/{level1?}/{level2?}', 'Store\CategoriesController@categoryPage');
Route::get('/categoria/{level0}', 'Store\CategoriesController@categoryHome');
Route::get('/categoria/{level0}/{level1?}/{level2?}', 'Store\CategoriesController@subcategoryPage');
Route::get('/categories/{parent?}', 'Store\CategoriesController@index');

// Products
Route::get('/products', 'Store\ProductsController@index');

// Questions & Store Ratings
Route::get('/questions', 'Store\QuestionsController@index');
Route::get('/avalie', 'Store\RatingsController@index');

// Pages
Route::get('/pg/{slug}', 'Store\PagesController@showPage');

// Contact Us
Route::get('/fale-conosco', 'Store\ContactController@create');
Route::post('/fale-conosco', 'Store\ContactController@store');
// Feedback
Route::get('/fale-conosco/sucesso', 'Store\ContactController@feedback');

// Cart
Route::get('/carts/count', 'Store\CartController@showCount');
Route::get('/carrinho', 'Store\CartController@index');
Route::resource('/carts', 'Store\CartController');

// Shipping & Checkout
Route::get('/shipping', 'Store\ShippingController@calculate');
//Route::get('/checkout', 'Store\CheckoutController@checkout');
//Route::post('/checkout', 'Store\CheckoutController@processCheckout');
//Route::get('/checkout/completed', 'Store\CheckoutController@checkoutCompleted');

// Search & New Products
Route::get('/busca', 'Store\ProductsController@search');
Route::get('/novidades', 'Store\ProductsController@newProducts');

/*
|--------------------------------------------------------------------------
| Upload
|--------------------------------------------------------------------------
*/
Route::post('/upload', 'MediaController@upload');
Route::post('/upload/{id}/delete', 'MediaController@delete');

/*
|--------------------------------------------------------------------------
| User authenticated routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function()
{
    // User account
    Route::get('/minha-conta', 'Auth\UserController@myAccount');
    Route::get('/mudar-senha', 'Auth\UserController@myPassword');
    Route::post('/mudar-senha', 'Auth\UserController@updatePassword');

    // My Orders
    Route::resource('/meus-pedidos', 'Auth\OrdersController');
    //Route::resource('/users', 'Auth\UserController');

    // Addresses resource
    Route::get('/cobranca', 'Auth\AddressesController@index');
    Route::get('/entrega', 'Auth\AddressesController@index');
    Route::resource('/addresses', 'Auth\AddressesController');

    // Wishlist resource
    Route::resource('/wishlist', 'Auth\WishlistController');

    // Checkout
    Route::get('/checkout', 'Store\CheckoutController@checkout');
    Route::post('/checkout', 'Store\CheckoutController@processCheckout');
    Route::get('/checkout/completed', 'Store\CheckoutController@checkoutCompleted');

    // Questions
    Route::post('/questions', 'Store\QuestionsController@store');

    // Ratings
    Route::resource('/store-ratings', 'Store\RatingsController');
});

/*
|--------------------------------------------------------------------------
| Store Administration
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function()
{
    // Store resource
    //Route::get('/minha-loja/config', 'Admin\StoreController@showConfig');
    //Route::post('/minha-loja/config', 'Admin\StoreController@setImage');
    Route::get('/minha-loja/bank', 'Admin\StoreController@showBankPage');
    Route::post('/minha-loja/bank', 'Admin\StoreController@saveBank');
    Route::resource('/minha-loja', 'Admin\StoreController');

    // Products resource
    Route::get('/esgotados', 'Admin\ProductsController@soldOut');
    Route::resource('/produtos', 'Admin\ProductsController');

    // Orders resource
    Route::resource('/pedidos', 'Admin\OrdersController');

    // Sales resource
    Route::get('/sales', 'Admin\SalesController@chartData');
    Route::resource('/vendas', 'Admin\SalesController');

    // Product Variations & Options
    Route::resource('/variacoes', 'Admin\VariationsController');
    Route::resource('/var-options', 'Admin\VariationOptionsController');

    // Promotions & Coupons
    Route::resource('/promocao', 'Admin\PromotionController');
    Route::resource('/cupons', 'Admin\CouponsController');

    // Inventory
    Route::resource('/estoque', 'Admin\InventoryController');

    // Prices
    Route::resource('/precos', 'Admin\PricesController');

    // Questions & Reputation
    Route::resource('/perguntas', 'Admin\QuestionsController');
    Route::resource('/reputacao', 'Admin\ReputationController');
});

/*
|--------------------------------------------------------------------------
| Master Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function()
{
    // Dashboard
    Route::get('/dashboard', 'Master\DashboardController@index');

    // Stores resource
    Route::post('/stores/status', 'Master\StoresController@changeStatus');
    Route::resource('/stores', 'Master\StoresController');

    // Products resource
    Route::resource('/products', 'Master\ProductsController');

    // Pages resource
    Route::resource('/pages', 'Master\PagesController');

    // Menus resource
    Route::resource('/menus', 'Master\MenusController');
    Route::resource('/menus-items', 'Master\MenuItemsController');

    // Slugify
    Route::get('/slugify', 'Master\MainController@slugify');
});

/*
|--------------------------------------------------------------------------
| Root Slug Routes
|--------------------------------------------------------------------------
*/
Route::get('/{slug}', 'Store\ProductsController@show');

