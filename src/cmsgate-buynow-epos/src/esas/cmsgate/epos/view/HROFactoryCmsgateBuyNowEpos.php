<?php


namespace esas\cmsgate\epos\view;

use esas\cmsgate\epos\BridgeConnectorEposBuyNow;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\htmlbuilder\hro\HROFactoryCmsGate;

class HROFactoryCmsgateBuyNowEpos extends HROFactoryCmsGate
{
    public function createFooterSectionCompanyInfoBuilder() {
        return HROFactoryEpos::fromRegistry()->createFooterSectionCompanyInfoBuilder();
    }

    public function createHeaderSectionLogoContactsBuilder() {
        return HROFactoryEpos::fromRegistry()->createHeaderSectionLogoContactsBuilder();
    }

    public function createAdminLoginPageBuilder() {
        $loginPageBuilder = HROFactoryEpos::fromRegistry()->createAdminLoginPageBuilder();
        $loginPageBuilder
            ->setSandbox(BridgeConnectorEposBuyNow::fromRegistry()->isSandbox())
            ->setMessage("Login to BuyNow " . Registry::getRegistry()->getPaysystemConnector()->getPaySystemConnectorDescriptor()->getPaySystemMachinaName());
        return $loginPageBuilder;
    }
}