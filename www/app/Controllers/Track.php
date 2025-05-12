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

    public function index(): string
    {
        $data = $this->getTrackInfo(169);

        return view('track', ['track' => $data->results[0]]);
    }

}