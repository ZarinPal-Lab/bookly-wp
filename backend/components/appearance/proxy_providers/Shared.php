<?php
namespace BooklyPro\Backend\Components\Appearance\ProxyProviders;

use Bookly\Backend\Components\Appearance\Proxy;

/**
 * Class Shared
 * @package BooklyPro\Backend\Modules\Appearance\ProxyProviders
 */
class Shared extends Proxy\Shared
{
    /**
     * @inheritdoc
     */
    public static function prepareCodes( array $codes )
    {
        return array_merge( $codes, array(
            'online_meeting_url' => array( 'description' => __( 'Online meeting URL', 'bookly' ), 'if' => true, 'flags' => array( 'step' => 8, 'extra_codes' => true ) ),
            'online_meeting_password' => array( 'description' => __( 'Online meeting password', 'bookly' ), 'if' => true, 'flags' => array( 'step' => 8, 'extra_codes' => true ) ),
            'online_meeting_join_url' => array( 'description' => __( 'Online meeting join URL', 'bookly' ), 'if' => true, 'flags' => array( 'step' => 8, 'extra_codes' => true ) ),
        ) );
    }

}