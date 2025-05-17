<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PlaylistModel;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use App\Entities\TrackEntity;

class Playlist extends BaseController
{
    private Client $client;
    private string $apiKey = "aab3b83e";
    private PlaylistModel $playlistModel;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.jamendo.com/v3.0/',
        ]);
        $this->playlistModel = new PlaylistModel();
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

    private function getOwner($id)
    {
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
                $result = $data->results[0];
                $tracks = [];

                foreach ($result->tracks as $track) {
                    $entity = new TrackEntity(
                        $track->id,
                        $track->name,
                        $track->album_image,
                        $track->artist_name,
                        $track->artist_id,
                        "undefined",
                        $track->album_id,
                        $track->duration,
                        $track->audio,
                        "undefined",
                        $track->license_ccurl
                    );

                    $tracks[] = $entity;
                }

                $result->tracks = $tracks;
                return $result;
            }

            return [];
        } catch (GuzzleException $e) {
            return [];
        }
    }

    private function getUserPlaylists($userId, $excludePlaylist)
    {
        try {
            $response = $this->client->request('GET', 'playlists', [
                'query' => [
                    'client_id'  => $this->apiKey,
                    'format'     => 'json',
                    'user_id'    => $userId,
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

    public function index($id)
    {
        $playlist = $this->getPlaylist($id);
        if (empty($playlist)) {
            return redirect()->to(base_url(route_to('home_view')));
        }

        $playlist->image = 'https://img.freepik.com/premium-psd/music-icon-user-interface-element-3d-render-illustration_516938-1693.jpg';
        $playlist->tracks = $this->getPlaylistTracks($id)->tracks ?? [];
        $playlist->totalDuration = 0;

        $playlist->playlists = $this->playlistModel->getPlaylistsByUserId(session()->get('user')['id']);
        if (!empty($playlist->tracks)) {
            foreach ($playlist->tracks as $track) {
                $playlist->totalDuration += $track->duration;
            }
        }

        $playlist->owner = $this->getOwner($playlist->user_id);
        $playlist->similarPlaylists = $this->getUserPlaylists($playlist->user_id, $id) ?? [];

        return view('playlist', ['playlist' => $playlist]);
    }
}
