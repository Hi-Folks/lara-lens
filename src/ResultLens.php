<?php

namespace HiFolks\LaraLens;

use Illuminate\Support\Collection;

class ResultLens
{
    private $result = null;
    private $idx = -1;

    public function __construct()
    {
        $this->reset();
    }

    public function reset()
    {
        $this->result = collect();
        $this->idx = -1;

    }
    public function add($label, $value)
    {
        $this->result->push(
            [
                "label"=> $label,
                "value" => $value
            ]
        );
        $this->idx++;
    }

    public function toArray()
    {
        return $this->result->toArray();
    }
}
