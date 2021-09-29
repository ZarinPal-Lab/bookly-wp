<?php
namespace BooklyPro\Backend\Components\Appearance\ProxyProviders;

use Bookly\Backend\Components\Appearance\Proxy;
use Bookly\Lib as BooklyLib;
use BooklyPro\Lib;

/**
 * Class Local
 * @package BooklyPro\Backend\Modules\Appointments\ProxyProviders
 */
class Local extends Proxy\Pro
{
    /**
     * @inheritdoc
     */
    public static function renderAddress()
    {
        $address_is_required = BooklyLib\Config::addressRequired();
        $address             = array();
        foreach ( Lib\Utils\Common::getDisplayedAddressFields() as $field_name => $field ) {
            $labels = array( 'bookly_l10n_label_' . $field_name );
            if ( $address_is_required ) {
                $labels[] = 'bookly_l10n_required_' . $field_name;
            }
            $id             = 'bookly-js-address-' . $field_name;
            $address[ $id ] = $labels;
        }
        self::renderTemplate( 'address', compact( 'address' ) );
    }

    /**
     * @inheritdoc
     */
    public static function renderBirthday()
    {
        // Render HTML.
        $fields = array();
        foreach ( BooklyLib\Utils\DateTime::getDatePartsOrder() as $type ) {
            $fields[] = self::_renderEditableField( $type );
        }

        self::renderTemplate( 'birthday', compact( 'fields' ) );
    }

    /**
     * Render single editable field of given type.
     *
     * @param string $type
     *
     * @return string
     */
    protected static function _renderEditableField( $type )
    {
        $editable = array( 'bookly_l10n_label_birthday_' . $type, 'bookly_l10n_option_' . $type, 'bookly_l10n_required_' . $type );
        $empty    = get_option( 'bookly_l10n_option_' . $type );
        $options  = array();

        switch ( $type ) {
            case 'day':
                $editable[] = 'bookly_l10n_invalid_day';
                $options    = Lib\Utils\Common::dayOptions();
                break;
            case 'month':
                $options = Lib\Utils\Common::monthOptions();
                break;
            case 'year':
                $options = Lib\Utils\Common::yearOptions();
                break;
        }

        return self::renderTemplate( 'birthday_fields', compact( 'type', 'editable', 'empty', 'options' ), false );
    }
}