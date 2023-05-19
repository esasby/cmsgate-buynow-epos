<?php
namespace esas\cmsgate\buynow;

use esas\cmsgate\bridge\service\OrderService;
use esas\cmsgate\buynow\dao\BasketBuyNowRepository;
use esas\cmsgate\buynow\protocol\RequestParamsBuyNow;
use esas\cmsgate\epos\HooksEpos;
use esas\cmsgate\epos\protocol\EposCallbackRq;
use esas\cmsgate\epos\protocol\EposInvoiceAddRs;
use esas\cmsgate\wrappers\OrderWrapper;

class HooksEposBuyNow extends HooksEpos
{
    public function onCallbackRqRead(EposCallbackRq $rq) {
        parent::onCallbackRqRead($rq);
        OrderService::fromRegistry()->loadSessionOrderByExtId($rq->getInvoiceId());
    }

    public function onInvoiceAddSuccess(OrderWrapper $orderWrapper, EposInvoiceAddRs $resp) {
        parent::onInvoiceAddSuccess($orderWrapper, $resp);
        BasketBuyNowRepository::fromRegistry()->incrementCheckoutCount(RequestParamsBuyNow::getBasketId());
    }
}