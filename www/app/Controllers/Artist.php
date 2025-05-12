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


    public function index($id = null)
    {
        if (!$id) {
            $id = 103;
        }

        $artist = $this->getArtistInfo($id);

        if (!$artist) {
            session()->setFlashdata('error', 'Artist not found');
            return redirect()->to('/home');
        }

        $artist->albums = $this->getArtistAlbums($id);
        $artist->tracks = $this->getArtistTracks($id);

        return view('artist', ['artist' => $artist]);
    }


    public function toggleFollow()
    {
        // Ensure the request is via AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Forbidden']);
        }

        // Ensure user is authenticated
        if (!session()->get('user_id')) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        // Get artist ID from request
        $artistId = $this->request->getPost('artist_id');

        if (!$artistId) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Artist ID is required']);
        }

        // This is where you would implement the actual follow/unfollow logic
        // For example:
        // $db = \Config\Database::connect();
        // $userId = session()->get('user_id');
        // $isFollowing = $this->getUserArtistRelationship($artistId, $userId);

        // if ($isFollowing) {
        //     // Unfollow
        //     $db->table('artist_followers')
        //         ->where('user_id', $userId)
        //         ->where('artist_id', $artistId)
        //         ->delete();
        //     $newStatus = false;
        // } else {
        //     // Follow
        //     $db->table('artist_followers')->insert([
        //         'user_id' => $userId,
        //         'artist_id' => $artistId,
        //         'created_at' => date('Y-m-d H:i:s')
        //     ]);
        //     $newStatus = true;
        // }

        // For now, we'll just simulate a successful response
        return $this->response->setJSON([
            'success' => true,
            'status' => 'followed', // or 'unfollowed'
            'artist_id' => $artistId
        ]);
    }
}