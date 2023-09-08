<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ui;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class UiController extends Controller
{
    public function changeLocale(Request $request){
      $ui = Ui::updateOrCreate(['user_id' => backpack_user()->id],
      [ 
        'user_id' => backpack_user()->id,
        'lang' => $request->value
      ]);
      return true;
    }
    public function changeTheme(Request $request){
      $ui = Ui::updateOrCreate(['user_id' => backpack_user()->id],
      [ 
        'user_id' => backpack_user()->id,
        'background' => $request->colorCode
      ]);
      return true;

    }
}
