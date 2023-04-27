<?php

use esas\cmsgate\buynow\controllers\admin\AdminControllerBuyNow;
use esas\cmsgate\buynow\controllers\client\ClientControllerBuyNow;
use esas\cmsgate\epos\controllers\ControllerEposCallback;
use esas\cmsgate\utils\Logger as LoggerCms;

require_once((dirname(__FILE__)) . '/src/init.php');

$request = $_SERVER['REDIRECT_URL'];

$logger = LoggerCms::getLogger('index');
if (strpos($request, 'admin') !== false) {
    $controller = new AdminControllerBuyNow();
    $controller->process();
} else if (strpos($request, 'baskets') !== false || strpos($request, 'orders') !== false) {
    $controller = new ClientControllerBuyNow();
    $controller->process();
} elseif (strpos($request, 'callback') !== false) {
    $controller = new ControllerEposCallback();
    $controller->process();
} else {
    http_response_code(404);
    return;
}
