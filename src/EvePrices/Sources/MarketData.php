<?php
namespace EvePrices\Sources;

class MarketData
{

    private $userid;

    public function __construct($userid)
    {
        $this->userid=$userid;
    }

    public function returnPrice($typeid, $regionid)
    {
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
        return array($price,$buyprice);
    }

    public function returnPriceArray($typeids, $regionid)
    {
        $priceArray=array();
        $url="http://api.eve-marketdata.com/api/item_prices2.xml?char_name=".$this->userid
        ."&buysell=a&region_ids=".$regionid."&type_ids=".join(",", $typeids);
        $pricexml=file_get_contents($url);
        $xml=new \SimpleXMLElement($pricexml);
        foreach ($typeids as $typeid) {
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
            $priceArray[$typeid]=array($price,$buyprice);
        }
        return $priceArray;
    }

    public function populateArray($inputarray, $regionid)
    {
        $typeids=array();
        $populatedArray=array();
        foreach ($inputarray as $entry) {
            $typeids[]=$entry['typeid'];
        }
        $pricearray=$this->returnPriceArray($typeids, $regionid);
        foreach ($inputarray as $entry) {
            $entry['price']=$pricearray[$entry['typeid']];
            $populatedArray[]=$entry;
        }
        return $populatedArray;
    }
}
