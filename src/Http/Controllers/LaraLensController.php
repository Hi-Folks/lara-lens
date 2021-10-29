<?php

namespace HiFolks\LaraLens\Http\Controllers;

use HiFolks\LaraLens\Lens\LaraLens;

class LaraLensController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        if (config('lara-lens.web-enabled') != 'on') {
            abort(403, 'Unauthorized action.', ['X-Laralens' => 'off']);
        }
        $ll = new LaraLens();



        $data = [
            [
                "title" => "Check Server requirements",
                "description" => "Check Server requirements",
                "data" => $ll->checkServerRequirements()->toArray()
            ],
            [
                "title" => "Configs",
                "description" => "Config keys via config()",
                "data" => $ll->getConfigs()->toArray()
            ],
            [
                "title" => "Runtime",
                "description" => "Laravel Runtime configs",
                "data" => $ll->getRuntimeConfigs()->toArray()
            ],
            [
                "title" => "Check files",
                "description" => "Check files consistency / exists",
                "data" => $ll->checkFiles()->toArray()
            ],
            [
                "title" => "Connections",
                "description" => "Check connections",
                "data" => $ll->getConnections('')->toArray()
            ],
            [
                "title" => "Database",
                "description" => "Config Database",
                "data" => $ll->getDatabase()->toArray()
            ],
            [
                "title" => "PHP Extensions",
                "description" => "List of PHP extensions loaded",
                "data" => $ll->getPhpExtensions()->toArray()
            ],
            [
                "title" => "PHP INI",
                "description" => "List of php ini values",
                "data" => $ll->getPhpIniValues()->toArray()
            ],
            [
                "title" => "Credits",
                "description" => "LaraLens app",
                "data" => $ll->getCredits()->toArray()
            ],
        ];

        $checks = $ll->checksBag->toArray();

        return view('lara-lens::laralens.index', ['data' => $data, 'checks' => $checks]);
    }
}
