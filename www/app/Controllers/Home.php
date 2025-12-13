<?php

namespace App\Controllers;

use GuzzleHttp\Client;

class Home extends BaseController
{
    private Client $client;
    private string $apiKey;

    public function __construct(){
        $this->client = new Client([
            'base_uri' => 'https://api.jamendo.com/v3.0/',
        ]);
        $this->apiKey = getenv('JAMENDO_API_KEY') ?: '';
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

    private function getNewPlaylists() {
        $response = $this->client->request('GET', 'playlists', [
            'query' => [
                'client_id' => $this->apiKey,
                'format'    => 'json',
                'limit'     => 10,
                'order'     => 'creationdate_desc'
            ]
        ]);

        return json_decode($response->getBody(), true)['results'];
    }

    private function getOldestPlaylists() {
        $response = $this->client->request('GET', 'playlists', [
            'query' => [
                'client_id' => $this->apiKey,
                'format'    => 'json',
                'limit'     => 10,
                'order'     => 'creationdate_asc'
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

    private function getNewArtists() {
        $response = $this->client->request('GET', 'artists', [
            'query' => [
                'client_id' => $this->apiKey,
                'format'    => 'json',
                'limit'     => 10,
                'order'     => 'joindate_desc',
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

    private function getNewAlbums() {
        $response = $this->client->request('GET', 'albums', [
            'query' => [
                'client_id' => $this->apiKey,
                'format'    => 'json',
                'limit'     => 10,
                'order'     => 'releasedate_desc',
            ]
        ]);

        return json_decode($response->getBody(), true)['results'];
    }

    public function index(): string
    {
        $data = [
            'topTracks'     => $this->getTopTracks(),
            'recentTracks'  => $this->getRecentTracks(),
            'newPlaylists'  => $this->getNewPlaylists(),
            'oldPlaylists'   => $this->getOldestPlaylists(),
            'topArtists'    => $this->getTopArtists(),
            'newArtists'    => $this->getNewArtists(),
            'topAlbums'     => $this->getTopAlbums(),
            'newAlbums'     => $this->getNewAlbums(),
        ];

        return view('home', $data);
    }


    public function search($category, $query)
    {
        $query = trim($query);
        $validCategories = ['tracks', 'albums', 'artists', 'playlists'];
        if (!in_array($category, $validCategories)) {
            return $this->response->setStatusCode(400)
                ->setJSON(['error' => lang('Validation.invalid_category') . implode(', ', $validCategories)]);
        }

        if (empty($query)) {
            return $this->response->setStatusCode(400)
                ->setJSON(['error' => lang('Validation.search_empty')]);
        }

        try {
            $results = $this->searchData($category, $query);
            return $this->response->setJSON($results);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)
                ->setJSON(['error' => lang('Validation.error_while_searching') . $e->getMessage()]);
        }
    }

    private function searchData($category, $query)
    {
        $response = $this->client->request('GET', $category, [
            'query' => [
                'client_id' => $this->apiKey,
                'format'    => 'json',
                'limit'     => 5,
                'namesearch' => $query,
            ]
        ]);
        return json_decode($response->getBody(), true);
    }
}
