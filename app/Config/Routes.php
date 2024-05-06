<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/',function(){
   return redirect()->route('auth');
});
$routes->get('/logout', 'Users::logout',['filter'=>'session']);

$routes->group('product',['filter'=>'session'], function($routes){
    $routes->get('', 'Products::index',);
    $routes->get('add', 'Products::add');
    $routes->get('edit/(:num)', 'Products::edit/$1');

    $routes->post('add', 'Products::proses_add');
    $routes->post('edit/(:num)', 'Products::proses_edit/$1');
    $routes->get('list', 'Products::listing_product');
    $routes->delete('delete/(:num)', 'Products::proses_hapus/$1');

});

$routes->group('download',['filter'=>'session'], function($routes){
    $routes->get('excel/product', 'DownloadExcel::products',);
});

$routes->get('/profile', 'Home::profile',['filter'=>'session']);

$routes->get('/auth', 'Users::index',['filter'=>'auth']);
$routes->get('/home', 'Home::index');

$routes->group('auth', function($routes){
    $routes->post('proses-login', 'Users::proses_login');
});


