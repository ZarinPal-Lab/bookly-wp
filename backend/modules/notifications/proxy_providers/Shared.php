<?php
namespace BooklyPro\Backend\Modules\Notifications\ProxyProviders;

use Bookly\Backend\Modules\Notifications\Proxy;

/**
 * Class Shared
 * @package BooklyPro\Backend\Modules\Notifications\ProxyProviders
 */
class Shared extends Proxy\Shared
{
    /**
     * @inheritDoc
     */
    public static function prepareNotificationCodes( array $codes, $type )
    {
        $codes['appointment']['online_meeting_url'] = array( 'description' => __( 'Online meeting URL', 'bookly' ) );
        $codes['appointment']['online_meeting_password'] = array( 'description' => __( 'Online meeting password', 'bookly' ) );
        $codes['appointment']['online_meeting_start_url'] = array( 'description' => __( 'Online meeting start URL', 'bookly' ) );
        $codes['appointment']['online_meeting_join_url'] = array( 'description' => __( 'Online meeting join URL', 'bookly' ) );
        $codes['customer']['client_birthday'] = array( 'description' => __( 'Client birthday', 'bookly' ), 'if' => true );
        $codes['staff']['staff_timezone'] = array( 'description' => __( 'Time zone of staff', 'bookly' ), 'if' => true );

        return $codes;
    }
}