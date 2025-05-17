<?php

namespace App\Controllers;

use App\Models\PlaylistModel;
use CodeIgniter\Controller;
use GuzzleHttp\Client;
use App\Entities\TrackEntity;

class Track extends BaseController
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

    private function getTrackInfo($id)
    {
        $response = $this->client->request('GET', 'tracks', [
            'query' => [
                'client_id' => $this->apiKey,
                'format'    => 'json',
                'id'        => $id,
            ]
        ]);
        return json_decode($response->getBody(), false);
    }

    private function getTrackArtist($id)
    {
        $response = $this->client->request('GET', 'artists', [
            'query' => [
                'client_id' => $this->apiKey,
                'format'    => 'json',
                'id'        => $id,
            ]
        ]);
        return json_decode($response->getBody(), false);
    }

    public function index($id)
    {
        helper('url');
        $rawData = $this->getTrackInfo($id);

        if (empty($rawData->results)) {
            return redirect()->to(base_url(route_to('home_view')));
        }

        $trackData = $rawData->results[0];

        $artistData = $this->getTrackArtist($trackData->artist_id);
        $artistImage = $artistData->results[0]->image ?? null;
        if (empty($artistImage)) {
            $artistImage = 'https://static.vecteezy.com/system/resources/thumbnails/004/511/281/small_2x/default-avatar-photo-placeholder-profile-picture-vector.jpg';
        }

        $track = new TrackEntity(
            $trackData->id,
            $trackData->name,
            $trackData->album_image,
            $trackData->artist_name,
            $trackData->artist_id,
            $trackData->album_name,
            $trackData->album_id,
            (int)$trackData->duration,
            $trackData->audio,
            $trackData->releasedate,
            $trackData->license_ccurl,
        );

        $track->artist_image = $artistImage;
        $track->playlists = $this->playlistModel->getPlaylistsByUserId(session()->get('user')['id']) ?? [];

        return view('track', ['track' => $track]);
    }
}
