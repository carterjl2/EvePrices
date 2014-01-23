<?php
namespace EvePrices;

class EvePrices
{

    const VERSION="1.0.0";

    private $region;
    private $priceSource;


    public function __construct($args = array('source'=>'redis','region'=>10000002,'host'=>'127.0.0.1','port'=>6379,'scheme'=>'tcp'))
    {
        switch ($args['source']) {
            case 'redis':
                $this->$priceSource=new \EvePrices\Sources\Redis($args['host'],$args['port'],$args['scheme']);
                break;
            case 'populatedmemcached':
                $this->$priceSource=new \EvePrices\Sources\Memcached($args['host'],$args['port']);
                break;
            case 'mysql':
                $this->$priceSource=new \EvePrices\Sources\Mysql($args['dbh'],$args['selltable'],$args['buytable']);
                break;
            case 'marketdata':
                $this->$priceSource=new \EvePrices\Sources\MarketData($args['userid']);
                break;
            default:
                throw new \Exception("EvePrices doesn't understand source type $args['source']");
        }
        if (isset($args['region']) and is_numeric($args['region'])) {
            $this->$region=$args['region'];
        } else {
            $this->$region=10000002;
        }
    }

    public function setRegion($regionid)
    {
        if (isset($regionid) and is_numeric($regionid)) {
            $this->region=$regionid;
        } else {
            throw new \Exception("regionid must be a number");
        }
    }

    public function returnprice($typeid, $regionid = null)
    {
        return $this->$priceSource($typeid, $regionid ?: $this->$region);
    }
}
