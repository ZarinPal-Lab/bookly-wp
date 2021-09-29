<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class=form-group ng-show="form.service && !form.service.id">
    <label for="bookly-custom-service-name"><?php esc_html_e( 'Custom service name', 'bookly' ) ?></label>
    <input type="text" id="bookly-custom-service-name" class="form-control" ng-model="form.custom_service_name" />
    <p class="text-danger" my-slide-up="errors.custom_service_name_required">
        <?php esc_html_e( 'Please enter a service name', 'bookly' ) ?>
    </p>
</div>

<div class=form-group ng-show="form.service && !form.service.id">
    <label for="bookly-custom-service-price"><?php esc_html_e( 'Custom service price', 'bookly' ) ?></label>
    <input type="number" id="bookly-custom-service-price" class="form-control" ng-model="form.custom_service_price" min="0" step="1" />
</div>