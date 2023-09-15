<?php

namespace App\Http\Controllers;

use Alert;
use App\Models\District;
use App\Models\LocalLevel;
use App\Imports\ExcelImport;
use App\Models\ProcessAnnex;
use App\Models\IndustryAnnex;
use App\Base\Helpers\PdfPrint;
use App\Models\CommerceProcess;
use App\Models\MstCommerceAnnex;
use App\Models\IndustryDocuments;
use App\Helpers\Letter\BaseLetter;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Helpers\Letter\Certificate;
use App\Models\CommerceProcessAnnex;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Helpers\Letter\PrivateLetter;
use App\Helpers\Letter\PartnershipLetter;
use App\Helpers\Letter\CommerceCertificate;
use App\Helpers\Letter\PublicLimitedLetter;
use App\Helpers\Letter\PrivateLimitedLetter;
use App\Helpers\Letter\CommercePrivateFirmLetter;
use Backpack\CRUD\app\Http\Requests\AccountInfoRequest;
use Backpack\CRUD\app\Http\Requests\ChangePasswordRequest;

class MyAccountController extends Controller
{
    protected $data = [];

    public function __construct()
    {
        $this->middleware(backpack_middleware());
    }

    /**
     * Show the user a form to change his personal information & password.
     */
    public function getAccountInfoForm()
    {
        $this->data['title'] = trans('backpack::base.my_account');
        $this->data['user'] = $this->guard()->user();

        return view(backpack_view('my_account'), $this->data);
    }

    /**
     * Save the modified personal information for a user.
     */
    public function postAccountInfoForm(AccountInfoRequest $request)
    {
        $result = $this->guard()->user()->update($request->except(['_token']));

        if ($result) {
            Alert::success(trans('backpack::base.account_updated'))->flash();
        } else {
            Alert::error(trans('backpack::base.error_saving'))->flash();
        }

        return redirect()->back();
    }

    /**
     * Save the new password for a user.
     */
    public function postChangePasswordForm(ChangePasswordRequest $request)
    {
        $user = $this->guard()->user();
        $user->password = Hash::make($request->new_password);

        if ($user->save()) {
            Alert::success(trans('backpack::base.account_updated'))->flash();
        } else {
            Alert::error(trans('backpack::base.error_saving'))->flash();
        }

        return redirect()->back();
    }

    /**
     * Get the guard to be used for account manipulation.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return backpack_auth();
    }

    
    public function manual($id){
        switch($id)
        {
            case 1:
                $location = public_path()."/Manual/superAdmin_easyIndustry.pdf";
                $filename = 'Super Admin Manual.pdf';
            break;
            case 2:
                $location = public_path()."/Manual/applicantManual.pdf";
                $filename = 'Application Manual.pdf';
            break;
            // case 3:
            //     $location = public_path()."/Manual/Province_easyIndustry.pdf";
            //     $filename = 'Province Admin Manual.pdf';
            // break;
            // case 4:
            //     $location = public_path()."/Manual/superAdmin_easyIndustry.pdf";
            //     $filename = 'Super Admin Manual.pdf';
            // break;
            // case 5:
            //     $location = public_path()."/Manual/commerceManual.pdf";
            //     $filename = 'Commerce Manual.pdf';
            // break;
            // case 6:
            //     $location = public_path()."/Manual/applicantManual.pdf";
            //     $filename = 'applicant Manual.pdf';
            // break;

            default:
            break;

        }
        // Optional: serve the file under a different filename:
        // optional headers
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"'
        ];
        return response()->file($location,$headers);
    }

    public function deleteFile($document_id,$filepath,$model)
    {
        // dd($document_id,base64_decode($filepath),$model);

        $filepath = base64_decode($filepath);
        $industry_docs = $model::findOrFail($document_id);
        $files = [];
        if(isset($industry_docs->received_documents)){
            foreach($industry_docs->received_documents as $doc){
                if(!($doc == $filepath)){
                    $files[] = $doc;
                }
            }
            $model::whereId($document_id)->update([
              'received_documents' => $files,
              'remarks' => 'delete document',
            ]);

        }else{
            foreach($industry_docs->files as $doc){
                if(!($doc == $filepath)){
                    $files[] = $doc;
                }
            }

            $industry_docs->preventAttrSet = true;
            $industry_docs->files = $files;
            $industry_docs->remarks = 'delete document';
            $industry_docs->save();
        }
        
        $path=storage_path('app/public/'. $filepath);
        // dd(File::exists($path),$path);

        if (File::exists($path)) {
            File::delete($path);
        }
        return back();
    }

    public function getLocalLevel($district_id)
    {
        return LocalLevel::where('district_id',$district_id)->pluck('id','name_lc')->all();
    }
    public function getDistrict($province_id)
    {
        return District::where('province_id',$province_id)->pluck('id','name_lc')->all();
    }

    public function copyAddress($industry_id)
    {
        $industry = \App\Models\Industry::find($industry_id);
        $district = $industry->applicant_district_id;
        $applicant_name_lc = $industry->applicant_first_name_lc . ' ' . $industry->applicant_middle_name_lc . ' ' . $industry->applicant_last_name_lc;
        $applicant_name_en = $industry->applicant_first_name_en . ' ' . $industry->applicant_middle_name_en . ' ' . $industry->applicant_last_name_en;
        $address = $industry->applicant_localLevel->name_lc.'-'.$industry->applicant_ward_number.', '.$industry->applicant_district->name_lc.', '.$industry->applicant_province->name_lc;
        $phone = $industry->applicant_phone;
        $mobile = $industry->applicant_mobile;
        $email = $industry->applicant_email;
        $data = [
            'phone'=>$phone,
            'mobile'=>$mobile,
            'email'=>$email,
            'address'=>$address,
            'district'=>$district,
            'applicant_name_lc'=>$applicant_name_lc,
            'applicant_name_en'=>$applicant_name_en
        ];
        // $data = json_encode($data);
        return $data;
    }

    public function copyCommerceAddress($commerce_process_id)
    {
        $commerceProcess = \App\Models\CommerceProcess::find($commerce_process_id);
        $district = $commerceProcess->applicant_district_id;
        $applicant_name_lc = $commerceProcess->applicant_first_name_lc . ' ' . $commerceProcess->applicant_middle_name_lc . ' ' . $commerceProcess->applicant_last_name_lc;
        $applicant_name_en = $commerceProcess->applicant_first_name_en . ' ' . $commerceProcess->applicant_middle_name_en . ' ' . $commerceProcess->applicant_last_name_en;
        $address = $commerceProcess->applicant_localLevel->name_lc.'-'.$commerceProcess->applicant_ward_number.', '.$commerceProcess->applicant_district->name_lc.', '.$commerceProcess->applicant_province->name_lc;
        $phone = $commerceProcess->applicant_phone;
        $mobile = $commerceProcess->applicant_mobile;
        $email = $commerceProcess->applicant_email;
        $data = [
            'phone'=>$phone,
            'mobile'=>$mobile,
            'email'=>$email,
            'address'=>$address,
            'district'=>$district,
            'applicant_name_lc'=>$applicant_name_lc,
            'applicant_name_en'=>$applicant_name_en
        ];
        // $data = json_encode($data);
        return $data;
    }

    public function preview_data($industry_id)
    {
        $data = DB::select(
            "SELECT
            i.name_lc as industry_name,i.file_number as file_number ,i.location_ward_number as industry_ward,i.location_street_ward as street_name, 
            i.location_kittas as kittas,i.ownership_type_id as ownership_type_id,i.other_subcategory as other_subcategory,
            app.name_lc as office_name, app.phone as phone, app.fax as fax,
            lp.name_lc as industry_province, ld.name_lc as industry_district, ll.name_lc as industry_ll, op.name_lc as owner_province,od.name_lc as owner_district, ol.name_lc as owner_ll,
            i.applicant_ward_number as applicant_ward,
            concat(i.applicant_first_name_lc,' ',i.applicant_middle_name_lc, ' ' ,i.applicant_last_name_lc) as applicant_name,
             i.applicant_phone as applicant_phone,i.applicant_mobile as applicant_mobile,i.applicant_email as applicant_email,
            i.contact_person_name as contact_person, i.contact_phone as contact_phone,i.contact_mobile as contact_mobile,i.contact_email as contact_email,i.operation_days_in_year as yearly_operation_days,i.operation_shifts_per_day as daily_operation_shifts,
            ow.owner_name as owner_name, ow.owner_address as owner_address,category.name_lc as idnustry_cate,
            officell.name_lc office_local_level_lc, odd.name_lc as office_district_lc,
            master.registration_date_bs, master.registration_date_ad,ot.name_lc as ownership_type_lc,
            i.total_capital_total as total_capital, i.fixed_capital_total as fixed_capital,i.fixed_capital_self as fixed_capital_self,i.fixed_capital_loan as fixed_capital_loan,i.fcd_land_area_in_sqft as land_area_in_sqft,i.fcd_land_value as land_value,i.fcd_land_remarks as land_remarks,i.*,
            i.current_capital_self as current_capital_self,i.current_capital_loan as current_capital_loan,i.total_capital_self as total_capital_self,i.total_capital_loan as total_capital_loan,
            i.current_capital_total as current_capital, i.production_start_date_bs as production_start_date_bs, i.electricity_consumption as electricity_consumption,
            i.electricity_consumption_amount,isc.subcategories as sub_category,master.registration_number as registration_number,own.owner_photo as photo,own.citizenship_number as citizenship_number,own.citizenship_issued_date_bs as citizenship_issued_date,own.mailing_address as mailing_address,
            cid.name_lc as citizenship_issued_district,mit.name_lc as industry_type, dpi.items as production_items,iut.utilities as industry_utilities,dhr.human_resource as human_resources,iect.env_components as env_components,dio.owners_detail as owners_detail,mc.name_lc as foreign_investment_country_name
            
            From dt_industry_master as master
            INNER JOIN app_office as app on master.office_id = app.id
            INNER JOIN dt_industry as i on master.id = i.industry_master_id
            INNER JOIN mst_ownership_type ot on i.ownership_type_id=ot.id
            -- LEFT JOIN dt_industry_external_approval as ea on i.id = ea.industry_id
            LEFT JOIN dt_industry_annex as ia on ia.industry_id = i.id
            LEFT JOIN mst_process_annex mia on mia.id = ia.process_annex_id
            LEFT JOIN dt_industry_owners as own on i.id = own.industry_id
            LEFT JOIN mst_industry_category as category on i.industry_category_id = category.id
            LEFT JOIN (select industry_id, ARRAY_TO_STRING(ARRAY_AGG(sc.name_lc), ', ') as subcategories
            from dt_industry_sub_categories isc
                LEFT JOIN mst_industry_subcategory sc on isc.sub_category_id = sc.id
            group by industry_id
            ) as isc on isc.industry_id = i.id
            LEFT JOIN (select industry_id, ARRAY_TO_STRING(ARRAY_AGG(item_name_lc ||';'||yearly_production_capacity || ';' || (select mu.name_lc from mst_unit mu where  mu.id = unit_id)||';'||sales_price||';'||total_sales_amount),',') as items from dt_industry_production_items dipt group by industry_id)as dpi on dpi.industry_id = i.id
            LEFT JOIN (select industry_id,ARRAY_TO_STRING(ARRAY_AGG((select mut.name_lc from mst_utility_type mut where mut.id = utility_type_id) || ';' || (case utility_type_id when 7 then other_machinary else '' end)),'~') as utilities from dt_industry_utilities diu group by industry_id) as iut on iut.industry_id = i.id
            LEFT JOIN (select industry_id, ARRAY_TO_STRING(ARRAY_AGG((select mht.name_lc from mst_hr_type mht where mht.id = hr_type) || ';' || local_indigenous_count || ';' || foreign_total || ';' || total),',') as human_resource from dt_industry_human_resource dihr  group by industry_id) as dhr on dhr.industry_id = i.id
            LEFT JOIN (select industry_id,ARRAY_TO_STRING(ARRAY_AGG(name_lc),', ') as owner_name, ARRAY_TO_STRING(ARRAY_AGG(mailing_address),', ') as owner_address from dt_industry_owners group by industry_id) as ow on ow.industry_id=i.id  
            LEFT JOIN (select industry_id,ARRAY_TO_STRING(ARRAY_AGG(name_lc || ';' || father_name || ';' || grand_father_name ||';'|| citizenship_number ||';'||passport_number||';'||phone||';'||mobile||';'||email||';'||share_investment_percentage||';'||mailing_address),':') as owners_detail from dt_industry_owners group by industry_id) as dio on dio.industry_id = i.id
            LEFT JOIN (select industry_id, ARRAY_TO_STRING(ARRAY_AGG((select mec.name_lc from mst_environment_component mec where mec.id = environment_component_d) || ';' || (case is_distance_in_km when true then distance_in_km || ' कि.मी.' else distance_in_meters || ' मी.' end) ||';'|| any_adverse_effect || ';' || (case any_adverse_effect when true then adverse_effect_mitigation_measures else '' end)),',') as env_components from dt_industry_env_components diec group by industry_id) as iect on iect.industry_id = i.id
            LEFT JOIN mst_fed_province lp on i.location_province_id = lp.id
            LEFT JOIN mst_fed_district ld on i.location_district_id = ld.id
            LEFT JOIN mst_fed_local_level ll on i.location_local_level_id = ll.id
            LEFT JOIN mst_fed_province op on i.applicant_province_id = op.id
            LEFT JOIN mst_fed_district od on i.applicant_district_id = od.id
            LEFT JOIN mst_fed_local_level ol on i.applicant_local_level_id = ol.id 
            LEFT JOIN mst_fed_local_level officell on app.local_level_id = officell.id 
            LEFT JOIN mst_fed_district odd on app.district_id=odd.id
            LEFT JOIN mst_fed_district cid on own.citizenship_issued_district_id = cid.id
            LEFT JOIN mst_industry_type mit on i.industry_type_id = mit.id 
            LEFT JOIN mst_country mc on i.fi_country_id = mc.id
            
            
            where i.id = ?
                ",[$industry_id]
          );
        // dd($data);
        $data = $data[0];
        $view = 'admin.industry.preview';
        $html = view($view, compact('data'))->render();
        $file_name = \App\Models\Industry::find($industry_id)->name_lc;
        $res = PdfPrint::printPortrait($html, $file_name);
    }
}
