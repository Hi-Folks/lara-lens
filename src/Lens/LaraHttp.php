<?php


namespace HiFolks\LaraLens\Lens;


use GuzzleHttp\Client;
use HiFolks\LaraLens\Lens\Objects\LaraHttpResponse;


class LaraHttp
{

    public static function get($url)
    {
        $client = new Client();
        $response = new LaraHttpResponse($client->get($url));
        return $response;
    }
}
