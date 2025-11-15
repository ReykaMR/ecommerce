<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Auth routes
$route['register'] = 'auth/register';
$route['login'] = 'auth/login';
$route['logout'] = 'auth/logout';
$route['profile'] = 'auth/profile';
$route['update-profile'] = 'auth/update_profile';

// Product routes
$route['products'] = 'home/products';
$route['product/(:num)'] = 'home/product/$1';

// Cart routes
$route['cart'] = 'cart/index';
$route['cart/add'] = 'cart/add';
$route['cart/update'] = 'cart/update_quantity';
$route['cart/remove/(:num)'] = 'cart/remove_item/$1';
$route['cart/clear'] = 'cart/clear';
$route['cart/count'] = 'cart/get_cart_count';

// Checkout routes
$route['checkout'] = 'checkout/index';
$route['checkout/process'] = 'checkout/process';

// Order routes
$route['orders'] = 'orders/index';
$route['orders/view/(:num)'] = 'orders/view/$1';
$route['orders/cancel/(:num)'] = 'orders/cancel/$1';
$route['orders/upload-payment/(:num)'] = 'orders/upload_payment_proof/$1';

// Admin routes
$route['admin'] = 'admin/dashboard';
$route['admin/dashboard'] = 'admin/dashboard';

$route['admin/products'] = 'admin/products';
$route['admin/products/add'] = 'admin/add_product';
$route['admin/products/edit/(:num)'] = 'admin/edit_product/$1';
$route['admin/products/delete/(:num)'] = 'admin/delete_product/$1';
$route['admin/products/delete_image/(:num)'] = 'admin/delete_image/$1';
$route['admin/set_primary_image'] = 'admin/set_primary_image';

$route['admin/orders'] = 'admin/orders';
$route['admin/orders/update-status/(:num)'] = 'admin/update_order_status/$1';
$route['admin/orders/view/(:num)'] = 'admin/view_order/$1';

$route['admin/categories'] = 'admin/categories';
$route['admin/categories/add'] = 'admin/add_category';
$route['admin/categories/edit/(:num)'] = 'admin/edit_category/$1';
$route['admin/categories/delete/(:num)'] = 'admin/delete_category/$1';

$route['admin/users'] = 'admin/users';
$route['admin/users/add'] = 'admin/add_user';
$route['admin/users/edit/(:num)'] = 'admin/edit_user/$1';
$route['admin/users/delete/(:num)'] = 'admin/delete_user/$1';
$route['admin/users/view/(:num)'] = 'admin/view_user/$1';

$route['admin/payments/confirm/(:num)'] = 'admin/confirm_payment/$1';
$route['admin/payments/reject/(:num)'] = 'admin/reject_payment/$1';

$route['admin/file-cleanup'] = 'admin/file_cleanup';
$route['admin/run-file-cleanup'] = 'admin/run_file_cleanup';

// Wishlist routes
$route['wishlist'] = 'wishlist/index';
$route['wishlist/add'] = 'wishlist/add';
$route['wishlist/remove'] = 'wishlist/remove';
$route['wishlist/remove/(:num)'] = 'wishlist/remove_item/$1';
$route['wishlist/count'] = 'wishlist/get_count';
$route['wishlist/check_status'] = 'wishlist/check_status';

