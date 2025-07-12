<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::doLogin');
$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::doRegister');
$routes->get('/logout', 'Auth::logout');

// Contoh grup (opsional, hanya jika pakai)
$routes->group('admin', function($routes) {
    $routes->get('/', 'Admin::index');
});


