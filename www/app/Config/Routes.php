<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->group('/', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('', 'LandingPage::landingPage');

    $routes->group('sign-up', ['filter' => 'NotLoggedFilter'], function ($routes) {
        $routes->get('', 'SignUp::showForm');
        $routes->post('', 'SignUp::simpleSubmit');
        $routes->get('success', 'SignUp::success');
    });

    $routes->group('sign-in', ['filter' => 'NotLoggedFilter'], function ($routes) {
        $routes->get('', 'SignIn::showForm');
        $routes->post('', 'SignIn::simpleSubmit');
    });

    $routes->get('sign-out', 'SignOut::signOut');
});


$routes->get('/home', 'Home::index');
