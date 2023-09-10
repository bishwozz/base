<?php

namespace App\Models;

use App\Models\BaseModel;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Category extends BaseModel
{
    use CrudTrait;
    public static $menus = [4 => "News Notices", 5 => "Gallery", 11 => "Events", 12 => "Blogs"];
    protected $table = 'categories';
    protected $guarded = ['id'];

    public function menu(){
        return $this->belongsTo('App\Models\Menu', 'menu_id', 'id');
    }
    public function galleries(){
        return $this->hasMany('App\Models\Gallery', 'category_id', 'id');
    }
    public function events(){
        return $this->hasMany('App\Models\Event', 'category_id', 'id');
    }
    public function news_notices(){
        return $this->hasMany('App\Models\NewsNotice', 'category_id', 'id');
    }
    public function blogs(){
        return $this->hasMany('App\Models\Blog', 'category_id', 'id');
    }
}
