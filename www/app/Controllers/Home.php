<?php

namespace App\Controllers;

use GuzzleHttp\Client;

class Home extends BaseController
{
    private Client $client;
    private string $apiKey = "aab3b83e";

    public function __construct(){
        $this->client = new Client([
            'base_uri' => 'https://api.jamendo.com/v3.0/',
        ]);
    }

    private function getTopTracks() {
        $response = $this->client->request('GET', 'tracks', [
            'query' => [
                'client_id' => $this->apiKey,
                'format'    => 'json',
                'limit'     => 10,
                'order'     => 'popularity_total'
            ]
        ]);

        return json_decode($response->getBody(), true)['results'];
    }

    private function getRecentTracks() {
        $response = $this->client->request('GET', 'tracks', [
            'query' => [
                'client_id' => $this->apiKey,
                'format'    => 'json',
                'limit'     => 10,
                'order'     => 'releasedate_desc'
            ]
        ]);

        return json_decode($response->getBody(), true)['results'];
    }

    private function getTopPlaylists() {
        $response = $this->client->request('GET', 'playlists', [
            'query' => [
                'client_id' => $this->apiKey,
                'format'    => 'json',
                'limit'     => 10,
                'order'     => 'popularity_total'
            ]
        ]);

        return json_decode($response->getBody(), true)['results'];
    }

    private function getTopArtists() {
        $response = $this->client->request('GET', 'artists', [
            'query' => [
                'client_id' => $this->apiKey,
                'format'    => 'json',
                'limit'     => 10,
                'order'     => 'popularity_total',
            ]
        ]);

        return json_decode($response->getBody(), true)['results'];
    }

    private function getTopAlbums() {
        $response = $this->client->request('GET', 'albums', [
            'query' => [
                'client_id' => $this->apiKey,
                'format'    => 'json',
                'limit'     => 10,
                'order'     => 'popularity_total',
            ]
        ]);

        return json_decode($response->getBody(), true)['results'];
    }

    public function index(): string
    {
        $data = [
            'topTracks'     => $this->getTopTracks(),
            'recentTracks'  => $this->getRecentTracks(),
            'topPlaylists'  => $this->getTopPlaylists(),
            'topArtists'    => $this->getTopArtists(),
            'topAlbums'     => $this->getTopAlbums(),
        ];

        return view('home', $data);
    }
}
