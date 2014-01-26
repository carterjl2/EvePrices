<?php
namespace EvePrices;

interface CacheInterface
{
    public function __construct($host, $port);
    public function checkPrice($typeid, $regionid);
    public function checkPriceArray($typeids, $regionid);
    public function checkPopulatedArray($inputarray, $regionid);

    public function setPrice($typeid, $regionid, $pricearray);
    public function setPriceArray($typeids, $regionid);
    public function setPopulatedArray($inputarray, $regionid);
}
