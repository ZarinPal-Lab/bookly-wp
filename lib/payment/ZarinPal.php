<?php


namespace BooklyPro\Lib\Payment;

class ZarinPal
{
    protected $product = NULL;
    protected $tax = 0;
    public static $remove_parameters = ["bookly_action", "bookly_fid", "error_msg", "token", "PayerID", "type"];
    const TYPE_EXPRESS_CHECKOUT = "ec";
    const TYPE_PAYMENTS_STANDARD = "ps";
    public function sendECRequest($form_id)
    {
        $current_url = \Bookly\Lib\Utils\Common::getCurrentPageURL();
       // $sandbox = get_option("bookly_pmt_zarin_sandbox") == 1;
       // $curlUrl = "https://www.zarinpal.com/pg/rest/WebGate/PaymentRequest.json";
        $currency = get_option("bookly_pmt_currency");
        $total = 0;
        if ($currency == \Bookly\Lib\Utils\Price::CURRENCY_IR_RIAL) {
            $amuntWithCurrency = $this->product->qty * $this->product->price / 10;
        } else {
            $amuntWithCurrency = $this->product->qty * $this->product->price;
        }
        $total += $amuntWithCurrency + $this->tax;
        \Bookly\Lib\Session::setFormVar($form_id, "zarin_amount_total", $total);
        $cart = \Bookly\Lib\Session::getFormVar($form_id, "cart");
        $formData = \Bookly\Lib\Session::getFormVar($form_id, "data");
        $date = date_i18n("Y-m-d", strtotime($formData["date_from"]));
        $countOfServices = count($formData["slots"]);
        if (strlen($formData["first_name"])) {
            $fullName = $formData["first_name"] . " " . $formData["last_name"];
        } else {
            $fullName = $formData["full_name"];
        }
        $fullName = strlen($fullName) != 0 ? $fullName : "بدون نام";

        $description = "رزرو وقت ملاقات برای '" . $fullName . "' به شماره تلفن '" . $formData["phone"] . "' و تعداد " . $countOfServices . " سرویس" . " در تاریخ " . $date;

        // $data = ["MerchantID" => get_option("bookly_pmt_zarin_merchantid"), "Amount" => $total, "Description" => $description, "CallbackURL" => add_query_arg(["bookly_action" => "zarin-ec-return", "bookly_fid" => $form_id], $current_url)];

        if($currency == \Bookly\Lib\Utils\Price::CURRENCY_IR_RIAL){
            $data = array("merchant_id" =>  get_option("bookly_pmt_zarin_merchantid"),
                "amount" => $total,
                "callback_url" => add_query_arg(["bookly_action" => "zarin-ec-return", "bookly_fid" => $form_id],$current_url),
                "description" => $description,
                "currency"=> "IRR",

            );
        }else {

            $data = array("merchant_id" =>  get_option("bookly_pmt_zarin_merchantid"),
                "amount" => $total,
                "callback_url" => add_query_arg(["bookly_action" => "zarin-ec-return", "bookly_fid" => $form_id],$current_url),
                "description" => $description,
                "currency"=> "IRT",

            );
        }



       // $slotReserved = \BooklyPro\Lib\CheckAppointment::SlotIsReserved($cart);
        $slotReserved=$this->SlotIsReserved($cart);
        /*if(isset($cart)==true){
            $cart ? exit : NULL;
            $slotReserved = $cart;
        }*/
        if ($slotReserved !== false && is_string($slotReserved)) {
            header("Location: " . wp_sanitize_redirect(add_query_arg(["bookly_action" => "payir-ec-error", "bookly_fid" => $form_id, "error_msg" => urlencode($slotReserved)], $current_url)));
            exit;
        }
        //    $jsonData = json_encode($data);
        // $ch = curl_init($curlUrl);
        //  curl_setopt($ch, CURLOPT_USERAGENT, "ZarinPal Rest Api v1");
        // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json", "Content-Length: " . strlen($jsonData)]);
        //  $result = curl_exec($ch);
        //$err = curl_error($ch);
        // $result = json_decode($result, true);
        // curl_close($ch);

        $jsonData = json_encode($data);
        $ch = curl_init('https://api.zarinpal.com/pg/v4/payment/request.json');
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ));

        $result = curl_exec($ch);
        $err = curl_error($ch);
        $result = json_decode($result, true, JSON_PRETTY_PRINT);
        curl_close($ch);


        if ($err) {
            header("Location: " . wp_sanitize_redirect(add_query_arg(["bookly_action" => "zarin-ec-error", "bookly_fid" => $form_id, "error_msg" => urlencode($this->description_Verification(210))], $current_url)));
            exit;
        }

        //  if ($result["Status"] == 100) {
        //  header("Location: https://www.zarinpal.com/pg/StartPay/" . $result["Authority"]);
        // exit;
        //}

        if ($result['data']['code'] == 100) {
            header("Location: https://www.zarinpal.com/pg/StartPay/" . $result['data']["authority"]);
            exit;
        }

        echo "ERR: " . $result['errors']['code'];
        header("Location: " . wp_sanitize_redirect(add_query_arg(["bookly_action" => "zarin-ec-error", "bookly_fid" => $form_id, "error_msg" => urlencode($this->description_Verification($result['errors']['code']))], $current_url)));
        exit;
    }
    public static function SlotIsReserved($cart)
    {
        global $wpdb;
        $cart ? exit : NULL;
        return $cart;
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
        return $cart;
    }

    public function sendNvpRequest($method, $data)
    {
        //$sandbox = get_option("bookly_pmt_zarin_sandbox") == 1;
        //$curlUrl = "https://www.zarinpal.com/pg/rest/WebGate/PaymentVerification.json";

        $PayPalResponse = ["code" => -1, "msg" => $this->description_Verification(0), "refid" => ""];
        if (isset($_GET["Authority"]) && isset($_GET["Status"]) && $_GET["Status"] == "OK") {
            $orderid = $refID = $_GET["Authority"];

            $amountTotal = \Bookly\Lib\Session::getFormVar($method, "zarin_amount_total");

            //   $data = ["MerchantID" => get_option("bookly_pmt_zarin_merchantid"), "Authority" => $refID, "Amount" => $amountTotal];


            //     $jsonData = json_encode($data);

            //  $ch = curl_init("https://www.zarinpal.com/pg/rest/WebGate/PaymentVerification.json");
            //  curl_setopt($ch, CURLOPT_USERAGENT, "ZarinPal Rest Api v1");
            // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            // curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            //  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //  curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json", "Content-Length: " . strlen($jsonData)]);
            // $result = curl_exec($ch);
            //  $err = curl_error($ch);
            //  curl_close($ch);

            $data = array("merchant_id" => get_option("bookly_pmt_zarin_merchantid"), "authority" => $refID, "amount" => $amountTotal);


            $jsonData = json_encode($data);
            $ch = curl_init('https://api.zarinpal.com/pg/v4/payment/verify.json');
            curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v4');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($jsonData)
            ));

            $result = curl_exec($ch);
            curl_close($ch);
            $result = json_decode($result, true);
            $err = curl_error($ch);

            if ($err) {
                $PayPalResponse = ["code" => 210, "msg" => $this->description_Verification(210), "refid" => $refID];
            } else if($result['data']['code'] == 100) {
                $PayPalResponse = ["code" => $result['data']['code'], "msg" => $this->description_Verification($result['data']['code']), "refid" => $result['data']['ref_id']];
            }
        }
        $cartData = \Bookly\Lib\Session::getFormVar($method, "cart");
        \BooklyPro\Lib\CheckAppointment::deleteReserved($method, $cartData);
        return $PayPalResponse;
    }
    public static function renderECForm($form_id)
    {
        $replacement = ["%form_id%" => $form_id, "%gateway%" => \Bookly\Lib\Entities\Payment::TYPE_ZARIN, "%back%" => \Bookly\Lib\Utils\Common::getTranslatedOption("bookly_l10n_button_back"), "%next%" => \Bookly\Lib\Utils\Common::getTranslatedOption("bookly_l10n_step_payment_button_next"), "%align_class%" => get_option("bookly_app_align_buttons_left") ? "bookly-left" : "bookly-right"];
        $form = "<form method=\"post\" class=\"bookly-%gateway%-form\">\r\n        <input type=\"hidden\" name=\"bookly_fid\" value=\"%form_id%\"/>\r\n        <input type=\"hidden\" name=\"bookly_action\" value=\"zarin-ec-init\"/>\r\n        <button class=\"bookly-back-step bookly-js-back-step bookly-btn ladda-button\" data-style=\"zoom-in\" style=\"margin-right: 10px;\" data-spinner-size=\"40\"><span class=\"ladda-label\">%back%</span></button>\r\n        <div class=\"%align_class%\">\r\n            <button class=\"bookly-next-step bookly-js-next-step bookly-btn ladda-button\" data-style=\"zoom-in\" data-spinner-size=\"40\"><span class=\"ladda-label\">%next%</span></button>\r\n        </div>\r\n        </form>";
        echo strtr($form, $replacement);
    }
    public function setProduct(\stdClass $product)
    {
        $this->product = $product;
    }
    public function setTotalTax($tax)
    {
        $this->tax = $tax;
    }
    public function description_Request($code)
    {
        intval($code);
        switch (intval($code)) {
            case -1:
                return "اطلاعات ارسالی ناقص می باشد (کد -1)";
                break;
            case -2:
                return "وب سرویس مورد نظر معتبر نمی باشد (کد -2)";
                break;
            case -3:
                return "حداقل مبلغ پرداختی درگاه پرداخت 100 تومان می باشد (کد -3)";
                break;
            case -4:
                return "فروشنده متقاضی پرداخت معتبر نمی باشد (کد -4)";
                break;
            default:
                return "سیستم در حال انتقال به سیستم مورد نظر می باشد، لطفا صبر کنید (کد 1)";
        }
    }
    public function description_Verification($code)
    {
        intval($code);
        switch (intval($code)) {
            case 101:
            case 100:
                return "پرداخت با موفقیت انجام پذیرفت (کد 1)";
                break;
            case -1:
                return "اطلاعات ارسالی ناقص می باشد (کد -1)";
                break;
            case -2:
                return "و يا مرچنت كد پذيرنده صحيح نيست. IP";
                break;
            case -3:
                return "با توجه به محدوديت هاي شاپرك امكان پرداخت با رقم درخواست شده ميسر نمي باشد.";
                break;
            case -4:
                return "سطح تاييد پذيرنده پايين تر از سطح نقره اي است.";
                break;
            case -11:
                return "درخواست مورد نظر يافت نشد.";
                break;
            case -12:
                return "امكان ويرايش درخواست ميسر نمي باشد.";
                break;
            case -21:
                return "هيچ نوع عمليات مالي براي اين تراكنش يافت نشد.";
                break;
            case -22:
                return "تراكنش نا موفق ميباشد.";
                break;
            case -33:
                return "رقم تراكنش با رقم پرداخت شده مطابقت ندارد.";
                break;
            case -34:
                return "سقف تقسيم تراكنش از لحاظ تعداد يا رقم عبور نموده است";
                break;
            case -40:
                return "اجازه دسترسي به متد مربوطه وجود ندارد.";
                break;
            case -42:
                return "مدت زمان معتبر طول عمر شناسه پرداخت بايد بين 30 دقيه تا 45 روز مي باشد.";
                break;
            case -54:
                return "درخواست مورد نظر آرشيو شده است.";
                break;
            case 210:
                return "مشکلی در ارتباط با زرین پال به وجود آمده است";
                break;
            default:
                return "عملیات پرداخت بطورکامل انجام نشد. (کد 0)";
        }
    }
}

?>