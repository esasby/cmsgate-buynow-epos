<?php

use esas\cmsgate\BridgeConnector;
use esas\cmsgate\controllers\admin\AdminControllerBuyNow;
use esas\cmsgate\controllers\client\ClientControllerBuyNowBasket;
use esas\cmsgate\epos\controllers\ControllerEposCallback;
use esas\cmsgate\epos\controllers\ControllerEposCompletionPage;
use esas\cmsgate\epos\controllers\ControllerEposInvoiceAdd;
use esas\cmsgate\protocol\RequestParamsBuyNow;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\SessionUtilsBridge;
use esas\cmsgate\utils\StringUtils;
use esas\cmsgate\utils\Logger as LoggerCms;

require_once((dirname(__FILE__)) . '/src/init.php');

$request = $_SERVER['REDIRECT_URL'];
const PATH_BASKET_VIEW = '/basket/view';
const PATH_BASKET_PAY = '/basket/pay';

const PATH_INVOICE_VIEW = '/invoice/view';
const PATH_INVOICE_CALLBACK = '/invoice/callback';

$logger = LoggerCms::getLogger('index');
if (strpos($request, 'admin') !== false) {
    $controller = new AdminControllerBuyNow();
    $controller->process();
} else if (strpos($request, 'basket') !== false) {
    $controller = new ClientControllerBuyNowBasket();
    $controller->process();
} elseif (strpos($request, PATH_INVOICE_VIEW) !== false) {
    $uuid = RequestParamsBuyNow::getOrderId();
    SessionUtilsBridge::setOrderCacheUUID($uuid);
    $orderWrapper = Registry::getRegistry()->getOrderWrapperForCurrentUser();
    $controller = new ControllerEposCompletionPage();
    $completeionPage = $controller->process($orderWrapper);
    $completeionPage->render();
} elseif (strpos($request, PATH_INVOICE_CALLBACK) !== false) {
    $controller = new ControllerEposCallback();
    $controller->process();
} else {
    http_response_code(404);
    return;
}



if (strpos($request, 'order') !== false) {
    try {
//        $logger->info('Got request from Tilda: ' . JSONUtils::encodeArrayAndMask($_REQUEST, ["ps_iii_client_secret"]));
        if (StringUtils::endsWith($request, PATH_INVOICE_ADD)) {
            // приходится сохрянть заказ где-то в кэше, для возможнсоти повторного отображения страницы в случае возврата с webpay
            BridgeConnector::fromRegistry()->getShopConfigService()->checkAuthAndLoadConfig($_REQUEST);
            BridgeConnector::fromRegistry()->getOrderCacheService()->addSessionOrderCache($_REQUEST);
            $orderWrapper = Registry::getRegistry()->getOrderWrapperForCurrentUser();
            if ($orderWrapper->getExtId() == null || $orderWrapper->getExtId() == '') {
                $controller = new ControllerEposInvoiceAdd();
                $controller->process($orderWrapper);
            }
            $controller = new ControllerEposCompletionPage();
            $completeionPage = $controller->process($orderWrapper);
            $completeionPage->render();
        } elseif (strpos($request, PATH_INVOICE_VIEW) !== false) {
            $uuid = RequestParamsBuyNow::getOrderId();
            SessionUtilsBridge::setOrderCacheUUID($uuid);
            $orderWrapper = Registry::getRegistry()->getOrderWrapperForCurrentUser();
            $controller = new ControllerEposCompletionPage();
            $completeionPage = $controller->process($orderWrapper);
            $completeionPage->render();
        } elseif (strpos($request, PATH_INVOICE_CALLBACK) !== false) {
            $controller = new ControllerEposCallback();
            $controller->process();
        } else {
            http_response_code(404);
            return;
        }
    } catch (Exception $e) {
        $logger->error("Exception", $e);
        $errorPage = Registry::getRegistry()->getCompletionPage(
            Registry::getRegistry()->getOrderWrapperForCurrentUser(),
            null
        );
        $errorPage->render();
    } catch (Throwable $e) {
        $logger->error("Exception", $e);
        $errorPage = Registry::getRegistry()->getCompletionPage(
            Registry::getRegistry()->getOrderWrapperForCurrentUser(),
            null
        );
        $errorPage->render();
    }
}