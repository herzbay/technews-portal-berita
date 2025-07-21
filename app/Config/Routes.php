<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// =======================
// 🌐 Frontend Routes
// =======================
$routes->get('/', 'Home::index');
$routes->get('/news/(:segment)', 'NewsController::detail/$1');

// =======================
// 🗨️ Komentar (HTMX Support) - CSRF sudah dikecualikan di global
// =======================
$routes->get('comments/list/(:num)', 'CommentController::list/$1');
$routes->post('comments/add/(:num)', 'CommentController::add/$1');
$routes->delete('comments/delete/(:num)', 'CommentController::delete/$1');

// =======================
// ❤️ Like & Share - CSRF sudah dikecualikan di global  
// =======================
$routes->post('like/(:num)', 'ActionController::like/$1');
$routes->post('share/(:num)', 'ActionController::share/$1');

// =======================
// 🏆 Leaderboard
// =======================
$routes->get('/leaderboard', 'LeaderboardController::index');

// =======================
// 🔐 Auth
// =======================
$routes->group('', ['filter' => 'guest'], function ($routes) {
    $routes->get('/register', 'AuthController::register');
    $routes->post('/register', 'AuthController::attemptRegister');
    $routes->get('/login', 'AuthController::login');
    $routes->post('/login', 'AuthController::attemptLogin');
});
$routes->get('/logout', 'AuthController::logout', ['filter' => 'auth']);

// =======================
// 🔐 Admin Panel
// =======================
$routes->group('admin', ['filter' => 'authAdmin'], function ($routes) {
    $routes->get('/', 'Admin\DashboardController::index');

    // CRUD Berita
    $routes->get('news', 'Admin\NewsController::index');
    $routes->get('news/create', 'Admin\NewsController::create');
    $routes->post('news/store', 'Admin\NewsController::store');
    $routes->get('news/edit/(:num)', 'Admin\NewsController::edit/$1');
    $routes->post('news/update/(:num)', 'Admin\NewsController::update/$1');
    $routes->get('news/delete/(:num)', 'Admin\NewsController::delete/$1');

    // CRUD User
    $routes->get('users', 'Admin\UserController::index');
    $routes->get('users/create', 'Admin\UserController::create');
    $routes->post('users/store', 'Admin\UserController::store');
    $routes->get('users/edit/(:num)', 'Admin\UserController::edit/$1');
    $routes->post('users/update/(:num)', 'Admin\UserController::update/$1');
    $routes->get('users/delete/(:num)', 'Admin\UserController::delete/$1');
});