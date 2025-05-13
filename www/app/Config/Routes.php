<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->group('/', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('', 'LandingPage::landingPage', ['filter' => 'logged']);

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


    $routes->get('tracks', 'Track::index');
    $routes->get('artists', 'Artist::index');
    $routes->get('albums', 'Album::index');
    $routes->get('playlists', 'Playlist::index');
    
    $routes->group('home', ['filter' => 'notlogged'], function($routes) {
        $routes->get('', 'Home::index');
        $routes->get('(:segment)/(:segment)', 'Home::search/$1/$2');
    });

    $routes->group('profile', ['filter' => 'notlogged'], function ($routes) {
        $routes->get('', 'Profile::index');
        $routes->post('', 'Profile::managePost', ['filter' => 'images']);
        $routes->get('picture', 'ProfilePicture::profileImage');
    });

});