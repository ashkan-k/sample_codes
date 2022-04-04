<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Payment;
use App\Models\SoldCourse;
use Illuminate\Http\Request;
use SoapClient;

class PaymentController extends AdminController
{
    use PaymentTrait;

    protected $MerchantID = 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX'; //Required

    private static function DeleteCarts($user): void
    {
        Cart::query()
            ->where('user_id', '=', $user->id)
            ->delete();
    }

    public function check()
    {

        $user = auth()->user();

        $Authority = $_GET['Authority'];
        $MerchantID = $this->MerchantID;

        $payment = Payment::query()
            ->where('resNumber', '=', $Authority);

        $Amount = $payment->sum('price'); //Amount will be based on Toman


        if ($_GET['Status'] == 'OK') {

            $client = new SoapClient('https://sandbox.zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);

            $result = $client->PaymentVerification(
                [
                    'MerchantID' => $MerchantID,
                    'Authority' => $Authority,
                    'Amount' => $Amount,
                ]
            );

            if ($result->Status == 100) {
                if ($this->addCourseforUser($payment, $result, $user)) {
                    return 'Transaction success. RefID:' . $result->RefID;
                }
            }
            return 'Transaction failed. Status:' . $result->Status;

        } else {
            echo 'Transaction canceled by user';
        }
    }

    public function payment(Request $request)
    {

        if (!auth()->check()) {
            static::setAlert('برای دانلود یا خریداری اول وارد سایت شوید');
            return redirect(route('home'))->with("error", true);
        }

        $user = auth()->user();
        $carts = $this->selectCartUser($user);
        if (!$carts) {
            static::setAlert('شما محصولی در سبد خرید خود ندارید');
            return redirect(route('home'))->with("error", true);
        }

        $coupon = Coupon::where('name', $request->input('coupon'))->first() ?: '';

        $totalPrice = 0;
        $totalPrice = $this->actionDiscounts($carts, $totalPrice, $user);

        // Start Actions Coupon
        $coupon != '' ? $totalPrice = $this->actionCoupon($coupon, $totalPrice, $carts, $user) : null;
        // End Actions Coupon

        $Amount = $totalPrice; //Amount will be based on Toman - Required
        $Description = 'خرید از وب سایت آموزشی پلت لرن '; // Required
        $Email = $user->email; // Optional
        $CallbackURL = 'http://127.0.0.1:8000/course/payment/check'; // Required


        $client = new SoapClient('https://sandbox.zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);


        $result = $this->setPaymentRequest($client, $Amount, $Description, $Email, $CallbackURL);


        //Redirect to URL You can do it also by creating a form
        if ($result->Status == 100) {
            $this->updateAuthority($carts, $user, $result);
            return redirect('https://sandbox.zarinpal.com/pg/StartPay/' . $result->Authority);
        }
        echo 'ERR: ' . $result->Status;
    }

    private function addCourseforUser($payment, $result, $user)
    {
        $payment->update(['status' => 1, 'RefID' => $result->RefID]);

        foreach ($payment->get() as $pay) {
           $sold = SoldCourse::create([
                'user_id' => $user->id,
                'course_id' => $pay->course->id,
            ]);
        }
        self::DeleteCarts($user);
        return true;
    }

}
