<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 01.10.2018
 * Time: 12:05
 */

namespace esas\cmsgate\epos;

use esas\cmsgate\BridgeConnector;
use esas\cmsgate\CmsConnectorByNow;
use esas\cmsgate\ConfigStorageBridge;
use esas\cmsgate\ConfigStorageBuyNow;
use esas\cmsgate\descriptors\ModuleDescriptor;
use esas\cmsgate\descriptors\VendorDescriptor;
use esas\cmsgate\descriptors\VersionDescriptor;
use esas\cmsgate\epos\view\client\CompletionPageEpos;
use esas\cmsgate\protocol\RequestParamsBuyNow;
use esas\cmsgate\utils\CMSGateException;
use esas\cmsgate\utils\SessionUtilsBridge;
use esas\cmsgate\utils\URLUtils;
use esas\cmsgate\view\admin\AdminViewFields;
use esas\cmsgate\view\admin\ConfigFormBridge;
use esas\cmsgate\view\admin\ConfigFormBuyNow;
use Exception;

class RegistryEposBuyNow extends RegistryEpos
{
    public function __construct()
    {
        $this->cmsConnector = new CmsConnectorByNow();
        $this->paysystemConnector = new PaysystemConnectorEpos();
        $this->registerService(BridgeConnector::BRIDGE_CONNECTOR_SERVICE_NAME, new BridgeConnectorEposBuyNow());
    }

    /**
     * Переопределение для упрощения типизации
     * @return RegistryEposBuyNow
     */
    public static function getRegistry()
    {
        return parent::getRegistry();
    }

    /**
     * @throws \Exception
     */
    public function createConfigForm()
    {
        $managedFields = $this->getManagedFieldsFactory()->getManagedFieldsOnly(AdminViewFields::CONFIG_FORM_COMMON, [
            ConfigFieldsEpos::eposProcessor(),
            ConfigFieldsEpos::eposServiceProviderCode(),
            ConfigFieldsEpos::eposServiceCode(),
            ConfigFieldsEpos::eposRetailOutletCode(),
            ConfigFieldsEpos::dueInterval(),
//            ConfigFieldsEpos::sandbox(), //buynow is working in single mode
            ConfigFieldsEpos::completionText(),
            ConfigFieldsEpos::instructionsSection(),
            ConfigFieldsEpos::qrcodeSection(),
            ConfigFieldsEpos::webpaySection(),
        ]);
        $configForm = new ConfigFormBuyNow(
            $managedFields
        );
        return $configForm;
    }


    function getUrlWebpay($orderWrapper)
    {
        $currentURL = URLUtils::getCurrentURLNoParams();
        $currentURL = str_replace(PATH_INVOICE_ADD, PATH_INVOICE_VIEW, $currentURL);
        if (strpos($currentURL, PATH_INVOICE_VIEW) !== false)
            return $currentURL . '?' . RequestParamsBuyNow::ORDER_ID . '=' . SessionUtilsBridge::getOrderCacheUUID();
        else
            throw new CMSGateException('Incorrect URL genearation');
    }

    public function createModuleDescriptor()
    {
        return new ModuleDescriptor(
            "cmsgate-buynow-epos",
            new VersionDescriptor("1.17.1", "2022-03-28"),
            "Tilda EPOS",
            "https://bitbucket.org/esasby/cmsgate-buynow-epos/src/master/",
            VendorDescriptor::esas(),
            "Выставление пользовательских счетов в ЕРИП"
        );
    }

    public function getCompletionPanel($orderWrapper)
    {
        return new CompletionPanelEposTilda($orderWrapper);
    }

    /**
     * @param $orderWrapper
     * @param $completionPanel
     * @return CompletionPageEpos
     */
    public function getCompletionPage($orderWrapper, $completionPanel)
    {
        return new CompletionPageEpos($orderWrapper, $completionPanel);
    }

    public function createHooks()
    {
        return new HooksEposBuyNow();
    }

    public function createConfigStorage()
    {
        return new ConfigStorageBuyNowEpos();
    }

    public function createProperties() {
        return new PropertiesEposBuyNow();
    }
}