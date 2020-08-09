<?php
namespace HiFolks\LaraLens\Lens\Traits;


use HiFolks\LaraLens\ResultLens;
use Illuminate\Support\Facades\Http;

trait HttpConnectionLens
{
    public function getConnections()
    {
        $results = new ResultLens();
        $url = config("app.url");
        $results->add(
            "Connect to URL",
            $url
        );

        try {
            $response = Http::get($url);
            $results->add(
                "Connection HTTP Status",
                $response->status()
            );
            if ($response->failed()) {
                $this->checksBag->addWarningAndHint(
                    "Connection HTTP Status",
                    "Connection response not 20x, status code: " . $response->status(),
                    "Check this URL: " . $url . " in .env file APP_URL"
                );

            }


        } catch (\Exception $e){
            $results->add(
                "Connection HTTP Status",
                "Error connection"
            );
            $this->checksBag->addErrorAndHint(
                "Connection HTTP Status",
                "Connection Error: " . $e->getMessage(),
                "Check this URL: " . $url . " in .env file APP_URL"
            );
        }
        return $results;
    }

}
