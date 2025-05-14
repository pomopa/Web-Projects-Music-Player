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
            log_message('error', 'Error fetching artist info: ' . $e->getMessage());
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
                    'order'      => 'releasedate_desc',
                    'include'    => 'stats',
                ]
            ]);
            $data = json_decode($response->getBody(), false);

            if (isset($data->results) && count($data->results) > 0) {
                return $data->results;
            }

            return [];
        } catch (GuzzleException $e) {
            log_message('error', 'Error fetching artist albums: ' . $e->getMessage());
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
            log_message('error', 'Error fetching artist tracks: ' . $e->getMessage());
            return [];
        }
    }


    public function index($id)
    {
        $artist = $this->getArtistInfo($id);

        if (!$artist) {
            session()->setFlashdata('error', 'Artist not found');
            return redirect()->to('/home');
        }

        $artist->albums = $this->getArtistAlbums($id);
        $artist->tracks = $this->getArtistTracks($id);

        return view('artist', ['artist' => $artist]);
    }
}