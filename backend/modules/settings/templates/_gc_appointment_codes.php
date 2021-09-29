<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Bookly\Backend\Modules\Settings\Proxy;

$codes = array(
    'appointment_date'         => __( 'Date of appointment', 'bookly' ),
    'appointment_time'         => __( 'Time of appointment', 'bookly' ),
    'booking_number'           => __( 'Booking number', 'bookly' ),
    'category_name'            => __( 'Name of category', 'bookly' ),
    'company_address'          => __( 'Address of company', 'bookly' ),
    'company_name'             => __( 'Name of company', 'bookly' ),
    'company_phone'            => __( 'Company phone', 'bookly' ),
    'company_website'          => __( 'Company web-site address', 'bookly' ),
    'online_meeting_password'  => __( 'Online meeting password', 'bookly' ),
    'online_meeting_start_url' => __( 'Online meeting start URL', 'bookly' ),
    'online_meeting_url'       => __( 'Online meeting URL', 'bookly' ),
    'service_info'             => __( 'Info of service', 'bookly' ),
    'service_name'             => __( 'Name of service', 'bookly' ),
    'service_price'            => __( 'Price of service', 'bookly' ),
    'staff_email'              => __( 'Email of staff', 'bookly' ),
    'staff_info'               => __( 'Info of staff', 'bookly' ),
    'staff_name'               => __( 'Name of staff', 'bookly' ),
    'staff_phone'              => __( 'Phone of staff', 'bookly' ),
);

$codes = Proxy\Shared::prepareCalendarAppointmentCodes( $codes, 'many' );

echo Bookly\Lib\Utils\Codes::tableHtml( $codes );