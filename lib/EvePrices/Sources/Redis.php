<?php
namespace EvePrices\Sources;

class Redis
{

    private $redis;

    public function __construct($host, $port, $scheme)
    {
        $this->redis = new \Predis\Client(array('scheme' => $scheme,'host'   => $host,'port'   => $port));
    }

    public function returnPrice($typeid, $regionid)
    {
        $pricedatasell=$this->redis->get($regionid.'sell-'.$typeid);
        $pricedatabuy=$this->redis->get($regionid.'buy-'.$typeid);
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
