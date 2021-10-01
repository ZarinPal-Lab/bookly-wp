<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*
Plugin Name: افزونه Bookly Pro
Plugin URI: https://www.rtl-theme.com/bookly-wordpress-plugin/
Description: افزونه Bookly Pro به شما امکان می دهد از ویژگی ها و تنظیمات اضافی استفاده کنید و سایر افزونه ها را برای افزونه Bookly نصب کنید.
Version: 4.0
Author: Bookly
Author URI: https://www.rtl-theme.com/bookly-wordpress-plugin/
Text Domain: bookly
Domain Path: /languages
License: Commercial
*/

if ( ! function_exists( 'bookly_pro_loader' ) ) {
    include_once __DIR__ . '/autoload.php';

    BooklyPro\Lib\Boot::up();
}