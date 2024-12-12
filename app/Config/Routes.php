<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->post('/saveUser','Home::saveUser');
$routes->get('/getUser/(:num)','Home::getUser/$1');
$routes->post('/updateUser','Home::updateUser');
$routes->post('/deleteUser','Home::deleteUser');
$routes->post('/deleteAll','Home::deleteAll');
$routes->post('/filterUser','Home::filterUser');//?filter
$routes->post('/file_upload', 'Home::file_upload');
$routes->get('/spreadsheet', 'Home::spreadsheet');
$routes->post('/upload','Home::upload');


//!login
$routes->get('login', 'Validation::login');//!first parameter is url name and second parameter function name
$routes->post('login','Validation::do_login');
$routes->get('register', 'Validation::register');
$routes->post('register','Validation::do_register');
$routes->post('dashboard','Validation::dashboard');