<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// =======================
// 🏠 Halaman Utama
// =======================
$routes->get('/', 'Home::index');

// =======================
// 🔐 Autentikasi Manual
// =======================
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::login');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::register');
$routes->get('logout', 'Auth::logout');

// =======================
// 🔐 Login via Google
// =======================
$routes->get('auth/googleLogin', 'Auth::googleLogin');
$routes->get('auth/googleCallback', 'Auth::googleCallback');

// =======================
// 🛠️ Admin (bisa dikembangkan)
// =======================
$routes->group('admin', function ($routes) {
    $routes->get('/', 'Admin::index');
    // $routes->add('news', 'AdminNews::index'); // contoh
});
