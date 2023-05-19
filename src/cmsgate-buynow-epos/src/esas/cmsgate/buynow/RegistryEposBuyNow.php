<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 01.10.2018
 * Time: 12:05
 */

namespace esas\cmsgate\buynow;

use esas\cmsgate\bridge\service\OrderService;
use esas\cmsgate\buynow\hro\AdminLoginPageHROTunerBynowEpos;
use esas\cmsgate\buynow\service\OrderServiceBuyNowEpos;
use esas\cmsgate\buynow\service\PDOServiceBuyNowEpos;
use esas\cmsgate\buynow\service\ServiceProviderBuyNow;
use esas\cmsgate\buynow\view\admin\ConfigFormBuyNow;use esas\cmsgate\descriptors\ModuleDescriptor;
use esas\cmsgate\descriptors\VendorDescriptor;
use esas\cmsgate\descriptors\VersionDescriptor;
use esas\cmsgate\epos\ConfigFieldsEpos;
use esas\cmsgate\epos\hro\client\CompletionPanelEposHRO;
use esas\cmsgate\epos\hro\client\CompletionPanelEposHRO_v2;
use esas\cmsgate\epos\hro\sections\FooterSectionCompanyInfoHROTunerEpos;
use esas\cmsgate\epos\hro\sections\HeaderSectionLogoContactsHROTunerEpos;
use esas\cmsgate\epos\PaysystemConnectorEpos;
use esas\cmsgate\epos\RegistryEpos;
use esas\cmsgate\hro\HROManager;
use esas\cmsgate\hro\pages\AdminLoginPageHRO;
use esas\cmsgate\hro\sections\FooterSectionCompanyInfoHRO;
use esas\cmsgate\hro\sections\HeaderSectionLogoContactsHRO;
use esas\cmsgate\service\PDOService;
use esas\cmsgate\utils\URLUtils;
use esas\cmsgate\view\admin\AdminViewFields;

class RegistryEposBuyNow extends RegistryEpos
{
    public function __construct()
    {
        $this->cmsConnector = new CmsConnectorByNow();
        $this->paysystemConnector = new PaysystemConnectorEpos();
    }

    /**
     * Переопределение для упрощения типизации
     * @return RegistryEposBuyNow
     */
    public static function getRegistry()
    {
        return parent::getRegistry();
    }

    public function init() {
        parent::init();

        $this->registerServicesFromProvider(new ServiceProviderBuyNow());
        $this->registerService(OrderService::class, new OrderServiceBuyNowEpos());
        $this->registerService(PDOService::class, new PDOServiceBuyNowEpos());

        HROManager::fromRegistry()->addImplementation(CompletionPanelEposHRO::class, CompletionPanelEposHRO_v2::class);
        HROManager::fromRegistry()->addTuner(AdminLoginPageHRO::class, AdminLoginPageHROTunerBynowEpos::class);
        HROManager::fromRegistry()->addTuner(FooterSectionCompanyInfoHRO::class, FooterSectionCompanyInfoHROTunerEpos::class);
        HROManager::fromRegistry()->addTuner(HeaderSectionLogoContactsHRO::class, HeaderSectionLogoContactsHROTunerEpos::class);
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
        return new PropertiesBuyNowEpos();
    }
}