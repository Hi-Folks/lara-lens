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

    public function addError($label, $value) {
        $this->add($label, $value, true, true);
    }


    public function addHint($value) {
        $this->add("*** HINT", $value, true, false);
    }
    public function addErrorAndHint($label, $errorMessage, $hintMessage) {
        $this->addError($label, $errorMessage);
        $this->addHint($hintMessage);
    }

    public function add($label, $value, $forceLine = false, $isError = false)
    {
        $this->result->push(
            [
                "label"=> $label,
                "value" => $value,
                "isLine" => $forceLine,
                "isError" => $isError,

            ]
        );
        $this->idx++;
    }

    public function toArray()
    {
        return $this->result->toArray();
    }
}
