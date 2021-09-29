<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class=form-group ng-show="form.online_meeting.url || form.service && form.service.online_meetings != 'off'">
    <label><?php esc_html_e( 'Online meeting', 'bookly' ) ?></label>
    <div ng-if="form.online_meeting.url">
        <div class="btn btn-default disabled d-flex align-items-center" style="opacity:1;cursor:default">
            <a href="{{form.online_meeting.url}}" target="_blank">{{form.online_meeting.url}}</a>
            <i class="fas fa-external-link-alt fa-fw fa-sm text-muted ml-1"></i>
            <a ng-hide="form.online_meeting.copied" ng-click="copyOnlineMeetingUrl()" class="far fa-copy fa-fw text-secondary text-decoration-none ml-auto" href title="<?php esc_attr_e( 'Copy to clipboard', 'bookly' ) ?>"></a>
            <small ng-show="form.online_meeting.copied" class="text-muted ml-auto"><?php esc_html_e( 'copied', 'bookly' ) ?></small>
        </div>
        <small class="text-muted"><?php esc_html_e( 'This link can be inserted into notifications with {online_meeting_url} code', 'bookly' ) ?></small>
    </div>
    <div ng-if="!form.online_meeting.url">
        <small class="text-muted"><?php esc_html_e( 'Save appointment to create a meeting', 'bookly' ) ?></small>
    </div>
</div>