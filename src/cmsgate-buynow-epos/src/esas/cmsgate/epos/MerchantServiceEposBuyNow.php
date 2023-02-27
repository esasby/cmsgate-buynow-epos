<?php


namespace esas\cmsgate\epos;


use esas\cmsgate\bridge\MerchantServiceBuyNow;
use esas\cmsgate\epos\view\admin\AdminLoginPageEposBuyNow;

class MerchantServiceEposBuyNow extends MerchantServiceBuyNow
{

    public function createAdminLoginPage() {
        return new AdminLoginPageEposBuyNow();
    }
}