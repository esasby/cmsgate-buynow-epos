<?php
namespace esas\cmsgate\epos;

use esas\cmsgate\BridgeConnectorBuyNow;
use esas\cmsgate\properties\PropertiesBuyNow;
use PDO;

class BridgeConnectorEposBuyNow extends BridgeConnectorBuyNow
{
    public function getPDO()
    {
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

    public function isSandbox()
    {
        return PropertiesBuyNow::fromRegistry()->isSandbox();
    }

    protected function createMerchantService() {
        return new MerchantServiceEposBuyNow();
    }
}