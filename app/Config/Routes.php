<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Root Redirect to Dashboard or Login
$routes->get('/', static function() {
    if (session()->get('isLoggedIn')) {
        return redirect()->to('/dashboard');
    }
    return redirect()->to('/login');
});

// Authentication (Public)
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::attemptLogin');
$routes->get('/logout', 'Auth::logout');

// Public Order Tracking (No Auth Required)
$routes->get('/track', 'TrackController::index');
$routes->post('/track/check', 'TrackController::check');
$routes->get('/track/check', 'TrackController::index');

// Protected Backend Routes (Requires Auth Filter)
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    // User Profile
    $routes->get('/profile', 'Auth::profile');
    $routes->post('/profile/change-password', 'Auth::changePassword');

    // Dashboard
    $routes->get('/dashboard', 'Dashboard::index');

    // Pelanggan (Customer Management)
    $routes->get('/customers', 'CustomerController::index');
    $routes->get('/customers/create', 'CustomerController::create');
    $routes->post('/customers/store', 'CustomerController::store');
    $routes->get('/customers/edit/(:num)', 'CustomerController::edit/$1');
    $routes->post('/customers/update/(:num)', 'CustomerController::update/$1');
    $routes->post('/customers/delete/(:num)', 'CustomerController::delete/$1');

    // Layanan & Tarif (Service Management)
    $routes->get('/services', 'ServiceController::index');
    $routes->post('/services/store', 'ServiceController::store');
    $routes->post('/services/import-basant', 'ServiceController::importBasant');
    $routes->get('/services/edit/(:num)', 'ServiceController::edit/$1');
    $routes->post('/services/update/(:num)', 'ServiceController::update/$1');
    $routes->post('/services/delete/(:num)', 'ServiceController::delete/$1');

    // Transaksi (Order Management)
    $routes->get('/transactions', 'TransactionController::index');
    $routes->get('/transactions/create', 'TransactionController::create');
    $routes->post('/transactions/store', 'TransactionController::store');
    $routes->get('/transactions/detail/(:num)', 'TransactionController::detail/$1');
    $routes->post('/transactions/updateStatus/(:num)', 'TransactionController::updateStatus/$1');
    $routes->post('/transactions/pay/(:num)', 'TransactionController::pay/$1');
    $routes->post('/transactions/delete/(:num)', 'TransactionController::delete/$1');
    $routes->get('/transactions/print/(:num)', 'TransactionController::print/$1');

    // Laporan (Reporting)
    $routes->get('/reports/revenue', 'ReportController::revenue');
    $routes->get('/reports/services', 'ReportController::byService');
});
