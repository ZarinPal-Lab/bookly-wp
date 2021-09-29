<?php
namespace BooklyPro\Backend\Components\Dialogs\Appointment\CustomerDetails\ProxyProviders;

use Bookly\Backend\Components\Dialogs\Appointment\CustomerDetails\Proxy;
use Bookly\Lib as BooklyLib;

/**
 * Class Local
 * @package BooklyPro\Backend\Components\Dialogs\Appointment\CustomerDetails\ProxyProviders
 */
class Local extends Proxy\Pro
{
    /**
     * @inheritdoc
     */
    public static function renderTimeZoneSwitcher()
    {
        self::renderTemplate('time_zone_switcher');
    }
}