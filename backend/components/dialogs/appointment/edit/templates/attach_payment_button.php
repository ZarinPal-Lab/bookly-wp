<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<button type="button" class="btn btn-default px-2 py-1" ng-click="attachPaymentModal(customer, $index)" ng-show="! customer.payment_id && ! customer.payment_create" popover="<?php esc_attr_e( 'Attach payment', 'bookly' ) ?>">
    <span class="fas fa-fw fa-search-dollar"></span>
</button>
