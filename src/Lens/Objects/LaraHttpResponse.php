<?php

namespace HiFolks\LaraLens\Lens\Objects;

use Psr\Http\Message\ResponseInterface;

class LaraHttpResponse
{
    /**
     * LaraHttpResponse constructor.
     */
    public function __construct(private ResponseInterface $response)
    {
    }

    /**
     * Return the Http response status code.
     *
     * @return int
     */
    public function status()
    {
        return $this->response->getStatusCode();
    }

    /**
     * Return true if the status code of the HTTP response is an error
     *
     * @return bool
     */
    public function failed()
    {
        return ($this->response->getStatusCode() >= 400);
    }
}
