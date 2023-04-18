<?php


namespace esas\cmsgate\epos;


use esas\cmsgate\ConfigStorageBuyNow;

class  ConfigStorageBuyNowEpos extends ConfigStorageBuyNow
{

    public function getConfigFieldLogin() {
        return ConfigFieldsEpos::iiiClientId();
    }

    public function getConfigFieldPassword() {
        return ConfigFieldsEpos::iiiClientSecret();
    }
}