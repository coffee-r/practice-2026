<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/posts', 'Post::index', ['filter' => 'session']);
$routes->post('/posts', 'Post::create', ['filter' => 'session']);
$routes->delete('/posts/(:segment)', 'Post::destroy/$1', ['filter' => 'session']);

service('auth')->routes($routes);
