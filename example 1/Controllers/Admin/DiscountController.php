<?php

namespace App\Http\Controllers\Admin;

use App\DB\DiscountRepo;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index()
    {
        return view('Admin.Discounts.index');
    }

    public function create()
    {
        $courses = DiscountRepo::selectCourses();
        return view('Admin.Discounts.create', compact('courses'));
    }

    public function store(Request $request)
    {
        try {
            $course_discount = DiscountRepo::selectDiscount($request['course_id']);
            foreach ($course_discount as $discount) {
                if (strtotime($discount->expiration) >= strtotime(now()->format('Y-m-d'))) {
                    $courseTitle = $discount->course->title;
                    return redirect(route('discounts.index'))->with("errorTime", "تخفیف فعالی برای دوره ( $courseTitle ) وجود دارد . ");
                }
            }

            $course_id = $request['course_id'];
            $expiration = $request['expiration'];
            $percent = $request['percent'];

            $discount = DiscountRepo::createDiscount($course_id, $expiration, $percent);
            $title = $discount->course->title;
            return redirect(route('discounts.index'))->with("status", "تخفیف ( $title ) با موفقیت ثبت شده . ");

        } catch (\Exception $exception) {

            return $this->ErrorRedirect();
        }
    }

    public function edit(Discount $discount)
    {
        $courses = DiscountRepo::selectCourses();
        return view('Admin.Discounts.edit', compact('discount', 'courses'));
    }

    public function update(Request $request, Discount $discount)
    {
        $course_discount = DiscountRepo::selectDiscount($request['course_id']);
        foreach ($course_discount as $discounts) {
            if ($discounts->id != $discount->id && strtotime($discounts->expiration) >= strtotime(now()->format('Y-m-d'))) {
                $title = $discounts->course->title;
                return redirect(route('discounts.index'))->with("errorTime", "تخفیف فعالی برای دوره ( $title ) وجود دارد . ");
            }
        }
        try {

            $course_id = $request['course_id'];
            $expiration = $request['expiration'];
            $percent = $request['percent'];

            DiscountRepo::editDiscount($discount, $course_id, $expiration, $percent);
            $title = $discount->course->title;
            return redirect(route('discounts.index'))->with("status", "تخفیف ( $title ) با موفقیت ثبت شده . ");

        } catch (\Exception $exception) {

            return $this->ErrorRedirect();
        }
    }

    private function ErrorRedirect()
    {
        alert()->error("در حین عملیات مشکلی رخ داده", 'خطا 😔')->persistent("باشه");

        return redirect(route('discounts.index'))->with("error", true);
    }


}
