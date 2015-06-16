<?php

namespace Gomitech\Wallstreet\Command;

class Result {

    const SUCCESS = true;
    CONST FAILURE = false;

    protected $isOk;
    protected $data;

    public function __construct($isOk = true) {
        $this->isOk = (bool)$isOk;
        $this->data = [];
    }

    public function isOK() {
        return $this->isOk;
    }

    public function getData($key) {
        if (!array_key_exists($key, $this->data)) {
            return null;
        }

        return $this->data[$key];
    }

    public function setData($key, $value) {
        $this->data[$key] = $value;
    }
}
