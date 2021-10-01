<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Bookly\Frontend\Modules\Booking\Proxy;
use BooklyPro\Lib\Payment\ZarinPal;
?>
<div class="bookly-gateway-buttons pay-zarin bookly-box bookly-nav-steps" style="display:none">
    <?php ZarinPal::renderECForm( $form_id ); ?>
</div>