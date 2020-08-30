<?php
namespace HiFolks\LaraLens\Lens\Objects;
use Psr\Http\Message\ResponseInterface;

class LaraHttpResponse {
    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * LaraHttpResponse constructor.
     * @param ResponseInterface $r
     */
    public function __construct(ResponseInterface $r)
    {
        $this->response = $r;
    }
    public function status() {
        $this->response->getStatusCode();
    }


    public function failed() {
        return ($this->response->getStatusCode()>= 400);
    }

}
