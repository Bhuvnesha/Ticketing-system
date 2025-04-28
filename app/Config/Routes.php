<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'UserController::login', ['filter' => 'guest']);
$routes->get('logout', 'UserController::logout');
$routes->match(['get', 'post'], 'login', 'UserController::login', ['filter' => 'guest']);
$routes->match(['get', 'post'], 'register', 'UserController::register', ['filter' => 'guest']);
// $routes->resource('tickets'); // RESTful routes for tickets


// profile management
$routes->match(['get', 'post'], 'profile', 'UserController::profile', ['filter' => 'auth']);


$routes->group('', ['filter' => 'auth'], function($routes) {
	$routes->get('tickets', 'Tickets::index');
	$routes->get('tickets/create', 'Tickets::create');
	$routes->post('tickets/store', 'Tickets::store'); // Change from 'tickets' to 'tickets/store'
	$routes->get('tickets/(:num)', 'Tickets::show/$1'); // For viewing a single ticket
	// New routes for edit/update/delete
	$routes->get('tickets/edit/(:num)', 'Tickets::edit/$1');
	$routes->post('tickets/update/(:num)', 'Tickets::update/$1');
	$routes->get('tickets/delete/(:num)', 'Tickets::delete/$1');
	$routes->post('comment/save', 'CommentController::save');

});


//routes for testing
$routes->get('test_mail','EmailController::sendHtmlEmail');

//forgot password routes
// Add 'filter' => 'guest' to these routes:
$routes->match(['get', 'post'], 'forgot-password', 'UserController::forgotPassword', ['filter' => 'guest']);
$routes->match(['get', 'post'], 'reset-password/(:any)', 'UserController::resetPassword/$1', ['filter' => 'guest']);

