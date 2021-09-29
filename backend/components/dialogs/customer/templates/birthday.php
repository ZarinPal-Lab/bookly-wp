<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="form-group" style="position: relative;">
    <label for="birthday"><?php esc_html_e( 'Date of birth', 'bookly' ) ?></label>
    <input class="form-control bookly-js-shamciDate" readonly onclick="jQuery('#birthday').click();" type="text" ng-model="form.shamciDate" />
    <input date-range-picker style="position: absolute;z-index: -26;top: 32px;" class="form-control" type="text" ng-model=form.birthday id="birthday" options="{parentEl:'#bookly-customer-dialog',singleDatePicker:true,showDropdowns:true,autoUpdateInput:false,locale:datePickerOptions}" autocomplete="off"/>
</div>