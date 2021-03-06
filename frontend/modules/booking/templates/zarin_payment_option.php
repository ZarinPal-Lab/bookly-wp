<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Bookly\Lib\Utils\Common;
use Bookly\Lib\Utils\Price;
use Bookly\Lib\Entities\Payment;
?>
<div class="bookly-box bookly-list">
    <label>
        <input type="radio" class="bookly-payment" name="payment-method-<?php echo $form_id ?>" value="zarin"/>
        <span><?php echo Common::getTranslatedOption( 'bookly_l10n_label_pay_zarinpal' ) ?>
            <?php if ( $show_price ) : ?>
                <span class="bookly-js-pay"><?php echo Price::format( $cart_info->getPayNow() ) ?></span>
            <?php endif ?>
        </span>
        <img src="<?php echo plugins_url( 'frontend/resources/images/zarinpal.png', BooklyPro\Lib\Plugin::getMainFile() ) ?>" alt="zarinpal" />
    </label>
    <?php if ( is_array( $payment_status ) && $payment_status['gateway'] == Payment::TYPE_ZARIN && $payment_status['status'] == 'error' ) : ?>
        <div class="bookly-label-error"><?php echo $payment_status['data'] ?></div>
    <?php endif ?>
</div>