<?php

namespace Gomitech\Wallstreet\Command;

use Gomitech\Wallstreet\FetcherInterface;
use Gomitech\Wallstreet\StorageInterface;
use Gomitech\Wallstreet\Symbol;

class TrackSymbol {

    protected $storage;
    protected $fetcher;

    public function __construct(FetcherInterface $fetcher, StorageInterface $storage) {
        $this->fetcher = $fetcher;
        $this->storage = $storage;
    }

    public function run(array $keys) {

        $query = [];
        $currentKeys = $this->storage->getSymbolKeys();
        foreach($keys as $key) {
            $key = strtoupper($key);
            if (in_array($key, $currentKeys)) {
                continue;
            }

            $query[] = $key;
        }

        if (empty($query)) {
            return new Result(Result::SUCCESS);
        }

        $found = [];
        $symbols = $this->fetcher->fetch($query);
        if (!empty($symbols)) {
            foreach($symbols as $symbol) {
                $this->storage->saveSymbol($symbol);
                $found[] = $symbol->getSymbol();
            }
        }

        $diff = array_diff($query, $found);
        if (!empty($diff)) {
            $result = new Result(Result::FAILURE);
            $result->setData("failed", $diff);
            return $result;
        }

        return new Result(Result::SUCCESS);
    }
}
