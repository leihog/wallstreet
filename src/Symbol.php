<?php

namespace Gomitech\Wallstreet;

class Symbol implements SymbolInterface {

    protected $name;                // STARBREEZE
    protected $symbol;              // STAR.ST
    protected $openPrice;           // 22.30
    protected $tradePrice;          // 22.20
    protected $change;              // -0.20
    protected $changeInPercent;     // -0.90%
    protected $volume;              // 1057345
    protected $exchange;            // STO
    protected $daysLow;             // 12.50
    protected $daysHigh;            // 14.50

    public function __construct(array $properties = []) {
        foreach($properties as $key => $value) {
            $method = "set". ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    public function getName() {
        return $this->name;
    }

    public function setName($value) {
        $this->name = $value;
    }

    public function getSymbol() {
        return $this->symbol;
    }

    public function setSymbol($value) {
        $this->symbol = strtoupper($value);
    }

    public function getOpenPrice() {
        return $this->openPrice;
    }

    public function setOpenPrice($value) {
        $this->openPrice = $value;
    }

    public function getTradePrice() {
        return $this->tradePrice;
    }

    public function setTradePrice($value) {
        $this->tradePrice = $value;
    }

    public function getChange() {
        return $this->change;
    }

    public function getChangeInPercent() {
        return $this->changeInPercent;
    }

    public function setChange($change, $changeInPercent = null) {
        $this->change = $change;

        if (!empty($changeInPercent)) {
            $this->changeInPercent = trim($changeInPercent, "%");
        } elseif (!empty($this->openPrice)) {
            $this->changeInPercent = round(($change/$this->openPrice*100), 2);
        }
    }

    public function getVolume() {
        return $this->volume;
    }

    public function setVolume($value) {
        $this->volume = $value;
    }

    public function getExchange() {
        return $this->exchange;
    }

    public function setExchange($value) {
        $this->exchange = $value;
    }

    public function getDaysLow() {
        return $this->daysLow;
    }

    public function setDaysLow($value) {
        $this->daysLow = $value;
    }

    public function getDaysHigh() {
        return $this->daysHigh;
    }

    public function setDaysHigh($value) {
        $this->daysHigh = $value;
    }

    public function toArray() {
        return get_object_vars($this);
    }

    public function toJson() {
        return json_encode($this->toArray());
    }
}
