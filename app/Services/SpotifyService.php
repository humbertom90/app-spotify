<?php

namespace App\Services;

use GuzzleHttp\Client;

class SpotifyService
{
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getAccessToken()
    {
        $response = $this->client->post(env('SPOTIFY_TOKEN_URL'), [
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id' => env('SPOTIFY_CLIENT_ID'),
                'client_secret' => env('SPOTIFY_CLIENT_SECRET'),
            ],
        ]);

        return json_decode($response->getBody(), true)['access_token'];
    }

    public function searchAlbums($bandName, $accessToken)
    {
        $response = $this->client->request('GET', env('SPOTIFY_SEARCH_URL'), [
            'query' => [
                'q' => $bandName,
                'type' => 'album'
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken
            ]
        ]);

        return json_decode($response->getBody(), true)['albums']['items'];
    }
}