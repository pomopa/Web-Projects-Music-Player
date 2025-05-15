<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use GuzzleHttp\Client;

class Track extends BaseController
{
    private Client $client;
    private string $apiKey = "aab3b83e";

    public function __construct(){
        $this->client = new Client([
            'base_uri' => 'https://api.jamendo.com/v3.0/',
        ]);
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

    private function getTrackArtist($id){
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
        $data = $this->getTrackInfo($id);
        if (empty($data->results)){
            return redirect()->to(base_url(route_to('home_view')));
        }
        $data->results[0]->artist_image = $this->getTrackArtist($data->results[0]->artist_id)->results[0]->image;
        if (empty($data->results[0]->artist_image)) {
            $data->results[0]->artist_image = 'https://static.vecteezy.com/system/resources/thumbnails/004/511/281/small_2x/default-avatar-photo-placeholder-profile-picture-vector.jpg';
        }
        return view('track', ['track' => $data->results[0]]);
    }

}