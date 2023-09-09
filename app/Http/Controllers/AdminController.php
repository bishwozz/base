<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Models\CoreMaster\AppSetting;
use App\Models\CoreMaster\MstFiscalYear;
use App\Models\CoreMaster\MstMinistry;
use App\Models\CoreMaster\MstNepaliMonth;

class AdminController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(backpack_middleware());
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {

        $this->data= [
            'title' => trans('backpack::base.dashboard'),
            trans('backpack::crud.admin')     => backpack_url('dashboard'),
            trans('backpack::base.dashboard') => true,

        ];

        return view(backpack_view('dashboard'), $this->data);
    }

    /**
     * Redirect to the dashboard.
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function redirect()
    {
        // The '/admin' route is not to be used as a page, because it breaks the menu's active state.
        return redirect(backpack_url('dashboard'));
    }


    public function userManual(){

        $location = public_path()."/UserManual/user_manual.pdf";
        $filename = 'User-Manual.pdf';

        // Optional: serve the file under a different filename:
        // optional headers
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"'
        ];
        return response()->file($location,$headers);
    }
}
