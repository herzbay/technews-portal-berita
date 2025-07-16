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
