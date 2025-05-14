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
                    'limit'      => 'all',
                    'album_id'  => $id,
                ]
            ]);
            $data = json_decode($response->getBody(), false);

            if (isset($data->results) && count($data->results) > 0) {
                return $data->results;
            }

            return [];
        } catch (GuzzleException $e) {
            return [];
        }
    }

    private function getArtistAlbums($artistId, $excludeAlbum){
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
                $albums = array_filter($data->results, function($album) use ($excludeAlbum) {
                    return $album->id != $excludeAlbum;
                });
                return array_slice($albums, 0, 4);
            }

            return [];
        } catch (GuzzleException $e) {
            return [];
        }
    }

    private function getAlbumArtist($id){
        $response = $this->client->request('GET', 'artists', [
            'query' => [
                'client_id' => $this->apiKey,
                'format'    => 'json',
                'id'        => $id,
            ]
        ]);
        return json_decode($response->getBody(), false)->results[0]->image;
    }

    public function index($id){
        $album = $this->getAlbum($id);
        $album->tracks = $this->getAlbumTracks($id);
        $album->similarAlbums = $this->getArtistAlbums($album->artist_id, $album->id);
        $album->artist_image = $this->getAlbumArtist($album->artist_id);
        if (empty($album->artist_image)) {
            $album->artist_image = 'https://static.vecteezy.com/system/resources/thumbnails/004/511/281/small_2x/default-avatar-photo-placeholder-profile-picture-vector.jpg';
        }
        $album->totalDuration = 0;
        if ($album && !empty($album->tracks)) {
            foreach ($album->tracks as $track) {
                $album->totalDuration += $track->duration;
            }
        }

        return view('albums', ['album' => $album]);
    }
}