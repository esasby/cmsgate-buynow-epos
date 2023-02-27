<?php


namespace esas\cmsgate\epos;


use esas\cmsgate\BridgeConnector;
use esas\cmsgate\BridgeConnectorBuyNow;
use esas\cmsgate\epos\protocol\EposCallbackRq;
use esas\cmsgate\epos\protocol\EposInvoiceAddRs;
use esas\cmsgate\protocol\RequestParamsBuyNow;
use esas\cmsgate\wrappers\OrderWrapper;

class HooksEposBuyNow extends HooksEpos
{
    public function onCallbackRqRead(EposCallbackRq $rq) {
        parent::onCallbackRqRead($rq);
        BridgeConnector::fromRegistry()->getOrderCacheService()->loadSessionOrderCacheByExtId($rq->getInvoiceId());
    }

    public function onInvoiceAddSuccess(OrderWrapper $orderWrapper, EposInvoiceAddRs $resp) {
        parent::onInvoiceAddSuccess($orderWrapper, $resp);
        BridgeConnectorBuyNow::fromRegistry()->getBuyNowBasketRepository()->incrementCheckoutCount(RequestParamsBuyNow::getBasketId());
    }
}