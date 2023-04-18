<?php


namespace esas\cmsgate\epos\view;


use esas\cmsgate\epos\view\client\CompletionPanelEposHRO_v2;

class HROFactoryEposBuyNow extends HROFactoryEpos
{
    public function createCompletionPanelEposBuilder() {
        return CompletionPanelEposHRO_v2::builder();
    }
}