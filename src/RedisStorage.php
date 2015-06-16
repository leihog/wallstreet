<?php

namespace Gomitech\Wallstreet;

class RedisStorage implements StorageInterface {

    protected $client;

    public function __construct(array $config = []) {
        if (!class_exists('\Predis\Client')) {
            throw new \Exception("Unable to load required library 'Predis\Client'.");
        }

        $config = array_merge([
            'scheme' => 'tcp',
            'host'   => '127.0.0.1',
            'port'   => 6379,
        ], $config);

        $this->client = new \Predis\Client($config);
    }

    // Using a HASH
    //
    // HSET symbols:data STAR.ST '{"symbol":"STAR.ST","Price":"12"...}'
    // HGET symbols:data STAR.ST
    //   '{"symbol":"STAR.ST","Price":"12"...}'
    //
    // HKEYS symbols:data // get all symbols (name only)
    //   APTA.ST
    //   STAR.ST
    //
    // HVALS symbols:data // get all symbol data
    //   '{"symbol":"STAR.ST","Price":"12"...}'
    //   '{"symbol":"APTA.ST","Price":"12"...}'
    //
    // todo need other strategy for historic values...

    public function getSymbolKeys() {
        return array_filter($this->client->hkeys('wallstreet:symbols'));
    }

    public function getSymbols() {
        $data = $this->client->hvals('wallstreet:symbols');
    }

    public function saveSymbol(SymbolInterface $symbol) {
        // hset return 1 if it's a new field, 0 if it was updated
        $isNewField = $this->client->hset('wallstreet:symbols', $symbol->getSymbol(), $symbol->toJson());
        if (0 === $isNewField) {
            echo "Debug: updated symbol: ". $symbol->getSymbol();
        }

        $this->client->publish('wallstreet', $symbol->toJson());
    }
}
