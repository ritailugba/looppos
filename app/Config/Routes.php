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

// API routes for AJAX calls
$routes->post('/api/products/by-category', 'Dashboard::getProductsByCategory');
$routes->post('/api/products/search', 'Dashboard::searchProducts');
$routes->post('/api/products/by-code', 'Dashboard::getProductByCode');
