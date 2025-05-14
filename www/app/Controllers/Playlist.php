<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\PageNotFoundException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Playlist extends BaseController
{
    private Client $client;
    private string $apiKey = "aab3b83e";

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.jamendo.com/v3.0/',
        ]);
    }

    private function getPlaylist($id)
    {
        try {
            $response = $this->client->request('GET', 'playlists', [
                'query' => [
                    'client_id'  => $this->apiKey,
                    'format'     => 'json',
                    'id'  => $id,
                ]
            ]);
            $data = json_decode($response->getBody(), false);

            if (isset($data->results) && count($data->results) > 0) {
                return $data->results[0];
            }

            return [];
        } catch (GuzzleException $e) {
            log_message('error', 'Error fetching albums: ' . $e->getMessage());
            return [];
        }
    }

    private function getTracks($playlist_id)
    {
        try {
            $response = $this->client->request('GET', 'tracks', [
                'query' => [
                    'client_id'   => $this->apiKey,
                    'format'      => 'json',
                    'playlist_id' => $playlist_id,
                    //'limit'       => 50,
                ]
            ]);

            $data = json_decode($response->getBody(), false);

            if (isset($data->results) && count($data->results) > 0) {
                return $data->results;
            }

            return [];
        } catch (GuzzleException $e) {
            log_message('error', 'Error fetching tracks: ' . $e->getMessage());
            return [];
        }
    }


    public function view($id){
        $playlist = $this->getPlaylist($id);

        if (empty($playlist)) {
            throw PageNotFoundException::forPageNotFound("Playlist no encontrada.");
        }

        $playlist->tracks = $this->getTracks($playlist->id);
        return view('playlist', ['playlist' => $playlist]);
    }
}