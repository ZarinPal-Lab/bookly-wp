<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use BooklyPro\Lib;

foreach ( Lib\Utils\Common::getDisplayedAddressFields() as $field_name => $field ) : ?>
    <div class="form-group">
        <label for="<?php echo $field_name ?>"><?php esc_html_e( get_option( 'bookly_l10n_label_' . $field_name ), 'bookly' ) ?></label>
        <input class="form-control" type="text" ng-model="form.<?php echo $field_name ?>" id="<?php echo $field_name ?>"/>
    </div>
<?php endforeach ?>