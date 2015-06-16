<?php

namespace Gomitech\Wallstreet\Command;

use Gomitech\Wallstreet\FetcherInterface;
use Gomitech\Wallstreet\StorageInterface;

class Update {

    protected $storage;
    protected $fetcher;

    public function __construct(FetcherInterface $fetcher, StorageInterface $storage) {
        $this->fetcher = $fetcher;
        $this->storage = $storage;
    }

    public function run() {

        $symbols = $this->storage->getSymbolKeys();
        if (empty($symbols)) {
            return;
        }

        $data = $this->fetcher->fetch($symbols);
        foreach($data as $symbol) {
            $this->storage->saveSymbol($symbol);
        }
    }
}
