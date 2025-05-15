<?php

namespace App\Controllers;

use App\Controllers\BaseController;
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
            return [];
        }
    }

    private function getOwner($id){
        try {
            $response = $this->client->request('GET', 'users', [
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

    private function getPlaylistTracks($id)
    {
        try {
            $response = $this->client->request('GET', 'playlists/tracks', [
                'query' => [
                    'client_id'  => $this->apiKey,
                    'format'     => 'json',
                    'limit'      => 'all',
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

    private function getUserPlaylists($userId, $excludePlaylist){
        try {
            $response = $this->client->request('GET', 'playlists', [
                'query' => [
                    'client_id'  => $this->apiKey,
                    'format'     => 'json',
                    'user_id'  => $userId,
                    'limit'      => 10,
                    'order'      => 'creationdate_desc',
                ]
            ]);
            $data = json_decode($response->getBody(), false);

            if (isset($data->results) && count($data->results) > 0) {
                $playlists = array_filter($data->results, function($playlist) use ($excludePlaylist) {
                    return $playlist->id != $excludePlaylist;
                });
                foreach ($playlists as $playlist) {
                    $playlist->image = 'https://img.freepik.com/premium-psd/music-icon-user-interface-element-3d-render-illustration_516938-1693.jpg';
                }
                return array_slice($playlists, 0, 4);
            }
            return [];
        } catch (GuzzleException $e) {
            return [];
        }
    }

    public function index($id){
        $playlist = $this->getPlaylist($id);
        $playlist->image = 'https://img.freepik.com/premium-psd/music-icon-user-interface-element-3d-render-illustration_516938-1693.jpg';
        $playlist->tracks = $this->getPlaylistTracks($id)->tracks ?? [];
        $playlist->totalDuration = 0;
        if ($playlist && !empty($playlist->tracks)) {
            foreach ($playlist->tracks as $track) {
                $playlist->totalDuration += $track->duration;
            }
        }
        $playlist->owner = $this->getOwner($playlist->user_id);
        $playlist->similarPlaylists = $this->getUserPlaylists($playlist->user_id, $id);
        return view('playlist', ['playlist' => $playlist]);
    }
}