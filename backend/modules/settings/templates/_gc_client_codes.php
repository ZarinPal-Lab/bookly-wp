<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Bookly\Backend\Modules\Settings\Proxy;

$codes = array(
    'appointment_notes' => __( 'Customer notes for appointment', 'bookly' ),
    'client_email'      => __( 'Email of client', 'bookly' ),
    'client_first_name' => __( 'First name of client', 'bookly' ),
    'client_last_name'  => __( 'Last name of client', 'bookly' ),
    'client_name'       => __( 'Full name of client', 'bookly' ),
    'client_phone'      => __( 'Phone of client', 'bookly' ),
    'payment_status'    => __( 'Status of payment', 'bookly' ),
    'payment_type'      => __( 'Payment type', 'bookly' ),
    'status'            => __( 'Status of appointment', 'bookly' ),
    'total_price'       => __( 'Total price of booking (sum of all cart items after applying coupon)', 'bookly' ),
);

$codes = Proxy\Shared::prepareCalendarAppointmentCodes( $codes, 'one' );

echo Bookly\Lib\Utils\Codes::tableHtml( $codes );