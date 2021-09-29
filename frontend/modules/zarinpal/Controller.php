<?php
namespace BooklyPro\Frontend\Modules\Zarinpal;

use Bookly\Lib as BooklyLib;
use Bookly\Lib\Entities\Payment;
use Bookly\Lib\Notifications\Cart\Sender;
use Bookly\Lib\UserBookingData;
use Bookly\Lib\Utils\Common;
use BooklyPro\Lib\Payment\ZarinPal;

/**
 * Class Controller
 * @package BooklyPro\Frontend\Modules\Zarinpal
 */
class Controller extends BooklyLib\Base\Component
{
    /**
     * Init Express Checkout transaction.
     */
    public static function ecInit()
    {
        $form_id = self::parameter( 'bookly_fid' );
        if ($form_id) {

            $zarinpal = new ZarinPal();
            $userData = new UserBookingData( $form_id );

            if ( $userData->load() ) {
                $cart_info = $userData->cart->getInfo( Payment::TYPE_ZARIN );
                $cart_info->setGatewayTaxCalculationRule( 'tax_increases_the_cost' );

                $product = new \stdClass();
                $product->name  = $userData->cart->getItemsTitle( 126 );
                $product->price = $cart_info->getGatewayAmount();
                $product->qty   = 1;
                $zarinpal->setProduct( $product );
                $zarinpal->setTotalTax( $cart_info->getGatewayTax() );


                $zarinpal->sendECRequest( $form_id );
            }
        }
    }


    public static function ecReturn()
    {
        $form_id = self::parameter( 'bookly_fid' );
        $ZarinPal  = new ZarinPal();
        $error_message = '';

        $response = $ZarinPal->sendNvpRequest($form_id, array());


        if (strtoupper($response['code']) == 100) {
            $payment = new Payment();
            $payment
                ->setType( Payment::TYPE_ZARIN )
                ->setStatus( Payment::STATUS_COMPLETED );
            $userData = new UserBookingData( $form_id );
            if ( $userData->load() ) {
                $cart_info = $userData->cart->getInfo( Payment::TYPE_ZARIN );

                $coupon = $userData->getCoupon();
                if ( $coupon ) {
                    $coupon->claim();
                    $coupon->save();
                }
                $paid     = (float) $cart_info->getPayNow();
                $expected = (float) $cart_info->getPayNow();
                if ($expected == $paid) {
                    $payment
                        ->setCartInfo($cart_info)
                        ->save();
                    $order = $userData->save($payment);
                    $payment->setDetailsFromOrder($order, $cart_info)->save();
                    Sender::send($order);
                }
            } else {

                $payment
                    ->setTotal($paid)
                    ->setPaid($paid)
                    ->setTax($response['TAXAMT'])
                    ->save();
            }
            $userData->setPaymentStatus( Payment::TYPE_ZARIN, 'success' );
            $userData->sessionSave();
            @wp_redirect( remove_query_arg( Zarinpal::$remove_parameters, Common::getCurrentPageURL() ));
            exit;
        } else {
            $error_message = $response["msg"];
        }

        if (! empty( $error_message )) {
            header( 'Location: ' . wp_sanitize_redirect(add_query_arg(array(
                'bookly_action'    => 'zarin-ec-error',
                'bookly_fid'    => $form_id,
                'error_msg' => urlencode($error_message),
            ), Common::getCurrentPageURL()) ));
            exit;
        }
    }


    public static function ecCancel()
    {
        $userData = new UserBookingData(self::Parameter( 'bookly_fid' ));
        $userData->load();
        $userData->setPaymentStatus(Payment::TYPE_ZARIN, 'cancelled' );
        @wp_redirect(remove_query_arg(Zarinpal::$remove_parameters, Common::getCurrentPageURL()));
        exit;
    }


    public static function ecError()
    {
        $userData = new UserBookingData(self::Parameter( 'bookly_fid' ));
        $userData->load();
        $userData->setPaymentStatus(Payment::TYPE_ZARIN, 'error', self::Parameter( 'error_msg' ));
        @wp_redirect(remove_query_arg(Zarinpal::$remove_parameters, Common::getCurrentPageURL()));
        exit;
    }
}
