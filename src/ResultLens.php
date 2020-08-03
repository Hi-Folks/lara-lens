<?php

namespace HiFolks\LaraLens;

use Illuminate\Support\Collection;

class ResultLens
{
    private $result = null;
    private $idx = -1;

    public const LINE_TYPE_ERROR   = 'error';
    public const LINE_TYPE_WARNING = 'warning';
    public const LINE_TYPE_HINT    = 'hint';
    public const LINE_TYPE_DEFAULT = 'default';


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
        $this->add($label, $value, true, self::LINE_TYPE_ERROR);
    }

    public function addWarning($label, $value) {
        $this->add($label, $value, true, self::LINE_TYPE_WARNING);
    }


    public function addHint($value) {
        $this->add("*** HINT", $value, true, self::LINE_TYPE_HINT);
    }
    public function addErrorAndHint($label, $errorMessage, $hintMessage) {
        $this->addError($label, $errorMessage);
        $this->addHint($hintMessage);
    }
    public function addWarningAndHint($label, $warningMessage, $hintMessage) {
        $this->addWarning($label, $warningMessage);
        $this->addHint($hintMessage);
    }

    public function add($label, $value, $forceLine = false, $lineType = self::LINE_TYPE_DEFAULT)
    {
        $this->result->push(
            [
                "label"=> $label,
                "value" => $value,
                "isLine" => $forceLine,
                "lineType" => $lineType
            ]
        );
        $this->idx++;
    }

    public function toArray()
    {
        return $this->result->toArray();
    }
}
