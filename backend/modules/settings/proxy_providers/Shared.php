<?php
namespace BooklyPro\Backend\Modules\Settings\ProxyProviders;

use Bookly\Backend\Components\Settings\Menu;
use Bookly\Backend\Modules\Settings\Proxy;
use Bookly\Lib as BooklyLib;
use BooklyPro\Lib;

/**
 * Class Shared
 * @package BooklyPro\Backend\Modules\Settings\ProxyProviders
 */
class Shared extends Proxy\Shared
{
    /**
     * @inheritDoc
     */
    public static function enqueueAssets()
    {
        self::enqueueScripts( array(
            'module' => array( 'js/settings-pro.js' => array( 'bookly-backend-globals' ) ),
        ) );

        wp_localize_script( 'bookly-settings-pro.js', 'BooklyProSettings10n', array(
            'zoomFailed' => esc_html__( 'Zoom connection failed', 'bookly' ),
        ) );
    }

    /**
     * @inheritDoc
     */
    public static function prepareCalendarAppointmentCodes( array $codes, $participants )
    {
        if ( $participants == 'one' ) {
            $codes['client_address'] = __( 'Address of client', 'bookly' );
        }

        return $codes;
    }

    /**
     * @inheritDoc
     */
    public static function prepareCodes( array $codes, $section )
    {
        switch ( $section ) {
            case 'calendar_one_participant':
                $codes['client_address'] = array( 'description' => __( 'Address of client', 'bookly' ) );
                $codes['client_birthday'] = array( 'description' => __( 'Client birthday', 'bookly' ), 'if' => true );
                break;
            case 'calendar_many_participants':
                $codes = array_merge_recursive( $codes, array(
                    'participants' => array(
                        'loop' => array(
                            'codes' => array(
                                'client_birthday' => array( 'description' => __( 'Client birthday', 'bookly' ), 'if' => true ),
                                'number_of_persons' => array( 'description' => __( 'Number of persons', 'bookly' ) ),
                            ),
                        ),
                    ),
                ) );
                break;
            case 'woocommerce':
                $codes = array_merge( $codes, array(
                    'appointment_date' => array( 'description' => __( 'Date of appointment', 'bookly' ), 'if' => true ),
                    'appointment_time' => array( 'description' => __( 'Time of appointment', 'bookly' ), 'if' => true ),
                    'category_name' => array( 'description' => __( 'Name of category', 'bookly' ) ),
                    'number_of_persons' => array( 'description' => __( 'Number of persons', 'bookly' ) ),
                    'service_info' => array( 'description' => __( 'Info of service', 'bookly' ), 'if' => true ),
                    'service_name' => array( 'description' => __( 'Name of service', 'bookly' ) ),
                    'service_price' => array( 'description' => __( 'Price of service', 'bookly' ) ),
                    'staff_info' => array( 'description' => __( 'Info of staff', 'bookly' ), 'if' => true ),
                    'staff_name' => array( 'description' => __( 'Name of staff', 'bookly' ) ),
                ) );
                break;
            case 'customer_address':
                foreach ( Lib\Utils\Common::getAddressFields() as $field => $description ) {
                    $codes[ $field ] = array( 'description' => $description, 'if' => true );
                }
                break;
            case 'google_calendar':
            case 'outlook_calendar':
                $codes = array_merge_recursive( $codes, array(
                    'appointment_date' => array( 'description' => __( 'Date of appointment', 'bookly' ) ),
                    'appointment_notes' => array( 'description' => __( 'Customer notes for appointment', 'bookly' ), 'if' => true ),
                    'appointment_time' => array( 'description' => __( 'Time of appointment', 'bookly' ) ),
                    'booking_number' => array( 'description' => __( 'Booking number', 'bookly' ) ),
                    'category_name' => array( 'description' => __( 'Name of category', 'bookly' ) ),
                    'company_address' => array( 'description' => __( 'Address of company', 'bookly' ) ),
                    'company_name' => array( 'description' => __( 'Name of company', 'bookly' ) ),
                    'company_phone' => array( 'description' => __( 'Company phone', 'bookly' ) ),
                    'company_website' => array( 'description' => __( 'Company web-site address', 'bookly' ) ),
                    'online_meeting_password' => array( 'description' => __( 'Online meeting password', 'bookly' ), 'if' => true ),
                    'online_meeting_start_url' => array( 'description' => __( 'Online meeting start URL', 'bookly' ), 'if' => true ),
                    'online_meeting_url' => array( 'description' => __( 'Online meeting URL', 'bookly' ), 'if' => true ),
                    'service_info' => array( 'description' => __( 'Info of service', 'bookly' ), 'if' => true ),
                    'service_name' => array( 'description' => __( 'Name of service', 'bookly' ) ),
                    'service_price' => array( 'description' => __( 'Price of service', 'bookly' ) ),
                    'staff_email' => array( 'description' => __( 'Email of staff', 'bookly' ) ),
                    'staff_info' => array( 'description' => __( 'Info of staff', 'bookly' ), 'if' => true ),
                    'staff_name' => array( 'description' => __( 'Name of staff', 'bookly' ) ),
                    'staff_phone' => array( 'description' => __( 'Phone of staff', 'bookly' ) ),
                    'internal_note' => array( 'description' => __( 'Internal note', 'bookly' ) ),
                    'participants' => array(
                        'description' => array(
                            __( 'Loop over clients list', 'bookly' ),
                            __( 'Loop over clients list with delimiter', 'bookly' ),
                        ),
                        'loop' => array(
                            'item' => 'participant',
                            'codes' => array(
                                'appointment_notes' => array( 'description' => __( 'Customer notes for appointment', 'bookly' ), 'if' => true ),
                                'client_address' => array( 'description' => __( 'Address of client', 'bookly' ) ),
                                'client_email' => array( 'description' => __( 'Email of client', 'bookly' ), 'if' => true ),
                                'client_first_name' => array( 'description' => __( 'First name of client', 'bookly' ), 'if' => true ),
                                'client_last_name' => array( 'description' => __( 'Last name of client', 'bookly' ), 'if' => true ),
                                'client_name' => array( 'description' => __( 'Full name of client', 'bookly' ) ),
                                'client_phone' => array( 'description' => __( 'Phone of client', 'bookly' ), 'if' => true ),
                                'client_birthday' => array( 'description' => __( 'Client birthday', 'bookly' ), 'if' => true ),
                                'number_of_persons' => array( 'description' => __( 'Number of persons', 'bookly' ) ),
                                'payment_status' => array( 'description' => __( 'Status of payment', 'bookly' ) ),
                                'payment_type' => array( 'description' => __( 'Payment type', 'bookly' ) ),
                                'status' => array( 'description' => __( 'Status of appointment', 'bookly' ) ),
                                'amount_paid' => array( 'description' => __( 'Amount paid', 'bookly' ) ),
                                'amount_due' => array( 'description' => __( 'Amount due', 'bookly' ) ),
                                'total_price' => array( 'description' => __( 'Total price of booking (sum of all cart items after applying coupon)', 'bookly' ) ),
                            ),
                        ),
                    ),
                ) );
                break;
        }

        return $codes;
    }

    /**
     * @inheritDoc
     */
    public static function preparePaymentGatewaySettings( $payment_data )
    {
        $payment_data['paypal'] = self::renderTemplate( 'paypal_settings', array(), false );
        $payment_data['zarin'] = self::renderTemplate( 'zarin_settings' , array(), false );

        return $payment_data;
    }

    /**
     * @inheritDoc
     */
    public static function renderMenuItem()
    {
        Menu::renderItem( 'ووکامرس', 'woo_commerce' );
        Menu::renderItem( 'فیسبوک', 'facebook' );
        if ( BooklyLib\Config::multipleServicesBookingEnabled() ) {
            Menu::renderItem( __( 'Cart', 'bookly' ), 'cart' );
        }
    }

    /**
     * @inheritDoc
     */
    public static function renderTab()
    {
        /** @global \wpdb $wpdb */
        global $wpdb;

        $goods    = array( array( 'id' => 0, 'name' => __( 'Select product', 'bookly' ) ) );
        $query    = 'SELECT ID, post_title FROM ' . $wpdb->posts . ' WHERE post_type = \'product\' AND post_status = \'publish\' ORDER BY post_title';
        $products = $wpdb->get_results( $query );

        foreach ( $products as $product ) {
            $goods[] = array( 'id' => $product->ID, 'name' => $product->post_title );
        }
        self::renderTemplate( 'wc_tab', compact( 'goods' ) );

        self::renderTemplate( 'fb_tab' );

        if ( BooklyLib\Config::multipleServicesBookingEnabled() ) {
            $cart_columns = array(
                'service'  => BooklyLib\Utils\Common::getTranslatedOption( 'bookly_l10n_label_service' ),
                'date'     => __( 'Date', 'bookly' ),
                'time'     => __( 'Time', 'bookly' ),
                'employee' => BooklyLib\Utils\Common::getTranslatedOption( 'bookly_l10n_label_employee' ),
                'price'    => __( 'Price', 'bookly' ),
                'deposit'  => __( 'Deposit', 'bookly' ),
                'tax'      => __( 'Tax', 'bookly' ),
            );
            self::renderTemplate( 'cart_tab', compact( 'cart_columns' ) );
        }
    }

    /**
     * @inheritDoc
     */
    public static function saveSettings( array $alert, $tab, array $params )
    {
        $options = array();
        switch ( $tab ) {
            case 'customers':
                $options = array(
                    'bookly_cst_address_show_fields',
                    'bookly_cst_cancel_action',
                    'bookly_cst_create_account',
                    'bookly_cst_limit_statuses',
                    'bookly_cst_new_account_role',
                    'bookly_cst_required_address',
                    'bookly_cst_required_birthday',
                    'bookly_l10n_cst_address_template'
                );
                break;
            case 'facebook':
                $options = array( 'bookly_fb_app_id' );
                if ( $params['bookly_fb_app_id'] == '' ) {
                    update_option( 'bookly_app_show_facebook_login_button', 0 );
                }
                $alert['success'][] = __( 'Settings saved.', 'bookly' );
                break;
            case 'general':
                $options = array( 'bookly_gen_min_time_prior_booking', 'bookly_gen_min_time_prior_cancel' );
                break;
            case 'google_calendar':
                $alert = Proxy\AdvancedGoogleCalendar::preSaveSettings( $alert, $params );
                $options = array(
                    'bookly_gc_client_id',
                    'bookly_gc_client_secret',
                    'bookly_gc_sync_mode',
                    'bookly_gc_limit_events',
                    'bookly_gc_event_title',
                    'bookly_gc_event_description'
                );
                $alert['success'][] = __( 'Settings saved.', 'bookly' );
                break;
            case 'payments':
                $options = array(
                    'bookly_paypal_enabled',
                    'bookly_paypal_api_username',
                    'bookly_paypal_api_password',
                    'bookly_paypal_api_signature',
                    'bookly_paypal_sandbox',
                    'bookly_paypal_increase',
                    'bookly_paypal_addition',
                    'bookly_paypal_send_tax',

                    'bookly_pmt_zarin',
                    'bookly_pmt_zarin_merchantid',
                    'bookly_pmt_zarin_sandbox',

                );
                break;
            case 'purchase_code':
                $grace_expired = Lib\Config::graceExpired();
                $errors = apply_filters( 'bookly_save_purchase_codes', array(), $params['purchase_code'], null );
                if ( empty ( $errors ) ) {
                    $alert['success'][] = __( 'Settings saved.', 'bookly' );
                    if ( $grace_expired && ! Lib\Config::graceExpired( false ) ) {
                        BooklyLib\Proxy\AdvancedGoogleCalendar::reSync();
                    }
                } else {
                    $alert['error'] = array_merge( $alert['error'], $errors );
                }
                break;
            case 'url':
                $options = array( 'bookly_url_final_step_url', 'bookly_url_cancel_confirm_page_url' );
                break;
            case 'woo_commerce':
                $options = array(
                    'bookly_l10n_wc_cart_info_name',
                    'bookly_l10n_wc_cart_info_value',
                    'bookly_wc_enabled',
                    'bookly_wc_product',
                );
                $alert['success'][] = __( 'Settings saved.', 'bookly' );
                break;
            case 'cart':
                $options = array( 'bookly_cart_show_columns' );
                $alert['success'][] = __( 'Settings saved.', 'bookly' );
                if ( get_option( 'bookly_wc_enabled' ) && $params['bookly_cart_enabled'] ) {
                    $alert['error'][] = sprintf(
                        __( 'To use the cart, disable integration with WooCommerce <a href="%s">here</a>.', 'bookly' ),
                        BooklyLib\Utils\Common::escAdminUrl( \Bookly\Backend\Modules\Settings\Page::pageSlug(), array( 'tab' => 'woo_commerce' ) )
                    );
                }
                break;
            case 'online_meetings':
                $options = array(
                    'bookly_zoom_authentication',
                    'bookly_zoom_jwt_api_key',
                    'bookly_zoom_jwt_api_secret',
                    'bookly_zoom_oauth_client_id',
                    'bookly_zoom_oauth_client_secret',
                );
                // Check current zoom authorization value
                if ( Lib\Config::zoomAuthentication() !== $params['bookly_zoom_authentication'] ) {
                    // Reset zoom authorization to default for all staff
                    BooklyLib\Entities\Staff::query()->update()->set( 'zoom_authentication', Lib\Zoom\Authentication::TYPE_DEFAULT )->execute();
                    $alert['success'][] = __( 'All staff members will use these settings when connecting to Zoom', 'bookly' );
                } else {
                    $alert['success'][] = __( 'Settings saved.', 'bookly' );
                }
                break;
            case 'user_permissions':
                global $wp_roles;
                $all_roles = $wp_roles->roles;
                $roles = apply_filters( 'editable_roles', $all_roles );
                $manage_bookly_appointments = isset( $params['manage_bookly_appointments'] ) ? $params['manage_bookly_appointments'] : array();
                $manage_bookly = isset( $params['manage_bookly'] ) ? $params['manage_bookly'] : array();
                $admin = current_user_can( 'manage_options' );
                foreach ( $roles as $role => $data ) {
                    if ( ! array_key_exists( 'manage_options', $data['capabilities'] ) ) {
                        if ( array_key_exists( 'manage_bookly_appointments', $data['capabilities'] ) && ! in_array( $role, $manage_bookly_appointments ) ) {
                            $wp_roles->remove_cap( $role, 'manage_bookly_appointments' );
                        }
                        if ( ! array_key_exists( 'manage_bookly_appointments', $data['capabilities'] ) && in_array( $role, $manage_bookly_appointments ) ) {
                            $wp_roles->add_cap( $role, 'manage_bookly_appointments' );
                        }
                        if ( $admin ) {
                            if ( array_key_exists( 'manage_bookly', $data['capabilities'] ) && ! in_array( $role, $manage_bookly ) ) {
                                $wp_roles->remove_cap( $role, 'manage_bookly' );
                            }
                            if ( ! array_key_exists( 'manage_bookly', $data['capabilities'] ) && in_array( $role, $manage_bookly ) ) {
                                $wp_roles->add_cap( $role, 'manage_bookly' );
                            }
                        }
                    }
                }
                $alert['success'][] = __( 'Settings saved.', 'bookly' );
                break;
        }

        // Update options.
        foreach ( $options as $option_name ) {
            if ( array_key_exists( $option_name, $params ) ) {
                $value = $params[ $option_name ];
                update_option( $option_name, is_array( $value ) ? $value : trim( $value ) );
                if ( strncmp( $option_name, 'bookly_l10n_', 12 ) === 0 ) {
                    do_action( 'wpml_register_single_string', 'bookly', $option_name, trim( $value ) );
                }
            }
        }

        return $alert;
    }
}