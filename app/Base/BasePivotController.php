<?php

namespace App\Base;


use Illuminate\Http\Request;
use App\Models\MinistryActLaw;
use Illuminate\Support\Facades\DB;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class BasePivotController extends CrudController
{
    public function setUp()
    {
        $this->setStyles();
        $this->setScripts();
    }

    public function fromView($view){
        return view($view, $this->data);
    }

    private function setScripts()
    {
        $load_js = array();
        $load_js[] = asset('pivot/cdnjs/jquery-ui.min.js');
        $load_js[] = asset('pivot/cdnjs/d3.min.js');
        $load_js[] = asset('pivot/cdnjs/jquery.ui.touch-punch.min.js');
        $load_js[] = asset('pivot/cdnjs/papaparse.min.js');
        $load_js[] = asset('pivot/cdnjs/c3.min.js');
        $load_js[] = asset('pivot/pivot.js');
        $load_js[] = asset('pivot/d3_renderers.js');
        $load_js[] = asset('pivot/c3_renderers.js');
        $load_js[] = asset('pivot/export_renderers.js');
        $load_js[] = asset('pivot/pivottable_base.js');
        $load_js[] = asset('pivot/custom.js');
        $load_js[] = asset('pivot/jquery.print.js');
        $this->data['load_scripts'] = $load_js;
    }

    private function setStyles()
    {
        $load_css = array();
        $load_css[] = asset('pivot/cdnjs/c3.min.css');
        $load_css[] = asset('pivot/pivot.css');
        $load_css[] = asset('pivot/pivottable.custom1.css');
        $this->data['load_css'] = $load_css;
    }

    public function getMasterData()
    {
        $ministry_name = DB::table('mst_ministries')->select('id', 'name_lc') ->get();
        $ministry=array();
        foreach($ministry_name as $tran) { $ministry[$tran->id]=$tran->name_lc; }

        $fiscal_year_name = DB::table('mst_fiscal_years')->select('id', 'code as name_lc') ->get();
        $fiscalyear=array();
        foreach($fiscal_year_name as $tran) { $fiscalyear[$tran->id]=$tran->name_lc; }


        $arr['ministry']=$ministry;
        $arr['fiscalyear']=$fiscalyear;
        return $arr;

    }
  
}
