<?php


namespace esas\cmsgate\buynow\service;


use esas\cmsgate\buynow\properties\PropertiesBuyNow;
use esas\cmsgate\service\PDOService;
use PDO;

class PDOServiceBuyNowEpos extends PDOService
{

    public function getPDO($repositoryClass = null) {
        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        return new PDO(
            PropertiesBuyNow::fromRegistry()->getPDO_DSN(),
            PropertiesBuyNow::fromRegistry()->getPDOUsername(),
            PropertiesBuyNow::fromRegistry()->getPDOPassword(),
            $opt);
    }
}