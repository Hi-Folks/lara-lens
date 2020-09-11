<?php

namespace HiFolks\LaraLens\Lens;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use HiFolks\LaraLens\Lens\Objects\LaraHttpResponse;

class LaraHttp
{

    public static function get($url)
    {
        $client = new Client();
        try {
            $response = new LaraHttpResponse($client->get($url));
        } catch (GuzzleException $e) {
            $response = null;
        }
        return $response;
    }
}
