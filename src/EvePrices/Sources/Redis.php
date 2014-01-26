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


    public function returnPriceArray($typeids, $regionid)
    {
        $priceArray=array();
        foreach ($typeids as $typeid) {
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
            $priceArray[$typeid]=array($price,$pricebuy);
        }
        return $priceArray;
    }
    
    public function populateArray($inputarray, $regionid)
    {
        $populatedArray=array();
        foreach ($inputarray as $entry) {
            $typeid=$entry['typeid'];
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
            $entry['price']=array($price,$pricebuy);
            $populatedArray[]=$entry;
        }
        return $populatedArray;
    }
}
