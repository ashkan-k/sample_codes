<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    public function index()
    {
        return view('Admin.Payments.index');
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function edit(Payment $payment)
    {
        return view('Admin.Payments.edit',compact('payment'));
    }


    public function update(Request $request, Payment $payment)
    {
        //
    }


    public function destroy(Payment $payment)
    {
        //
    }
}
