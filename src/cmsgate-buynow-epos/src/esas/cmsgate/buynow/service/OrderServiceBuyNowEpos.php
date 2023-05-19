<?php


namespace esas\cmsgate\buynow\service;


use DateTime;
use esas\cmsgate\epos\wrappers\ConfigWrapperEpos;

class OrderServiceBuyNowEpos extends OrderServiceBuyNow
{
    /**
     * @inheritDoc
     */
    public function getOrderExpirationDate() {
        return $date = (new DateTime('NOW'))->modify('+' . ConfigWrapperEpos::fromRegistry()->getDueInterval() . ' day');
    }
}