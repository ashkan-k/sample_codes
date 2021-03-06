<?php

namespace App\Http\Livewire\Front;

use App\Models\Category;
use App\Models\Center;
use Livewire\Component;
use Livewire\WithPagination;

class Mizbans extends Component
{
    use WithPagination;

    protected $centers;
    public $slug;

    public $pagination = 9;
    protected $paginationTheme = 'bootstrap';


    private function getCenterBySlug($slug)
    {
        return Center::query()->where('slug', $slug)->first();
    }

    public function AddToWishList($slug)
    {
        $center = $this->getCenterBySlug($slug);
        if ($center)
        {
            auth()->user()->wish_lists()->create([
                'wish_listable_id' => $center->id,
                'wish_listable_type' => get_class($center),
            ]);
        }
    }

    public function DeleteFromWishList($slug)
    {
        $center = $this->getCenterBySlug($slug);
        if ($center)
        {
            auth()->user()->wish_lists()
                ->where('wish_listable_id', $center->id)->where('wish_listable_type', get_class($center))->delete();
        }
    }

    private function getData()
    {
        $this->centers = Category::query()->where('slug', $this->slug)
            ->first()->centers()->where('is_remove' , 0)->whereHas('work_time' , function ($query){
                return $query->where('center_id' , '!=' , null);
            })->latest()->paginate($this->pagination);
    }

    public function render()
    {
        $this->getData();
        return view('livewire.front.mizbans', ['centers' => $this->centers]);
    }
}
