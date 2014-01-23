<?php
namespace EvePrices\Sources;

class Memcached
{

    private $memcache;

    public function __construct($host, $port)
    {
        $this->memcache = new \Memcache;
        $this->memcache->connect($host, $port);
    }

    public function returnPrice($typeid, $regionid)
    {
        $pricedatasell=$this->memcache->get($regionid.'sell-'.$typeid);
        $pricedatabuy=$this->memcache->get($regionid.'buy-'.$typeid);
        $values=explode("|", $pricedatasell);
        $price=$values[0];
        if (!(is_numeric($price))) {
            $price=0;
        }
        $values=explode("|", $pricedatabuy);
        $pricebuy=$values[0];
        if (!(is_numeric($pricebuy))) {
            $pricebuy=0;
        }
        return array($price,$pricebuy);
    }
}
