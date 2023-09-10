<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Blog;
use App\Models\Menu;
use App\Models\Event;
use App\Models\Course;
use App\Models\Slider;
use App\Models\AboutUs;
use App\Models\Category;
use App\Models\NewsNotice;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Base\Traits\HeaderFooterData;

class NewsController extends Controller
{
    use HeaderFooterData;
    public function index()
    {
        $header_footer_data = $this->getCollegeDetailsData();
        $menus = Menu::where('type_id','main')->where('deleted_uq_code',1)->orderBy('display_order','asc')->get();
        $news = NewsNotice::where('deleted_uq_code',1)->orderBy('display_order','asc')->paginate(3);
        $this->data = [
            'menus' => $menus,
            'header_footer_data' => $header_footer_data,
            'news' => $news,
        ];
        return view('frontend.news_notices', $this->data);
    }

    public function events()
    {
        $events = Event::where('deleted_uq_code',1)->paginate(6);
        $header_footer_data = $this->getCollegeDetailsData();
        $menus = Menu::where('type_id','main')->where('deleted_uq_code',1)->orderBy('display_order','asc')->get();
        $this->data = [
            'menus' => $menus,
            'header_footer_data' => $header_footer_data,
            'events'=>$events
        ];
        return view('frontend.event', $this->data);
    }

    public function filteredEvents($category_id)
    {
        $events = Event::where('deleted_uq_code',1)->where('category_id',$category_id)->paginate(6);
        $header_footer_data = $this->getCollegeDetailsData();
        $menus = Menu::where('type_id','main')->where('deleted_uq_code',1)->orderBy('display_order','asc')->get();
        $this->data = [
            'menus' => $menus,
            'header_footer_data' => $header_footer_data,
            'events'=>$events
        ];
        return view('frontend.event', $this->data);
    }
    public function eventDetail($id)
    {
        $event = Event::find($id);
        $header_footer_data = $this->getCollegeDetailsData();
        $menus = Menu::where('type_id','main')->where('deleted_uq_code',1)->orderBy('display_order','asc')->get();
        $latest_events = Event::latest()->take(5)->where('deleted_uq_code',1)->where('id','<>',$id)->get();
        $categories = Category::where('menu_id',11)->where('deleted_uq_code',1)->paginate(8);
        $this->data = [
            'menus' => $menus,
            'header_footer_data' => $header_footer_data,
            'event'=>$event,
            'latest_events'=>$latest_events,
            'categories'=>$categories
        ];
        return view('frontend.event-details', $this->data);
    }

    public function blog()
    {
        $header_footer_data = $this->getCollegeDetailsData();
        $menus = Menu::where('type_id','main')->where('deleted_uq_code',1)->orderBy('display_order','asc')->get();
        $blogs = Blog::where('deleted_uq_code',1)->paginate(3);
        $this->data = [
            'menus' => $menus,
            'header_footer_data' => $header_footer_data,
            'blogs' => $blogs,
        ];
        return view('frontend.blog', $this->data);
    }
    public function filteredBlogs($category_id)
    {
        $header_footer_data = $this->getCollegeDetailsData();
        $menus = Menu::where('type_id','main')->where('deleted_uq_code',1)->orderBy('display_order','asc')->get();
        $blogs = Blog::where('deleted_uq_code',1)->where('category_id',$category_id)->paginate(6);
        $this->data = [
            'menus' => $menus,
            'header_footer_data' => $header_footer_data,
            'blogs' => $blogs,
        ];
        return view('frontend.blog', $this->data);
    }
    public function blogDetail($id)
    {
        $blog = Blog::find($id);
        $blog->visit_counts++;
        $blog->save();
        $header_footer_data = $this->getCollegeDetailsData();
        $menus = Menu::where('type_id','main')->where('deleted_uq_code',1)->orderBy('display_order','asc')->get();
        $latest_blogs = Blog::latest()->take(5)->where('id','<>',$id)->get();
        $categories = Category::where('menu_id',12)->where('deleted_uq_code',1)->paginate(8);
        $this->data = [
            'menus' => $menus,
            'header_footer_data' => $header_footer_data,
            'blog'=>$blog,
            'latest_blogs'=>$latest_blogs,
            'categories'=>$categories
        ];
        return view('frontend.blog-details', $this->data);
    }
    public function filteredNews($category_id)
    {
        $news = NewsNotice::where('deleted_uq_code',1)->where('category_id',$category_id)->paginate(6);
        $header_footer_data = $this->getCollegeDetailsData();
        $menus = Menu::where('type_id','main')->where('deleted_uq_code',1)->orderBy('display_order','asc')->get();
        $this->data = [
            'menus' => $menus,
            'header_footer_data' => $header_footer_data,
            'news'=>$news
        ];
        return view('frontend.news_notices', $this->data);
    }
    public function newsDetail($id)
    {
        $news = NewsNotice::find($id);
        $news->visit_counts++;
        $news->save();
        $header_footer_data = $this->getCollegeDetailsData();
        $menus = Menu::where('type_id','main')->where('deleted_uq_code',1)->orderBy('display_order','asc')->get();
        $latest_news = NewsNotice::latest()->take(5)->where('deleted_uq_code',1)->where('id','<>',$id)->get();
        $categories = Category::where('menu_id',4)->where('deleted_uq_code',1)->paginate(8);
        $this->data = [
            'menus' => $menus,
            'header_footer_data' => $header_footer_data,
            'news'=>$news,
            'latest_news'=>$latest_news,
            'categories'=>$categories
        ];
        return view('frontend.news-details', $this->data);
    }
}
