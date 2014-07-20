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
        $eveprices=$this->redis->get('eveprice-'.$typeid);
        $values=explode("|", $eveprices);
        $average=$values[0];
        $adjusted=$values[1];
        if (!(is_numeric($adjusted))) {
            $adjusted=0;
        }
        if (!(is_numeric($average))) {
            $average=0;
        }
        return array($price,$pricebuy,$adjusted,$average);
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
            $eveprices=$this->redis->get('eveprice-'.$typeid);
            $values=explode("|", $eveprices);
            $average=$values[0];
            $adjusted=$values[1];
            if (!(is_numeric($adjusted))) {
                $adjusted=0;
            }
            if (!(is_numeric($average))) {
                $average=0;
            }
            $priceArray[$typeid]=array($price,$pricebuy,$adjusted,$average);
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
            $eveprices=$this->redis->get('eveprice-'.$typeid);
            $values=explode("|", $eveprices);
            $average=$values[0];
            $adjusted=$values[1];
            if (!(is_numeric($adjusted))) {
                $adjusted=0;
            }
            if (!(is_numeric($average))) {
                $average=0;
            }
            $entry['price']=array($price,$pricebuy,$adjusted,$average);
            $populatedArray[]=$entry;
        }
        return $populatedArray;
    }
}
