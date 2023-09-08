<?php

namespace App\Utils;
use Illuminate\Support\Facades\Config;

class PdfPrint  
{
    private static $jsreport_url = "";
    public static function printLandscape($content, $file_name, $recipe = "chrome-pdf"){
        return self::print("HJ_UbfC4P", $content, $recipe, "none", $file_name);
    }
    public static function printPortrait($content, $file_name, $recipe = "chrome-pdf"){
        return self::print("HJ_UbfC4P", $content, $recipe, "none", $file_name);
    }
    private static function loadJsReportConfig(){
        self::$jsreport_url= Config('report.jsreport_url');
    }

    public static function storeprintPortrait($content, $file_name, $recipe = "chrome-pdf"){
        return self::store_print("HJ_UbfC4P", $content, $recipe, "none", $file_name);
    }

    public static function print($shortid, $content, $recipe, $engine, $file_name){
        
        self::loadJsReportConfig();
        $post_fields['template']['shortid']=$shortid;
        $post_fields['template']['content']=$content;
        $post_fields=\json_encode($post_fields);
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => self::$jsreport_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false, 
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS =>$post_fields,
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Cookie: render-complete=true"
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
            dd($err);
        } else {
            header("Content-type:application/pdf");
            // It will be called downloaded.pdf
            header("Content-Disposition:inline;filename=".$file_name);
            echo $response;
        }
        exit();
    }
  
    public static function store_print($shortid, $content, $recipe, $engine, $file_name){
        self::loadJsReportConfig();
        // $post_fields['phantom']['printDelay']=2000;
        // $post_fields['phantom']['blockJavaScript']=false;
        // $post_fields['phantom']['waitForJS']=true;

        $post_fields['template']['shortid']=$shortid;
        $post_fields['template']['content']=$content;
        $post_fields=\json_encode($post_fields);
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => self::$jsreport_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false, 
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS =>$post_fields,
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Cookie: render-complete=true"
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        return $response;
        // file_put_contents('D:\servers\laragon3.2\www\cmis\public\storage\xyz.pdf',$response);
        // dd($response);
        // if ($err) {
        //     echo "cURL Error #:" . $err;
        //     dd($err);
        // } else {
        //     header("Content-type:application/pdf");
        //     // It will be called downloaded.pdf
        //     header("Content-Disposition:inline;filename=".'hellow.pdf');
        //     echo $response;
        // }
        // exit();
    }
}


