<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'bookly_pro_loader' ) ) {
    include_once __DIR__ . '/autoload.php';

    BooklyPro\Lib\Boot::up();
}