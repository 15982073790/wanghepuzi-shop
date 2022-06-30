<?php
namespace MrStock\Business\ServiceSdk\SecretMobile;
interface AbstractMobileSecret
{
    static function encrypt($servicestoken,$mobiles);
    static function decrypt($servicestoken,$secrets);

}