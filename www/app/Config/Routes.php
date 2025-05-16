<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->group('/', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('', 'LandingPage::landingPage', ['filter' => 'logged', 'as' => 'landing_view']);

    $routes->group('sign-up', ['filter' => 'logged'], function ($routes) {
        $routes->get('', 'SignUp::showForm', ['as' => 'sign-up_view']);
        $routes->post('', 'SignUp::simpleSubmit', ['filter' => 'images', 'as' => 'sign-up_submit']);
        $routes->get('success', 'SignUp::success', ['as' => 'sign-up_success']);
    });

    $routes->group('sign-in', ['filter' => 'logged'], function ($routes) {
        $routes->get('', 'SignIn::showForm', ['as' => 'sign-in_view']);
        $routes->post('', 'SignIn::simpleSubmit', ['filter' => 'files', 'as' => 'sign-in_logic']);
    });

    $routes->get('sign-out', 'SignOut::signOut', ['filter' => 'notlogged', 'as' => 'sign-out_logic']);

    $routes->group('home', ['filter' => 'notlogged'], function($routes) {
        $routes->get('', 'Home::index', ['as' => 'home_view']);
        $routes->get('(:segment)/(:segment)', 'Home::search/$1/$2', ['as' => 'home_search']);
    });

    $routes->group('profile', ['filter' => 'notlogged'], function ($routes) {
        $routes->get('', 'Profile::index', ['as' => 'profile_view']);
        $routes->post('', 'Profile::managePost', ['filter' => 'images', 'as' => 'profile_post']);
        $routes->get('picture', 'ProfilePicture::profileImage', ['as' => 'profile_picture']);
    });

    $routes->group('my-playlists', ['filter' => 'notlogged'], function ($routes) {
        $routes->get('', 'MyPlaylist::index', ['as' => 'my-playlist_view']);
        $routes->get('(:num)', 'MyPlaylist::viewPlaylist/$1', ['as' => 'my-playlist_exact_view']);

        $routes->put('(:num)', 'MyPlaylist::putPlaylist/$1', ['as' => 'my-playlist_put']);
        $routes->put('(:num)/track/(:segment)', 'MyPlaylist::putTrack/$1/$2', ['as' => 'my-playlist_put_song']);

        $routes->delete('(:num)', 'MyPlaylist::deletePlaylist/$1', ['as' => 'my-playlist_delete']);
        $routes->delete('(:num)/track/(:segment)', 'MyPlaylist::deleteTrack/$1/$2', ['as' => 'my-playlist_delete_song']);
    });

    $routes->group('create-playlist', ['namespace' => 'App\Controllers'], function($routes) {
        $routes->post('', 'MyPlaylist::createPlaylist', ['filter' => ['notlogged', 'images'], 'as' => 'my-playlist_create']);
        $routes->get('', 'MyPlaylist::createPlaylistView', ['filter' => ['notlogged'], 'as' => 'my-playlist_create_view']);
    });


    $routes->group('track', ['namespace' => 'App\Controllers'], function($routes) {
        $routes->get('(:segment)', 'Track::index/$1',  ['as' => 'tracks_view']);
    });

    $routes->group('artist', ['namespace' => 'App\Controllers'], function($routes) {
        $routes->get('(:segment)', 'Artist::index/$1', ['as' => 'artists_view']);
    });

    $routes->group('album', ['namespace' => 'App\Controllers'], function($routes) {
        $routes->get('(:segment)', 'Album::index/$1', ['as' => 'albums_view']);
    });

    $routes->group('playlist', ['namespace' => 'App\Controllers'], function($routes) {
        $routes->get('(:segment)', 'Playlist::index/$1', ['as' => 'playlist_view']);
    });

});