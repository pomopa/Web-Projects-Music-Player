<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->group('/', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('', 'LandingPage::landingPage');

    $routes->group('sign-up', ['filter' => 'NotLoggedFilter'], function ($routes) {
        $routes->get('', 'SignUp::showForm');
        $routes->post('', 'SignUp::simpleSubmit', ['filter' => 'images']);
        $routes->get('success', 'SignUp::success');
    });

    $routes->group('sign-in', ['filter' => 'NotLoggedFilter'], function ($routes) {
        $routes->get('', 'SignIn::showForm');
        $routes->post('', 'SignIn::simpleSubmit', ['filter' => 'files']);
    });

    $routes->get('sign-out', 'SignOut::signOut');
});



$routes->group('home', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('', 'Home::index');
    $routes->get('(:segment)/(:segment)', 'Home::search/$1/$2');
});

$routes->group('track', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('(:segment)', 'Track::index/$1');
});
$routes->group('artist', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('(:segment)', 'Artist::index/$1');
});
$routes->group('album', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('(:segment)', 'Album::index/$1');
});
$routes->group('playlist', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('(:segment)', 'Playlist::index/$1');
});

