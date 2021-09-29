<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$time_zone_options = wp_timezone_choice( null );
?>
<div class="form-group">
    <label for="bookly-customer-time-zone"><?php esc_html_e( 'Timezone', 'bookly' ) ?></label>
    <select class="form-control custom-select" id="bookly-customer-time-zone">
        <?php echo $time_zone_options ?>
    </select>
</div>