<?php

namespace Gomitech\Wallstreet;

interface SymbolInterface {

    public function __construct(array $properties = []);
    public function getName();
    public function setName($value);
    public function getSymbol();
    public function setSymbol($value);
    public function getOpenPrice();
    public function setOpenPrice($value);
    public function getTradePrice();
    public function setTradePrice($value);
    public function getChange();
    public function getChangeInPercent();
    public function setChange($change, $changeInPercent = null);
    public function getVolume();
    public function setVolume($value);
    public function getExchange();
    public function setExchange($value);
    public function getDaysLow();
    public function setDaysLow($value);
    public function getDaysHigh();
    public function setDaysHigh($value);
    public function toArray();
    public function toJson();
}
