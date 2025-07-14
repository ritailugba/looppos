<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Authentication routes
$routes->get('/auth/login', 'Auth::login');
$routes->post('/auth/login', 'Auth::login');
$routes->get('/auth/logout', 'Auth::logout');
$routes->get('/auth/register', 'Auth::register');
$routes->post('/auth/register', 'Auth::register');
$routes->get('/auth/forgot-password', 'Auth::forgotPassword');
$routes->post('/auth/forgot-password', 'Auth::forgotPassword');

// Main application routes
$routes->get('/', 'Dashboard::index');
$routes->get('/dashboard', 'Dashboard::index');
$routes->get('/change-language/(:segment)', 'Dashboard::changeLanguage/$1');

// Products routes
$routes->group('products', function($routes) {
    $routes->get('/', 'Products::index');
    $routes->get('add', 'Products::add');
    $routes->post('add', 'Products::add');
    $routes->get('edit/(:num)', 'Products::edit/$1');
    $routes->post('edit/(:num)', 'Products::edit/$1');
    $routes->get('delete/(:num)', 'Products::delete/$1');
    $routes->get('csv', 'Products::csv');
    $routes->post('import-csv', 'Products::importCsv');
    $routes->get('find/(:segment)', 'Products::findByCode/$1');
});

// Categories routes
$routes->group('categories', function($routes) {
    $routes->get('/', 'Categories::index');
    $routes->get('add', 'Categories::add');
    $routes->post('add', 'Categories::add');
    $routes->get('edit/(:num)', 'Categories::edit/$1');
    $routes->post('edit/(:num)', 'Categories::edit/$1');
    $routes->get('delete/(:num)', 'Categories::delete/$1');
});

// Customers routes
$routes->group('customers', function($routes) {
    $routes->get('/', 'Customers::index');
    $routes->get('add', 'Customers::add');
    $routes->post('add', 'Customers::add');
    $routes->get('edit/(:num)', 'Customers::edit/$1');
    $routes->post('edit/(:num)', 'Customers::edit/$1');
    $routes->get('view/(:num)', 'Customers::view/$1');
    $routes->get('delete/(:num)', 'Customers::delete/$1');
});

// Sales routes
$routes->group('sales', function($routes) {
    $routes->get('/', 'Sales::index');
    $routes->get('view/(:num)', 'Sales::view/$1');
    $routes->get('delete/(:num)', 'Sales::delete/$1');
    $routes->get('receipt/(:num)', 'Sales::receipt/$1');
    $routes->get('daily', 'Sales::dailySales');
    $routes->get('export', 'Sales::export');
});

// Reports routes
$routes->group('reports', function($routes) {
    $routes->get('/', 'Reports::index');
    $routes->get('sales', 'Reports::salesReport');
    $routes->get('products', 'Reports::productReport');
    $routes->get('customers', 'Reports::customerReport');
    $routes->get('inventory', 'Reports::inventoryReport');
    $routes->get('export-sales', 'Reports::exportSalesReport');
});

// Settings routes
$routes->group('settings', function($routes) {
    $routes->get('/', 'Settings::index');
    $routes->get('users', 'Settings::users');
    $routes->get('users/add', 'Settings::addUser');
    $routes->post('users/add', 'Settings::addUser');
    $routes->get('users/edit/(:num)', 'Settings::editUser/$1');
    $routes->post('users/edit/(:num)', 'Settings::editUser/$1');
    $routes->get('users/delete/(:num)', 'Settings::deleteUser/$1');
    $routes->get('profile', 'Settings::profile');
    $routes->post('profile', 'Settings::profile');
    $routes->get('system', 'Settings::system');
    $routes->post('system', 'Settings::system');
    $routes->get('backup', 'Settings::backup');
    $routes->post('backup', 'Settings::backup');
});

// API routes for AJAX calls
$routes->post('/api/products/by-category', 'Dashboard::getProductsByCategory');
$routes->post('/api/products/search', 'Dashboard::searchProducts');
$routes->post('/api/products/by-code', 'Dashboard::getProductByCode');
