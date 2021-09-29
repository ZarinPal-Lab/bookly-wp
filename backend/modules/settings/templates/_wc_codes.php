<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Bookly\Backend\Modules\Settings\Proxy;

$codes = array(
    'appointment_date'  => __( 'Date of appointment', 'bookly' ),
    'appointment_time'  => __( 'Time of appointment', 'bookly' ),
    'category_name'     => __( 'Name of category', 'bookly' ),
    'number_of_persons' => __( 'Number of persons', 'bookly' ),
    'service_info'      => __( 'Info of service', 'bookly' ),
    'service_name'      => __( 'Name of service', 'bookly' ),
    'service_price'     => __( 'Price of service', 'bookly' ),
    'staff_info'        => __( 'Info of staff', 'bookly' ),
    'staff_name'        => __( 'Name of staff', 'bookly' ),
);

$codes = Proxy\Shared::prepareWooCommerceCodes( $codes );

echo Bookly\Lib\Utils\Codes::tableHtml( $codes );