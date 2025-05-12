<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->group('/', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('', 'LandingPage::landingPage', ['filter' => 'logged']);

    // logged -> redirigeix si allredy logged in
    // notlogged -> redirigeix si no esta logged
    $routes->group('sign-up', ['filter' => 'logged'], function ($routes) {
        $routes->get('', 'SignUp::showForm');
        $routes->post('', 'SignUp::simpleSubmit', ['filter' => 'images']);
        $routes->get('success', 'SignUp::success');
    });

    $routes->group('sign-in', ['filter' => 'logged'], function ($routes) {
        $routes->get('', 'SignIn::showForm');
        $routes->post('', 'SignIn::simpleSubmit', ['filter' => 'files']);
    });

    $routes->get('sign-out', 'SignOut::signOut');

    $routes->get('home', 'Home::index', ['filter' => 'notlogged']);

    $routes->group('profile', ['filter' => 'notlogged'], function ($routes) {
        $routes->get('', 'Profile::index');
        $routes->post('', 'Profile::managePost');
    });
});