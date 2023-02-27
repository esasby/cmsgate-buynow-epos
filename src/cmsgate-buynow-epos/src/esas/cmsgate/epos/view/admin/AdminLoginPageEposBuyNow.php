<?php


namespace esas\cmsgate\epos\view\admin;


use esas\cmsgate\Registry;
use esas\cmsgate\view\admin\AdminConfigPage;
use esas\cmsgate\view\admin\AdminLoginPage;

class AdminLoginPageEposBuyNow extends AdminLoginPage
{
    protected function getLoginPlaceholder() {
        return "Client ID";
    }

    protected function getPasswordPlaceholder() {
        return "Secret";
    }

    public function loginFormLabel() {
        return "Login to BuyNow " . Registry::getRegistry()->getPaysystemConnector()->getPaySystemConnectorDescriptor()->getPaySystemMachinaName() . AdminConfigPage::elementTestLabel();
    }
}