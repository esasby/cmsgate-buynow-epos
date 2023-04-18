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
use esas\cmsgate\descriptors\ModuleDescriptor;
use esas\cmsgate\descriptors\VendorDescriptor;
use esas\cmsgate\descriptors\VersionDescriptor;
use esas\cmsgate\epos\view\client\CompletionPageEpos;
use esas\cmsgate\epos\view\client\CompletionPanelEposBuyNow;
use esas\cmsgate\epos\view\client\HROFactoryBuyNow;
use esas\cmsgate\epos\view\HROFactoryCmsgateBuyNowEpos;
use esas\cmsgate\epos\view\HROFactoryEpos;
use esas\cmsgate\epos\view\HROFactoryEposBuyNow;
use esas\cmsgate\utils\URLUtils;
use esas\cmsgate\view\admin\AdminViewFields;
use esas\cmsgate\view\admin\ConfigFormBuyNow;
use Exception;

class RegistryEposBuyNow extends RegistryEpos
{
    public function __construct()
    {
        $this->cmsConnector = new CmsConnectorByNow();
        $this->paysystemConnector = new PaysystemConnectorEpos();
        $this->registerService(BridgeConnector::BRIDGE_CONNECTOR_SERVICE_NAME, new BridgeConnectorEposBuyNow());
        $this->registerService(HROFactoryEpos::class, new HROFactoryEposBuyNow());
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
        return URLUtils::getCurrentURLNoParams();
    }

    public function createModuleDescriptor()
    {
        return new ModuleDescriptor(
            "cmsgate-buynow-epos",
            new VersionDescriptor("1.17.1", "2022-03-28"),
            "BuyNow EPOS",
            "https://bitbucket.org/esasby/cmsgate-buynow-epos/src/master/",
            VendorDescriptor::esas(),
            "Выставление пользовательских счетов в ЕРИП"
        );
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

    protected function createHROFactory() {
        return new HROFactoryCmsgateBuyNowEpos();
    }
}