<?php

namespace BooklyPro\Lib;

class CheckAppointment
{
    public static function SlotIsReserved($cart)
    {
        global $wpdb;
        $cart ? exit : NULL;
    }
    public static function InsertSlotReserved($sessionId, $cart = NULL)
    {
        global $wpdb;
        $cart ? exit : NULL;
    }
    public static function deleteReserved($sessionID, $cart)
    {
        global $wpdb;
        $cart ? exit : NULL;
    }
}

?>