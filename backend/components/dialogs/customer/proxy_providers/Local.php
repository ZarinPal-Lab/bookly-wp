<?php
namespace BooklyPro\Backend\Components\Dialogs\Customer\ProxyProviders;

use Bookly\Backend\Components\Dialogs\Customer\Edit\Proxy;

/**
 * Class Local
 * @package BooklyPro\Backend\Components\Dialogs\Customer\ProxyProviders
 */
class Local extends Proxy\Pro
{
    /**
     * @inheritdoc
     */
    public static function renderCustomerDialogAddress()
    {
        self::renderTemplate( 'address' );
    }

    /**
     * @inheritdoc
     */
    public static function renderCustomerDialogBirthday()
    {
        self::renderTemplate( 'birthday' );
    }
}