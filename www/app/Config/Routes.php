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

    $routes->get('tracks', 'Track::index', ['as' => 'tracks_view']);
    $routes->get('artists', 'Artist::index', ['as' => 'artists_view']);
    $routes->get('albums', 'Album::index', ['as' => 'albums_view']);
    
    $routes->group('playlist', ['filter' => 'notlogged'], function($routes) {
        $routes->get('(:id)', 'Playlist::view/$1', ['as' => 'playlist_view']);
    });
    
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
        $routes->get('', 'MyPlaylist::generalView', ['as' => 'my-playlist_general_view']);
        $routes->get('(:id)', 'MyPlaylist::specificView/$1', ['as' => 'my-playlist_specific_view']);
        $routes->post('(:id)', 'MyPlaylist::specificPost/$1', ['as' => 'my-playlist_specific_post']);


        $routes->put('(:id)', 'MyPlaylist::putPlaylist/$1', ['as' => 'my-playlist_put']);
        $routes->put('(:id)/track/(:id)', 'MyPlaylist::putTrack/$1/$2', ['as' => 'my-playlist_put_song']);
        $routes->delete('(:id)', 'MyPlaylist::deletePlaylist/$1', ['as' => 'my-playlist_delete']);
        $routes->delete('(:id)/track/(:id)', 'MyPlaylist::deleteTrack/$1/$2', ['as' => 'my-playlist_delete_song']);
    });

});