<?php

namespace Gomitech\Wallstreet;

interface StorageInterface {

    public function getSymbolKeys();
    public function getSymbols();
    public function saveSymbol(SymbolInterface $symbol);
}
