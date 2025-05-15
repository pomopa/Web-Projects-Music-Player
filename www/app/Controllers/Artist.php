<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Artist extends BaseController
{
    private Client $client;
    private string $apiKey = "aab3b83e";

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.jamendo.com/v3.0/',
        ]);
    }


    private function getArtistInfo($id)
    {
        try {
            $response = $this->client->request('GET', 'artists', [
                'query' => [
                    'client_id' => $this->apiKey,
                    'format'    => 'json',
                    'id'        => $id,
                    'imagesize' => 400,
                ]
            ]);
            $data = json_decode($response->getBody(), false);

            if (isset($data->results) && count($data->results) > 0) {
                return $data->results[0];
            }

            return null;
        } catch (GuzzleException $e) {
            return null;
        }
    }


    private function getArtistAlbums($artistId)
    {
        try {
            $response = $this->client->request('GET', 'albums', [
                'query' => [
                    'client_id'  => $this->apiKey,
                    'format'     => 'json',
                    'artist_id'  => $artistId,
                    'imagesize'  => 300,
                    'limit'      => 'all',
                    'fullcount'  => true,
                    'order'      => 'releasedate_desc',
                    'include'    => 'stats',
                ]
            ]);
            $data = json_decode($response->getBody(), false);

            if (isset($data->results) && count($data->results) > 0) {
                $data->fullcount = $data->headers->results_fullcount;
                return $data;
            }

            return [];
        } catch (GuzzleException $e) {
            return [];
        }
    }

    private function getArtistTracks($artistId)
    {
        try {
            $response = $this->client->request('GET', 'artists/tracks', [
                'query' => [
                    'client_id'  => $this->apiKey,
                    'format'     => 'json',
                    'id'  => $artistId,
                ]
            ]);
            $data = json_decode($response->getBody(), false);

            if (isset($data->results) && count($data->results) > 0) {
                return $data->results[0]->tracks;
            }
            return [];
        } catch (GuzzleException $e) {
            return [];
        }
    }


    public function index($id)
    {
        $artist = $this->getArtistInfo($id);

        if (empty($artist)){
            return redirect()->to(base_url(route_to('home_view')));
        }

        $artist->albums = $this->getArtistAlbums($id);
        $artist->fullcount = $artist->albums->fullcount;
        $artist->albums = $artist->albums->results;
        $artist->tracks = $this->getArtistTracks($id);
        $defaultImage = 'https://static.vecteezy.com/system/resources/thumbnails/004/511/281/small_2x/default-avatar-photo-placeholder-profile-picture-vector.jpg';
        if (empty($artist->image)) {
            $artist->image = $defaultImage;
        }

        return view('artist', ['artist' => $artist]);
    }
}