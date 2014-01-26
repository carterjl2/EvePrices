<?php
namespace EvePrices\Sources;

class MarketData
{

    private $cache;
    private $userid;

    public function __construct($userid)
    {
        $this->cache =array();
        $this->userid=$userid;
    }

    public function returnPrice($typeid, $regionid)
    {
        if (isset($this->cache[$regionid-$typeid])) {
            return $this->cache[$regionid-$typeid];
        }
        $url="http://api.eve-marketdata.com/api/item_prices2.xml?char_name=".$this->userid
        ."&buysell=a&region_ids=".$regionid."&type_ids=".$typeid;
        $pricexml=file_get_contents($url);
        $xml=new \SimpleXMLElement($pricexml);
        $price= $xml->xpath('//row[@buysell="s"][@typeID="'.$typeid.'"]/@price');
        $price=(float)$price[0]->price;
        $price=round($price, 2);
        if (!(is_numeric($price))) {
            $price=0;
        }
        $buyprice= $xml->xpath('//row[@buysell="b"][@typeID="'.$typeid.'"]/@price');
        $buyprice=(float)$buyprice[0]->price;
        $buyprice=round($buyprice, 2);
        if (!(is_numeric($buyprice))) {
            $buyprice=0;
        }
        $this->cache[$regionid-$typeid]=array($price,$buyprice);
        return array($price,$buyprice);
    }
}
