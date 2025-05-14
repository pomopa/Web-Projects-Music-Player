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

        $playlistId = $playlist->id;
        $playlistName = $playlist->name ?? 'Playlist Not Found';
        $tracks = $this->getTracks($playlist->id);
        $playlist->tracks = $tracks;

        // Utilitza la imatge del primer track, si existeix
        $playlistImage = !empty($tracks) ? $tracks[0]->album_image : '/assets/img/default-cover.png';

        $playlistCreator = $playlist->user_name ?? 'Unknown Creator';
        $playlistCreatorId = $playlist->user_id ?? 0;
        $creationDate = date('Y-m-d', strtotime($playlist->creationdate ?? 'now'));

        $totalDuration = 0;
        foreach ($playlist->tracks as $track) {
            $totalDuration += $track->duration;
        }

        $formattedTotalDuration = gmdate("H:i:s", $totalDuration);
        $tracksCount = count($playlist->tracks);

        $session = session();
        $userSession = $session->get('user');
        $currentUserId = $userSession['id'] ?? null;
        if($currentUserId == null) {
            $isOwner = false;
        } else {
            $isOwner = $currentUserId == $playlistCreatorId;
        }

        return view('playlist', [
            'playlist' => $playlist,
            'playlistId' => $playlistId,
            'playlistName' => $playlistName,
            'playlistImage' => $playlistImage,
            'playlistCreator' => $playlistCreator,
            'playlistCreatorId' => $playlistCreatorId,
            'creationDate' => $creationDate,
            'tracks' => $playlist->tracks,
            'formattedTotalDuration' => $formattedTotalDuration,
            'tracksCount' => $tracksCount,
            'isOwner' => $isOwner
        ]);
    }
}