<?php

namespace HiFolks\LaraLens\Lens;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use HiFolks\LaraLens\Lens\Objects\LaraHttpResponse;

class LaraHttp
{
    public static function get($url): LaraHttpResponse
    {
        $client = new Client();
        try {
            $response = new LaraHttpResponse($client->get($url, ['timeout' => 1]));
        } catch (GuzzleException $e) {
            $response = null;
            throw $e;
        }
        return $response;
    }
}
