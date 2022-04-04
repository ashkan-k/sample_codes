<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Payment;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use PaymentTrait;

    public function index()
    {
        //
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $user = auth()->user();
        $course_id = 1;
        $status = 1 ;
        $isExist = Cart::isExistCart($course_id,$user);
        $payment = $user->soldCourses()->where('course_id',$course_id)->first() ?: false;
        if ($isExist || $payment) {
            return redirect(route('home'));
        }
        Cart::create([
            'user_id' => auth()->user()->id,
            'course_id' => 1,
        ]);
    }


    public function show(Cart $cart)
    {
        //
    }


    public function edit(Cart $cart)
    {
        //
    }


    public function update(Request $request, Cart $cart)
    {
        //
    }


    public function destroy(Cart $cart)
    {
        //
    }
}
