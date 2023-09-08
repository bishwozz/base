<?php

namespace App\Base\Helpers;


class NepaliNumber
{
    public static function currencyFormat($value, $decimalPlaces = 2)
    {
        $pos = strpos($value, ".");  // find current position of decimal place
        // dd($pos);
        if ($pos === false) {
            $whole_number = abs($value);
            // dd($whole_number);
            $dec_digits = str_repeat("0", $decimalPlaces);
        } else {
            //decimals digits
            $dec_digits = substr($value, $pos);
            // dd($dec_digits);
            $dec_digits = round($dec_digits, $decimalPlaces);
            //  dd($dec_digits)
            $pos1 = strpos($dec_digits, ".");
            $dec_digits = substr($dec_digits, $pos1 + 1);
            // dd($dec_digits);
            $dec_digits = $dec_digits . str_repeat("0", $decimalPlaces - strlen($dec_digits));
            // dd($dec_digits);
            $whole_number = abs(substr($value, 0, $pos));
            // dd($whole_number);
        }
        $num = substr($whole_number, -3);        //get the last 3 digits
        // dd($num);
        $whole_number = substr($whole_number, 0, -3);    //omit the last 3 digits already stored in $num
        while (strlen($whole_number) > 0)        //loop the process - further get digits 2 by 2
        {
            $num = substr($whole_number, -2) . "," . $num;
            $whole_number = substr($whole_number, 0, -2);
        }
        if ($decimalPlaces < 1) {
            $result = $num;
        } else {
            if($dec_digits!= "00"){
                $result = $num . "|" . $dec_digits;
            }else{
                $result = $num;
            }
        }
        return $result;
    }
}
