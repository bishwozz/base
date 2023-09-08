<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Base\BasePivotController;
use Illuminate\Support\Facades\DB;

class PivotReportController extends BasePivotController
{
    public function index()
    {   
        $this->setUp();
        $this->customScript();
        return $this->fromView('pivot_report');
    }

    public function customScript()
    {
        $this->data['script_js'] = '
        $(function(){
            var dataUrl,masterUrl;
            $.ajax({
              url: "pivotdata",
              cache: false,
              success: function(data){
                  dataUrl = data;
                        $.ajax({
                          url: "masterdata",
                          cache: false,
                          success: function(data){
                            masterUrl = data;  
                            pivotTableWorking.loadAndRender({
                                  pivotDataUrl : dataUrl, 
                                  masterDataUrl : masterUrl,
                                  rows : ["मन्त्रालयहरु"],
                                  cols : [""]
                              });
                              }
                        });                
                  }
            });
          });

        window.pivotTableWorking = (function(params) {
            var getWorkingDerivedMapping = function() {
                var opt = {
                    "मन्त्रालयहरु": { master: "ministry", "data_field": "ministry_id" },
                    "आर्थिक वर्ष": { master: "fiscalyear", "data_field": "fiscal_year_id" },
                };
                return opt;
            };
            var getLabels = function() {
                var opt =  {
                    "project_count": "जम्मा कार्यक्रम / आयोजना ",
                    "new_pending": "नयाँ-बनेका ऐन/कानुन",
                    "new_progress": "नयाँ-निर्माणाधीन ऐन/कानुन",
                    "new_completed": "नयाँ-बनाउनुपर्ने ऐन/कानुन",
                    "ammendent_pending": "संशोधन-संसोधन भएका ऐन/कानुन",
                    "ammendent_progress": "संशोधन-संसोधन प्रक्रियमा भएका ऐन/कानुन",
                    "ammendent_completed": "संशोधन-संसोधन गर्नुपर्ने ऐन/कानुन",
                };
                return opt;
            };

            var loadAndRender = function(params) {
                params.hiddenAttributes = params.hiddenAttributes || [];
                params.hiddenAttributes.push("id");
                params.derivedAttributes = params.derivedAttributes || {};
                params.derivedMapping = params.derivedMapping || {};

                params.derivedMapping = params.derivedMapping || {};
                $.each(getWorkingDerivedMapping(), function (key,item) {
                    params.derivedMapping[key] = item;
                });

                params.rows = params.rows || [];
                params.cols = params.cols || [];

                
                if (params.rows.length == 0) {
                }
                
                params.labels = getLabels();
                pivotTableHelper.loadAndRender(params);
            };
            return {
                loadAndRender: loadAndRender
            }

        })();';
    }

   
    public function getPivotData() 
    {
        echo "ministry_id,fiscal_year_id,project_count,new_pending,new_progress,new_completed,ammendent_pending,ammendent_progress,ammendent_completed" . "\r\n";

        $pivotdata = DB::SELECT(DB::raw("SELECT mm.id as ministry_id,mfy.id as fiscal_year_id,pp.project_name as project_count,
        CASE WHEN mal.type = 0 AND mal.status = 0 THEN 'छ' ELSE 'छैन' END AS new_pending,
        CASE WHEN mal.type = 0 AND mal.status = 1 THEN 'छ' ELSE 'छैन' END AS new_progress,
        CASE WHEN mal.type = 0 AND mal.status = 2 THEN 'छ' ELSE 'छैन' END AS new_completed,
        CASE WHEN mal.type = 1 AND mal.status = 0 THEN 'छ' ELSE 'छैन' END AS amendment_pending,
        CASE WHEN mal.type = 1 AND mal.status = 1 THEN 'छ' ELSE 'छैन' END AS amendment_progress,
        CASE WHEN mal.type = 1 AND mal.status = 2 THEN 'छ' ELSE 'छैन' END AS amendment_completed
        from mst_ministries mm
        left join pt_project pp on pp.ministry_id = mm.id
        left join mst_fiscal_years mfy on mfy.id = pp.fiscal_year_id
        left join ministry_darbandi md on md.ministry_id = mm.id
        left join mst_level ml on ml.id = md.level_id
		left join ministry_act_laws mal on mal.ministry_id = mm.id
        where pp.deleted_uq_code = 1 and mm.deleted_uq_code = 1
        "));

        $resp = array();
        foreach ($pivotdata as $entry) {
            $row = array();
            foreach ($entry as $key => $value) {
                array_push($row, $value);
            }
            array_push($resp, implode(',', $row));
        }
        echo implode("\r", $resp);

    }

}
