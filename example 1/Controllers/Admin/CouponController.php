<?php

namespace App\Http\Controllers\Admin;

use App\DB\CouponRepo;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends AdminController
{

    public function index()
    {
        return view('Admin.Coupons.index');
    }


    public function create()
    {
        $courses = CouponRepo::selectCourses();
        return view('Admin.Coupons.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'course_id' => 'required',
            'percent' => 'required',
            'expiration' => 'required',
        ]);


        try {
            $name = $request->input('name');
            $expiration = $request->input('expiration');
            $percent = $request->input('percent');
            $numberUse = $request->input('numberUse');
            $course_id = $request->input('course_id');

            $coupon = CouponRepo::createCoupon($name, $expiration, $percent, $numberUse, $course_id);
            return redirect(route('coupons.index'))->with("status", "کوپن ( $coupon->name ) با موفقیت ثبت شده . ");

        } catch (\Exception $exception) {

            alert()->error("در حین عملیات مشکلی رخ داده", 'خطا 😔')->persistent("باشه");

            return redirect(route('coupons.index'))->with("error", true);
        }
    }


    public function edit(Coupon $coupon)
    {
        $courses = CouponRepo::selectCourses();
        return view('Admin.Coupons.edit', compact('coupon', 'courses'));
    }


    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'name' => 'required',
            'course_id' => 'required',
            'percent' => 'required',
            'expiration' => 'required',
        ]);

        try {

            $name = $request->input('name');
            $expiration = $request->input('expiration');
            $percent = $request->input('percent');
            $numberUse = $request->input('numberUse');
            $course_id = $request->input('course_id');

            CouponRepo::editCoupon($coupon, $name, $expiration, $percent, $numberUse, $course_id);

            return redirect(route('coupons.index'))->with("edit", "کوپن ( $coupon->name ) با موفقیت ویرایش شد .");

        } catch (\Exception $exception) {

            alert()->error("در حین عملیات مشکلی رخ داده", 'خطا 😔')->persistent("باشه");

            return redirect(route('coupons.index'))->with("error", true);
        }
    }

}
