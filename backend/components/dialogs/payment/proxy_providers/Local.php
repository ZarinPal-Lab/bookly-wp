<?php
namespace BooklyPro\Backend\Components\Dialogs\Payment\ProxyProviders;

use Bookly\Backend\Components\Controls\Buttons;
use Bookly\Backend\Modules\Payments\Proxy;

/**
 * Class Local
 * @package BooklyPro\Backend\Modules\Payments\ProxyProviders
 */
class Local extends Proxy\Pro
{
    /**
     * @inheritdoc
     */
    public static function renderManualAdjustmentButton()
    {
        Buttons::render( 'bookly-js-adjustment-button', 'btn-default', __( 'Manual adjustment', 'bookly' ) );
    }

    /**
     * @inheritdoc
     */
    public static function renderManualAdjustmentForm( array $show )
    {
        static::renderTemplate( 'manual_adjustment_form', compact( 'show' ) );
    }
}