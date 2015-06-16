<?php

namespace Gomitech\Wallstreet;

class YahooFetcher implements FetcherInterface {

    protected $api;
    protected $attributes;

    public function __construct() {
        if (!class_exists('\DirkOlbrich\YahooFinanceQuery\YahooFinanceQuery')) {
            throw new \Exception("Unable to load required library 'dirkolbrich/yahoo-finance-query'.");
        }

        $this->api = new \DirkOlbrich\YahooFinanceQuery\YahooFinanceQuery();
        $this->attributes = [       // Examples:
            'Name',                 // STARBREEZE
            'Symbol',               // STAR.ST
            'LastTradePriceOnly',   // 22.20
            'Change',               // -0.20
            'ChangeinPercent',      // -0.90%
            'Open',                 // 22.30
            'Volume',               // 1057345
            'StockExchange',        // STO
            'DaysLow',              // 12.50
            'DaysHigh',             // 14.50
        ];
    }

    public function fetch(array $names) {
        if (empty($names)) {
            return [];
        }

        $symbols = [];
        $data = $this->api->quote($names, $this->attributes)->get();
        foreach($data as $symbolData) {
            if (!isset($symbolData['Name']) || $symbolData['Name'] === "N/A") {
                continue;
            }

            $symbol = new Symbol();
            $symbol->setName($symbolData['Name']);
            $symbol->setSymbol($symbolData['Symbol']);
            $symbol->setOpenPrice($symbolData['Open']);
            $symbol->setTradePrice($symbolData['LastTradePriceOnly']);
            $symbol->setChange($symbolData['Change'], $symbolData['ChangeinPercent']);
            $symbol->setVolume($symbolData['Volume']);
            $symbol->setExchange($symbolData['StockExchange']);
            $symbol->setDaysLow($symbolData['DaysLow']);
            $symbol->setDaysHigh($symbolData['DaysHigh']);

            $symbols[] = $symbol;
        }

        return $symbols;
    }
}
