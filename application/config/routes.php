<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
$route['default_controller'] = 'DashboardController';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/*
| -------------------------------------------------------------------------
| API Routes
| -------------------------------------------------------------------------
*/

// Motor API Routes
$route['api/motors']['GET'] = 'api/MotorController/index';
$route['api/motors/(:num)']['GET'] = 'api/MotorController/show/$1';
$route['api/motors']['POST'] = 'api/MotorController/store';
$route['api/motors/(:num)']['PUT'] = 'api/MotorController/update/$1';
$route['api/motors/(:num)']['DELETE'] = 'api/MotorController/delete/$1';

// Motor routes
$route['motor'] = 'MotorController/index';
$route['motor/add'] = 'MotorController/add';
$route['motor/store'] = 'MotorController/store';
$route['motor/edit/(:num)'] = 'MotorController/edit/$1';
$route['motor/update/(:num)'] = 'MotorController/update/$1';
$route['motor/delete/(:num)'] = 'MotorController/delete/$1';
$route['motor/update-stock/(:num)'] = 'MotorController/updateStock/$1';

// Sparepart routes
$route['sparepart'] = 'SparepartController/index';
$route['sparepart/add'] = 'SparepartController/add';
$route['sparepart/store'] = 'SparepartController/store';
$route['sparepart/edit/(:num)'] = 'SparepartController/edit/$1';
$route['sparepart/update/(:num)'] = 'SparepartController/update/$1';
$route['sparepart/delete/(:num)'] = 'SparepartController/delete/$1';
$route['sparepart/search'] = 'SparepartController/search';
$route['sparepart/category/(:any)'] = 'SparepartController/getByCategory/$1';

// Customer routes
$route['customer'] = 'CustomerController/index';
$route['customer/add'] = 'CustomerController/add';
$route['customer/store'] = 'CustomerController/store';
$route['customer/edit/(:num)'] = 'CustomerController/edit/$1';
$route['customer/update/(:num)'] = 'CustomerController/update/$1';
$route['customer/delete/(:num)'] = 'CustomerController/delete/$1';
$route['customer/view/(:num)'] = 'CustomerController/view/$1';
$route['customer/search'] = 'CustomerController/search';

// Service routes
$route['service'] = 'ServiceController/index';
$route['service/add'] = 'ServiceController/add';
$route['service/store'] = 'ServiceController/store';
$route['service/edit/(:num)'] = 'ServiceController/edit/$1';
$route['service/update/(:num)'] = 'ServiceController/update/$1';
$route['service/delete/(:num)'] = 'ServiceController/delete/$1';
$route['service/view/(:num)'] = 'ServiceController/view/$1';

// Sales routes
$route['sales'] = 'SalesController/index';
$route['sales/add'] = 'SalesController/add';
$route['sales/store'] = 'SalesController/store';
$route['sales/edit/(:num)'] = 'SalesController/edit/$1';
$route['sales/update/(:num)'] = 'SalesController/update/$1';
$route['sales/delete/(:num)'] = 'SalesController/delete/$1';
$route['sales/view/(:num)'] = 'SalesController/view/$1';
$route['sales/invoice/(:num)'] = 'SalesController/invoice/$1';
$route['sales/confirm_payment/(:num)'] = 'SalesController/confirm_payment/$1';

// Category routes
$route['category'] = 'CategoryController/index';
$route['category/add'] = 'CategoryController/add';
$route['category/store'] = 'CategoryController/store';
$route['category/edit/(:num)'] = 'CategoryController/edit/$1';
$route['category/update/(:num)'] = 'CategoryController/update/$1';
$route['category/delete/(:num)'] = 'CategoryController/delete/$1';
