<?php
namespace esas\cmsgate\buynow;

use esas\cmsgate\epos\ConfigFieldsEpos;

class ConfigStorageBuyNowEpos extends ConfigStorageBuyNow
{

    public function getConfigFieldLogin() {
        return ConfigFieldsEpos::iiiClientId();
    }

    public function getConfigFieldPassword() {
        return ConfigFieldsEpos::iiiClientSecret();
    }
}