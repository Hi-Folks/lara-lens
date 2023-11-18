<?php

namespace HiFolks\LaraLens\Lens\Traits;

use function Termwind\{render};

trait TermOutput
{
    public function printOutputTerm(
        string $title,
        array $rows
    ) {
        render(
            view('lara-lens::laralens.term.table', [
                'title' => $title,
                'rows' => $rows
            ])
        );
    }

    public function printChecksTerm(array $rows): void
    {
        render(
            view('lara-lens::laralens.term.checks', [
                'rows' => $rows
            ])
        );
    }
    public function title(string $title): void
    {
        render("<div class='w-full mx-1 py-1 mt-1 text-center font-bold  bg-orange text-white'>$title</div>");
    }
}
