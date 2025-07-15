<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// 🏠 Halaman Utama
$routes->get('/', 'Home::index');

$routes->get('/news/(:segment)', 'NewsController::detail/$1');
$routes->post('/comments/add', 'CommentController::add'); // untuk HTMX
$routes->get('/comments/list/(:num)', 'CommentController::list/$1'); // untuk load komentar

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
$routes->group('admin', ['filter' => 'isAdmin'], function($routes) {
    $routes->get('/', 'Admin::index');
    
});


