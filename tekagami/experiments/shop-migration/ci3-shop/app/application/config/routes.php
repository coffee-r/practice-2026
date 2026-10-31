<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'api/shop/health';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['api/health']['GET'] = 'api/shop/health';
$route['api/test/reset']['POST'] = 'api/shop/reset';
$route['api/cart/items']['POST'] = 'api/shop/add_cart_item';
$route['api/cart']['GET'] = 'api/shop/cart';
$route['api/checkout/quote']['POST'] = 'api/shop/checkout_quote';
$route['api/orders']['POST'] = 'api/shop/create_order';
$route['api/orders/(:num)/cancel']['POST'] = 'api/shop/cancel_order/$1';
$route['api/payments/credit/callback']['POST'] = 'api/shop/credit_callback';
