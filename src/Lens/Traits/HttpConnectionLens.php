<?php

namespace HiFolks\LaraLens\Lens\Traits;

use HiFolks\LaraLens\Lens\LaraHttp;
use HiFolks\LaraLens\ResultLens;

trait HttpConnectionLens
{
    public function getConnections($checkPath): \HiFolks\LaraLens\ResultLens
    {
        $results = new ResultLens();
        $app_url = config("app.url");
        $results->add(
            "app.url configuration",
            $app_url
        );
        $url = url($checkPath);
        $results->add(
            "url()->full()",
            url()->full()
        );
        $results->add(
            "Connection HTTP URL",
            $url
        );
        try {
            $response = LaraHttp::get($url);
            $results->add(
                "Connection HTTP Status",
                $response->status()
            );
            if ($response->failed()) {
                $checkUrlHint = "Check APP_URL '" . $app_url . "' in .env file ";
                if ($checkPath !== "") {
                    $checkUrlHint .= " or check this path: '" . $checkPath . "' in routing file (routes/web.php)";
                }
                $this->checksBag->addWarningAndHint(
                    "Connection HTTP Status",
                    "Connection response not 20x, status code: " . $response->status() . " for " . $url,
                    $checkUrlHint
                );
            }
        } catch (\Exception $e) {
            $results->add(
                "Connection HTTP Status",
                "Error connection"
            );
            $this->checksBag->addErrorAndHint(
                "Connection HTTP Status",
                "Connection Error: " . $e->getMessage(),
                "Check this URL: " . $app_url . " in .env file APP_URL"
            );
        }
        return $results;
    }
}
