<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Bookly\Backend\Components\Appearance\Editable;
use Bookly\Backend\Modules\Appearance\Proxy;
/**
 * @var array $address
 */
?>
<div id="bookly-js-address"<?php if ( get_option( 'bookly_app_show_address' ) ) : ?> style="display:none;"<?php endif ?>>
    <div class="bookly-box bookly-bold">
        <?php Editable::renderText( 'bookly_l10n_info_address' ) ?>
    </div>

    <?php Proxy\GoogleMapsAddress::renderGoogleMaps() ?>

    <?php foreach ( $address as $id => $labels ): ?>
    <div class="bookly-box" id="<?php echo $id ?>">
        <div class="bookly-form-group">
            <?php Editable::renderLabel( $labels ) ?>
            <div>
                <input type="text" value="" maxlength="255" />
            </div>
        </div>
    </div>
    <?php endforeach ?>
</div>