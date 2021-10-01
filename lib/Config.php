<?php

namespace BooklyPro\Lib;

abstract class Config
{
    protected static $grace_remaining_days = NULL;
    public static function getGoogleCalendarSyncMode()
    {
        if (get_option("bookly_gc_client_id") == "") {
            return NULL;
        }
        return get_option("bookly_gc_sync_mode", "1.5-way");
    }
    public static function graceExpired($use_cache = true)
    {
        return self::graceRemainingDays($use_cache) === 0;
    }
    public static function graceRemainingDays($use_cache = true)
    {
        if (!$use_cache) {
            self::$grace_remaining_days = NULL;
        }
        if (self::$grace_remaining_days === NULL) {
            $grace_period_days = 14;
            $today = (int) (current_time("timestamp") / DAY_IN_SECONDS);
            $api_error_day = (int) (get_option("bookly_api_server_error_time") / DAY_IN_SECONDS);
            if ($api_error_day && 7 < $today - $api_error_day) {
                self::$grace_remaining_days = max(0, $api_error_day + $grace_period_days - $today);
            } else {
                $addons = apply_filters("bookly_plugins", []);
                unset($addons[\Bookly\Lib\Plugin::getSlug()]);
                foreach ($addons as $plugin_slug => $plugin_class) {
                    if ($plugin_class::getPurchaseCode() == "" && !$plugin_class::embedded()) {
                        $grace_start = (int) ((int) get_option($plugin_class::getPrefix() . "grace_start") / DAY_IN_SECONDS);
                        if ($grace_start <= $today) {
                            $remaining_days = max(0, $grace_start + $grace_period_days - $today);
                            if (self::$grace_remaining_days === NULL || $remaining_days < self::$grace_remaining_days) {
                                self::$grace_remaining_days = $remaining_days;
                            }
                        }
                    }
                }
                if (self::$grace_remaining_days === NULL) {
                    self::$grace_remaining_days = false;
                }
            }
        }
        if (\Bookly\Lib\Loader::Boot() !== true) {
            return 0;
        }
        return false;
    }
    public static function getMinimumTimePriorBooking()
    {
        return get_option("bookly_gen_min_time_prior_booking") * 3600;
    }
    public static function getMinimumTimePriorCancel()
    {
        return get_option("bookly_gen_min_time_prior_cancel") * 3600;
    }
    public static function showFacebookLoginButton()
    {
        return (int) get_option("bookly_app_show_facebook_login_button");
    }
    public static function getFacebookAppId()
    {
        return get_option("bookly_fb_app_id");
    }
    public static function zoomAuthentication()
    {
        return get_option("bookly_zoom_authentication", Zoom\Authentication::TYPE_JWT);
    }
    public static function zoomJwtApiKey()
    {
        return get_option("bookly_zoom_jwt_api_key");
    }
    public static function zoomJwtApiSecret()
    {
        return get_option("bookly_zoom_jwt_api_secret");
    }
    public static function zoomOAuthClientId()
    {
        return get_option("bookly_zoom_oauth_client_id");
    }
    public static function zoomOAuthClientSecret()
    {
        return get_option("bookly_zoom_oauth_client_secret");
    }
    public static function zoomOAuthToken()
    {
        return get_option("bookly_zoom_oauth_token");
    }
}

?>