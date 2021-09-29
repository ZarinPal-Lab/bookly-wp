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


        $phone=$formData["phone"];
        if($phone == ""){

            $data = array("merchant_id" =>  get_option("bookly_pmt_zarin_merchantid"),
                "amount" => $total,
                "callback_url" => add_query_arg(["bookly_action" => "zarin-ec-return", "bookly_fid" => $form_id],$current_url),
                "description" => $description,

            );
        }else {

            $data = array("merchant_id" => get_option("bookly_pmt_zarin_merchantid"),
                "amount" => $total,
                "callback_url" => add_query_arg(["bookly_action" => "zarin-ec-return", "bookly_fid" => $form_id],$current_url),
                "description" => $description,
                "metadata" => [ "email" => "0","mobile"=>$phone],
            );
        }



        $slotReserved = \BooklyPro\Lib\CheckAppointment::SlotIsReserved($cart);
        if ($slotReserved !== false && is_string($slotReserved)) {
            header("Location: " . wp_sanitize_redirect(add_query_arg(["bookly_action" => "zarin-ec-error", "bookly_fid" => $form_id, "error_msg" => urlencode($slotReserved)], $current_url)));
            exit;
        }
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
        if(empty($result['errors'])){
            if ($result['data']['code'] == 100) {
                header("Location: https://www.zarinpal.com/pg/StartPay/" . $result['data']["authority"]);
                exit;
            }
            echo "ERR: " . $result['errors']['code'].$result['errors']['message'];
            header("Location: " . wp_sanitize_redirect(add_query_arg(["bookly_action" => "zarin-ec-error", "bookly_fid" => $form_id, "error_msg" => urlencode($this->description_Verification($result["Status"]))], $current_url)));
            exit;
        }

    }
    public function sendNvpRequest($method, $data)
    {

        $ZarinpalResponse = ["code" => -1, "msg" => $this->description_Verification(0), "refid" => ""];

        if (isset($_GET["Authority"]) && isset($_GET["Status"]) && $_GET["Status"] == "OK") {

          $refID = $_GET["Authority"];

            $amountTotal = \Bookly\Lib\Session::getFormVar($method, "zarin_amount_total");



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
                $ZarinpalResponse = ["code" => 500, "msg" => $this->description_Verification(500), "refid" => $refID];
            } else if ($result['data']['code'] == 100){
                $ZarinpalResponse = ["code" => $result['data']['ref_id'], "msg" => $this->description_Verification($result['data']['ref_id']), "refid" => $result['data']['ref_id']];
            }else {
                $ZarinpalResponse = ["code" => $result['errors']['code'], "msg" => $this->description_Verification($result['errors']['code']), "refid" => $result['errors']['code']];

            }
        }
        $cartData = \Bookly\Lib\Session::getFormVar($method, "cart");
        \BooklyPro\Lib\CheckAppointment::deleteReserved($method, $cartData);
        return $ZarinpalResponse;
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
            case -9:
                return "اطلاعات ارسال شده خالی یا ناقص است";
                break;
            case -10:
                return "محدودیت آیپی وجود دارد";
                break;
            case -11:
                return "مرچنت کد فعال نیست";
                break;
            case -12:
                return "تعداد درخواست در بازه زمانی بیش از حد مجاز است";
                break;
            default:
                return "در حال انتقال";
        }
    }
    public function description_Verification($code)
    {
        intval($code);
        switch (intval($code)) {
            case 101:
                return "تراکنش موفق است ";
                break;
            case 100:
                return "تراکنش موفق است ";
                break;
            case -9:
                return "اطلاعات ارسال شده خالی یا ناقص است";
                break;
            case -10:
                return "محدودیت آیپی وجود دارد";
                break;
            case -11:
                return "مرچنت کد فعال نیست";
                break;
            case -12:
                return "تعداد درخواست در بازه زمانی بیش از حد مجاز است";
                break;
            case -50:
                return "مبلغ ارسال شده با مبلغ وریفای متفاوت است ";
                break;
            case -51:
                return "تراکنش ناموفق است";
                break;

            case 500:
                return "مشکلی در اتصال  با وب سرویس زرین پال وجود دارد";
                break;
            default:
                return "پرداختی انجام نشده است";
        }
    }
}

?>