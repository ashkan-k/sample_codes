<?php


namespace App\Http\Controllers;


use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Payment;

trait PaymentTrait
{

//   Start Action Offer (Discounts And Coupon)

    private function actionDiscounts($carts, $totalPrice, $user)
    {
        foreach ($carts as $cart) {
            if (count($cart->course->discounts) > 0) {
                $discount = $cart->course->discounts()->latest()->first();

                if ($this->checkExpiration($discount->expiration)) {
//                  action off Discount
                    $price = $this->setOffer($cart->course, $discount->percent, $cart->course->price);
//                  create Payment DateBase
                    $this->insertPayment($user, $cart->course->id, $price, $discount->id);
                    $totalPrice += $price;
                    continue;
                }
            }
            $this->insertPayment($user, $cart->course->id, $cart->course->price);
            $totalPrice += $cart->course->price;
        }

        return $totalPrice;
    }

    private function actionCoupon(Coupon $coupon, $totalPrice, $carts, $user)
    {

        $priceCoupon = 0;
        foreach ($carts as $cart) {
            $checkNumberUse = $coupon->numberUse == null || $coupon->numberUse > 0;
            if (!$this->checkExpiration($coupon->expiration) || !$checkNumberUse) {
                continue;
            } elseif (!$this->existsCoupun($cart, $coupon->courses)) {
                continue;
            }
//          action off Discount
            $priceCoupon += $this->setOffer($cart->course, $coupon->percent);
            $payment = $this->selectPayment($cart, $user);
//          Update Coupon_id Payment DateBase
            $payment->update([
                'coupon_id' => $coupon->id,
                'price' => $payment->price - $priceCoupon,
            ]);
        }

        $totalPrice -= $priceCoupon;
        return $totalPrice;
    }

    /**
     * @param $course
     * @param $percent
     * @param $price null
     * @return float|int
     */
    private function setOffer($course, $percent, $price = null)
    {
        if ($price != null) return $price - ($course->price * $percent) / 100;

        return ($course->price * $percent) / 100;
    }

//   End Action Offer (Discounts And Coupon)


//  Start Create Payment

    /**
     * @param $user
     * @param $course_id
     * @param $price
     * @param null $discount_id
     * @param null $coupon_id
     */
    private function insertPayment($user, $course_id, $price, $discount_id = null, $coupon_id = null): void
    {
        $check = $this->selectPaymentWithStatus($course_id, $user,1);
        if ($check != 'ok') {
            abort(403);
        }
        Payment::query()->create([
            'user_id' => $user->id,
            'course_id' => $course_id,
            'price' => $price,
            'discount_id' => $discount_id,
            'coupon_id' => $coupon_id,
        ]);
    }

//  End Create Payment


//  Start Select Functions

    private function selectPayment($cart, $user)
    {
        $payment = Payment::query()
            ->where('course_id', '=', $cart->course_id)
            ->where('user_id', '=', $user->id)
            ->latest()
            ->first();
        return $payment;
    }

    /**
     * @param $user
     * @return mixed
     */
    private function selectCartUser($user)
    {
        return Cart::query()
            ->where('user_id', '=', $user->id)
            ->get();
    }

    private function selectPaymentWithStatus($course_id, $user,$status)
    {
        $check = Payment::query()
            ->where(function ($query) use ($course_id, $user,$status) {
                $query->where('course_id', '=', $course_id)
                    ->where('user_id', '=', $user->id)
                    ->where('status', '=', $status);
            })->latest()->first();
        return $check ?: 'ok';
    }

//  End Select Functions


//  Start Checker Functions

    public function checkExpiration($expiration)
    {
        if (strtotime($expiration) >= strtotime(now()->format('Y-m-d'))) {
            return true;
        }
        return false;
    }

    private function existsCoupun($cart, $courses)
    {
        foreach ($courses as $course) {
            if ($course->id == $cart->course->id) {
                return true;
            }
            return false;
        }
    }


//  End Checker Functions


//  Start Seter Functions

    private static function setAlert($text): void
    {
        alert()->error($text, 'دقت کنید')->persistent("باشه");
    }

    /**
     * @param $client
     * @param $Amount
     * @param $Description
     * @param $Email
     * @param $CallbackURL
     * @return mixed
     */
    private function setPaymentRequest($client, $Amount, $Description, $Email, $CallbackURL)
    {
        $result = $client->PaymentRequest([
                'MerchantID' => $this->MerchantID,
                'Amount' => $Amount,
                'Description' => $Description,
                'Email' => $Email,
                'CallbackURL' => $CallbackURL,
            ]
        );
        return $result;
    }

//  End Checker Functions


    /**
     * @param $carts
     * @param $user
     * @param $result
     */
    private function updateAuthority($carts, $user, $result): void
    {
        foreach ($carts as $cart) {
            $course_id = $cart->course->id;
            $payment = $this->selectPaymentWithStatus($course_id, $user, 0);
            $payment->update([
                'resNumber' => $result->Authority
            ]);
        }
    }

}
