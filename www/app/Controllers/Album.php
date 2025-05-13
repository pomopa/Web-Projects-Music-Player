<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Album extends BaseController
{
    private Client $client;
    private string $apiKey = "aab3b83e";

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.jamendo.com/v3.0/',
        ]);
    }

    private function getAlbum($id)
    {
        try {
            $response = $this->client->request('GET', 'albums', [
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
    private function getAlbumTracks($id)
    {
        try {
            $response = $this->client->request('GET', 'tracks', [
                'query' => [
                    'client_id'  => $this->apiKey,
                    'format'     => 'json',
                    'album_id'  => $id,
                ]
            ]);
            $data = json_decode($response->getBody(), false);

            if (isset($data->results) && count($data->results) > 0) {
                return $data->results;
            }

            return [];
        } catch (GuzzleException $e) {
            log_message('error', 'Error fetching album tracks: ' . $e->getMessage());
            return [];
        }
    }

    private function getArtistAlbums($artistId, $excludeAlbumId = null){
        try {
            $response = $this->client->request('GET', 'albums', [
                'query' => [
                    'client_id'  => $this->apiKey,
                    'format'     => 'json',
                    'artist_id'  => $artistId,
                    'limit'      => 10,
                    'imagesize'  => 300,
                    'order'      => 'releasedate_desc',
                ]
            ]);
            $data = json_decode($response->getBody(), false);

            if (isset($data->results) && count($data->results) > 0) {
                $albums = array_filter($data->results, function($album) use ($excludeAlbumId) {
                    return $album->id != $excludeAlbumId;
                });
                return array_slice($albums, 0, 3);
            }

            return [];
        } catch (GuzzleException $e) {
            log_message('error', 'Error fetching artist albums: ' . $e->getMessage());
            return [];
        }
    }

    public function index(){
        $album = $this->getAlbum(24);
        $album->tracks = $this->getAlbumTracks(24);
        $album->similarAlbums = $this->getArtistAlbums($album->artist_id, $album->id);
        return view('albums', ['album' => $album]);
    }
}