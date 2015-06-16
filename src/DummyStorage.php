<?php

namespace Gomitech\Wallstreet;

class DummyStorage implements StorageInterface {

    protected $symbols;

    public function __construct(array $symbols = null) {
        $this->symbols = [];
        if (!empty($symbols)) {
            foreach($symbols as $symbol) {
                $this->saveSymbol($symbol);
            }
        }
    }

    public function getSymbolKeys() {
        return array_keys($this->symbols);
    }

    public function getSymbols() {
        return array_values($this->symbols);
    }

    public function saveSymbol(SymbolInterface $symbol) {
        $this->symbols[$symbol->getSymbol()] = $symbol;
    }
}
