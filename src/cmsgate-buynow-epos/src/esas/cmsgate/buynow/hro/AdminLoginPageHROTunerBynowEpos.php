<?php


namespace esas\cmsgate\buynow\hro;


use esas\cmsgate\bridge\view\client\RequestParamsBridge;
use esas\cmsgate\buynow\PropertiesBuyNowEpos;
use esas\cmsgate\Registry;
use esas\cmsgate\hro\HRO;
use esas\cmsgate\hro\HROTuner;
use esas\cmsgate\hro\pages\AdminLoginPageHRO;

class AdminLoginPageHROTunerBynowEpos implements HROTuner
{
    /**
     * @param AdminLoginPageHRO $hroBuilder
     * @return HRO|void
     */
    public function tune($hroBuilder) {
        return $hroBuilder
            ->setLoginField(RequestParamsBridge::LOGIN_FORM_LOGIN, "Client ID")
            ->setPasswordField(RequestParamsBridge::LOGIN_FORM_PASSWORD, 'Secret')
            ->setSandbox(PropertiesBuyNowEpos::fromRegistry()->isSandbox())
            ->setMessage("Login to BuyNow " . Registry::getRegistry()->getPaysystemConnector()->getPaySystemConnectorDescriptor()->getPaySystemMachinaName());
    }
}