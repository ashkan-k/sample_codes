<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;

class AdminController extends Controller
{
    private function setTimeFolder(): array
    {
        $year = Carbon::now()->year;
        $mouth = Carbon::now()->month;
        $day = Carbon::now()->day;
        return array($year, $mouth, $day);
    }

    protected function uploadImages($file, $username , $resize = false)
    {
        list($year, $mouth, $day) = $this->setTimeFolder();

        $imagePath = "/profile-photos/{$year}-{$mouth}-{$day}/{$username}/";
        $filename = time() . '-' . $file->getClientOriginalName();

        $file = $file->move(storage_path('app/public' . $imagePath), $filename);

        if ($resize == true) {
            $sizes = ["300", "600", "900"];
            $url['images'] = $this->resize($file->getRealPath(), $sizes, $imagePath, $filename);
            $url['thumb'] = $url['images'][$sizes[0]];
            return $url;
        }
        return $imagePath . $filename;

    }

    private function resize($path, $sizes, $imagePath, $filename)
    {
        $images['original'] = $imagePath . $filename;
        foreach ($sizes as $size) {
            $images[$size] = $imagePath . "{$size}_" . $filename;
            Image::make($path)->resize($size, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path($images[$size]));
        }
        return $images;
    }


    protected function getCourseTime($times)
    {
        $timestamp = Carbon::parse('00:00:00');
        foreach ($times as $t) {
            $time = strlen($t) == 5 ? strtotime('00:' . $t) : strtotime($t);
            $timestamp->addSecond($time);
        }
        return $timestamp->format('H:i:s');
    }

    protected function setCourseTime($episode)
    {
        $course = $episode->course;
        $course->time = $this->getCourseTime($course->episodes->pluck('time'));
        $course->save();
    }

    public function uploadImageSubject()
    {
        $this->validate(request(), [
            'upload' => 'required|mimes:jpeg,png,bmp',
        ]);

        list($year, $mouth, $day) = $this->setTimeFolder();


        $imagePath = "/upload/imagesCk/{$year}-{$mouth}-{$day}/";

        $file = request()->file('upload');
        $filename = $file->getClientOriginalName();

        if (file_exists(public_path($imagePath) . $filename)) {
            $filename = Carbon::now()->timestamp . $filename;
        }

        $file->move(public_path($imagePath), $filename);
        $url = $imagePath . $filename;

        return "<script>window.parent.CKEDITOR.tools.callFunction(1 , '{$url}' , '')</script>";
    }


}
