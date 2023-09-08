<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        @font-face {
            font-family: 'Kalimati';
            font-style: normal;
            font-weight: normal;
            src: url({#asset /Kalimati.ttf @encoding=dataURI});
            format('truetype');
        }
        .font-size-36 {
            font-size: 20px;
            font-weight: bold;
        }
        .font-size-24 {
            font-size: 18px;
            font-weight: bold;
            line-height:2.2rem;
        }
        .font-size-18 {
            font-size: 19px;
            font-weight: bold;
            line-height:2.0rem;
        }
        .font-size-16 {
            font-size: 15px;
            font-weight: bold;
            line-height:1.5rem;
            margin-left:20%;
            text-align:center;
            margin-right: 100px;
        }

        .head-body{
            text-align:center;
            width:90%;
            line-height:1.5;
            font-family:'Kalimati';
        }
        .image-left{
            float: left;
            margin-bottom: 5px;
        }
        .image-left img{
            height: 100px;
            width: 120px;
        }
        .letter_head{
            height:160px;
            text-align: center !important;

        }
        body{
            font-family: Kalimati !important;
            text-align: justify;
        }
        span{
            font-family: Kalimati !important;

        }
        h5{
            font-size:16px;
        }

        .text-center{
            text-align: center;
        }
        .center-heading{
            text-decoration: underline;
            font-weight: bold;
            font-size: 14px;
            margin-bottom:5px;
        }
        .th-center{
            text-align: center;
        }
        .td-center{
            text-align: center;
        }
        .font-size-20{
            font-size: 24px;
            text-align: center !important;
            margin-left: 70px;
            font-weight: bold;
        }
        .page-break{
            page-break-before: always;
        }
        .karyakaram{
            font-size: 24px;
            float:right;
        }
        .meeting-code{
            float:right;

        }
        .absent{
            float:right
        }
        .absent_count{
            display: inline-block;
            padding: 5px;
            padding-right:12px;
            padding-left:12px;
            border: 1px solid black;
            border-radius: 5px;
        }

        .underline{
          text-decoration: underline;
        }
        .present_count{
            display: inline-block;
            padding: 5px;
            padding-right:12px;
            padding-left:12px;
            border: 1px solid black;
            border-radius: 5px;
        }
        .sign{
            text-align: right;
        }
        @media print {
            @page {
                size: A4 portrait;
                font-family: 'Kalimati';
                margin-top: 100px;
                margin-left: 100px;
                margin-right: 80px;
                text-decoration-color: red;
                margin-bottom: 40px;
                font-size: 12px !important;
                text-align: justify !important;
            }
        }
        .justify{
            text-align: justify;
        }
    </style>
</head>
<body>
    <div class="main">
            {{-- <div class="image-left">
                <img src="{#asset /live/image/govtLogo.png @encoding=dataURI}" class="img-left">
            </div>
            <div class="head-body">
                <div class="letter_head">
                    <span  class="font-size-36">{{ isset($app_setting->letter_head_title_1) ? $app_setting->letter_head_title_1 : ''}}</span><br/>
                    <span  class="font-size-24">{{ isset($app_setting->letter_head_title_2) ? $app_setting->letter_head_title_2 : ''}}</span><br/>
                    <span  class="font-size-18">{{ isset($app_setting->letter_head_title_3) ? $app_setting->letter_head_title_3 : ''}}</span><br/>
                    <span  class="font-size-16">{{ isset($app_setting->letter_head_title_4) ? $app_setting->letter_head_title_4 : ''}}</span><br/>
                </div>
            </div> --}}
            <br>
            <br>
            {{-- <span style="float: left;">पत्र संख्या: </span><br><br>

            <span><span style="float: left;">चलानी नं.: </span><span class="meeting-code" style="float: right;">मिति: {{convertToNepaliNumber(str_replace('-','/',$agenda->ecMeetingRequest->start_date_bs))}}</span></span><br><br><br> --}}

              <div>
                <p>श्री सचिवज्यू, <br> {{$agenda->ministry_entity->name_lc}} ।</p><br>
              </div>
              <div>
                <p class="justify">
                  {{$agenda->agenda_title}} विषय म.प.बै.सं.  {{convertToNepaliNumber($agenda->agenda_code)}} मिति
                  {{convertToNepaliNumber(str_replace('-','/',$agenda->ecMeetingRequest->start_date_bs))}}  को प्रदेश मन्त्रिपरिषद्को बैठकमा पेश हुँदा त्यसमा प्रदेश सरकार,  मन्त्रिपरिषद्ले
                  देहायबमोजिम निर्णय गरेकाले सो बमोजिम कार्यान्वयन हुन प्रदेश सरकार (कार्यसम्पादन) नियमावली,
                  २०७४ को नियम २४ बमोजिम अनुरोध गरेको छु-</p><br>
                <div style="text-align: center">
                  <span class="underline">प्रदेश सरकारको निर्णय-
                  </span > <br>
                      <tr >
                          {{-- <td>{{ isset($agenda_history->decision_of_cabinet) ? ".$agenda_history->decision_of_cabinet." : '"निर्णयको प्रकार छानी दिनुहोला।"'}}</td> --}}
                        <td >{{ isset($agenda_history->decision_of_cabinet) ? $agenda_history->decision_of_cabinet : 'निर्णय लेख्नुहोस' }}</td>
                      </tr>
                </div>
                <br> 
                <div style="display:flex; justify-content:space-between;">
                    <p>मिति : {{convertToNepaliNumber(str_replace('-','/',$agenda->ecMeetingRequest->start_date_bs))}}</p>
                    <div class="sign">
                        ....................<br><br>
                        {{ $chief_secretary }}<br>
                        
                        प्रमुख सचिव
                        <br>
                        <br>
                    </div>
                    
                </div>
                
                
              </div>
    </div>
</body>
</html>
