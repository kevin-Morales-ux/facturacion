<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// 1. Rutas Públicas (Login)
$routes->get('login', 'AuthController::index');
$routes->post('login/authenticate', 'AuthController::authenticate');
$routes->get('logout', 'AuthController::logout');

// 2. Rutas Protegidas que devuelven Vistas HTML (Solo requieren Login)
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Home::index');
    $routes->get('facturacion', 'Home::index');
    
    // Vista principal de categorías (HTML)
    $routes->get('categorias', 'CategoriasController::index');
});

// 3. Rutas de la API / Endpoints (Requieren Login Y Petición AJAX)
$routes->group('categorias', ['filter' => ['auth', 'ajax']], function($routes) {
    $routes->get('getCategorias', 'CategoriasController::getCategorias');
    $routes->post('guardar', 'CategoriasController::guardar');
    $routes->get('obtener/(:num)', 'CategoriasController::obtener/$1');
    $routes->delete('eliminar/(:num)', 'CategoriasController::eliminar/$1');
});
