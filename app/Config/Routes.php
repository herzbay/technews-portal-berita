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
// 🗨️ Comments
// =======================
$routes->group('comments', function($routes) {
    $routes->get('list/(:num)', 'CommentController::list/$1');
    $routes->post('add/(:num)', 'CommentController::add/$1');
});

// =======================
// ❤️ Like & Share
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
$routes->get('/register', 'AuthController::register');
$routes->post('/register', 'AuthController::attemptRegister');
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::attemptLogin');
$routes->get('/logout', 'AuthController::logout');

// =======================
// 🔐 Admin
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

    // Kelola User
    $routes->get('users', 'Admin\UserController::index');
    $routes->get('users/create', 'Admin\UserController::create');
    $routes->post('users/store', 'Admin\UserController::store');
    $routes->get('users/delete/(:num)', 'Admin\UserController::delete/$1');
    $routes->get('users/edit/(:num)', 'Admin\UserController::edit/$1');
    $routes->post('users/update/(:num)', 'Admin\UserController::update/$1');
    $routes->get('users/delete/(:num)', 'Admin\UserController::delete/$1');
});
