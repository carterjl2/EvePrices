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

    public function returnPriceArray($typeids, $regionid)
    {
        $priceArray=array();
        foreach ($typeids as $typeid) {
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
            $priceArray[$typeid]=array($price,$pricebuy);
        }
        return $priceArray;
    }

    public function populateArray($inputarray, $regionid)
    {
        $populatedArray=array();
        foreach ($inputarray as $entry) {
            $typeid=$entry['typeid'];
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
            $entry['price']=array($price,$pricebuy);
            $populatedArray[]=$entry;
        }
        return $populatedArray;
    }
}
