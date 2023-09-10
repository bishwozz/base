<?php

namespace App\Http\Controllers;

use App\Models\OfficeDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CoreMaster\MstMinistry;


class HomeController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        return view('frontend.index', $data);
    }
}
