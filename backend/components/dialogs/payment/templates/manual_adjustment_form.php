<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Bookly\Backend\Components\Controls\Buttons;
?>
<tr id="bookly-js-adjustment-field" class="collapse">
    <th style="border-left-color:#fff;border-bottom-color:#fff;"></th>
    <th colspan="<?php echo 3 + $show['deposit'] + $show['taxes'] ?>" style="font-weight: normal;">
        <div class="form-group">
            <label for="bookly-js-adjustment-reason"><?php esc_html_e( 'Reason', 'bookly' ) ?></label>
            <textarea class="form-control" id="bookly-js-adjustment-reason"></textarea>
        </div>
        <div class="form-group">
            <label for="bookly-js-adjustment-amount"><?php esc_html_e( 'Amount', 'bookly' ) ?></label>
            <input class="form-control" type="number" step="1" id="bookly-js-adjustment-amount" />
        </div>
        <?php if ( $show['taxes'] ) : ?>
            <div class="form-group">
                <label for="bookly-js-adjustment-tax"><?php esc_html_e( 'Tax', 'bookly' ) ?></label>
                <input class="form-control" type="number" step="1" id="bookly-js-adjustment-tax" />
            </div>
        <?php endif ?>
        <div class="text-right">
            <?php Buttons::render( 'bookly-js-adjustment-cancel', 'btn-default', __( 'Cancel', 'bookly' ) ) ?>
            <?php Buttons::render( 'bookly-js-adjustment-apply', 'btn-success', __( 'Apply', 'bookly' ) ) ?>
        </div>
    </th>
</tr>