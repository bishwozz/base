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
            font-size: 26px;
            font-weight: bold;
        }
        .font-size-24 {
            font-size: 22px;
            font-weight: bold;
            line-height:2.2rem;
        }
        .font-size-18 {
            font-size: 17px;
            font-weight: bold;
            line-height:2.0rem;
        }
        .font-size-16 {
            font-size: 15px;
            font-weight: bold;
            line-height:1.5rem;
            margin-left:20%;
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
        .body{
            font-family: Kalimati;
            text-align: justify;
        }
        h5{
            font-size:16px;
        }

        table#meeting_agenda_table,
        table#meeting_att_table,
        table#meeting_decision_table
        {
        width: 100%;
        border-spacing: 0;
        table-layout: fixed;
        margin-bottom:25px;
        text-align: left;
        font-family:'Kalimati';
        }
        table#meeting_agenda_table tr,
        table#meeting_att_table tr,
        table#meeting_decision_table tr
        {
            border-spacing: 0;
        }
        table#meeting_agenda_table th,
        table#meeting_att_table th,
        table#meeting_decision_table th
        {
            border-bottom:1px solid black;
            border-right:1px solid black;
            border-top:1px solid black;
            border-spacing: 0;
            padding: 5px 5px;
            font-size:14px;
        }
        table#meeting_agenda_table th:first-child,
        table#meeting_att_table th:first-child,
        table#meeting_decision_table th:first-child
        {
            border-left:1px solid black;
        }
        table#meeting_agenda_table tr td,
        table#meeting_att_table tr td,
        table#meeting_decision_table tr td
        {
            font-size:14px;
            border-bottom:1px solid black;
            border-right:1px solid black;
            border-spacing: 0;
            padding: 5px 5px;
            font-family:'Kalimati';
        }
        table#meeting_agenda_table tr td:first-child,
        table#meeting_att_table tr td:first-child,
        table#meeting_decision_table tr td:first-child
        {
            border-left:1px solid black;
        }
        table#meeting_decision_table tr td:first-child, table#meeting_decision_table tr td:nth-child(2) {
            border-top:1px solid black;
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
        .underline{
            text-decoration: underline;

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
            font-size: 14px;
            float:right;
            font-family: 'Kalimati' !important;

        }
        .meeting-code{
            float:right;
            font-size: 14px;
            font-family: 'Kalimati' !important;


        }
        span{
            font-family: 'Kalimati' !important;

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
        .sachibname{
          margin-top:50px;
        }
        .meeting_att_table_tr, .meeting_att_table_td{
            border: none !important;
        }
        @media print {
            @page{
                size: A4 portrait;
                size: A4 portrait;
                margin-top: 40px;
                margin-left: 80px;
                margin-right: 80px;
                margin-bottom: 40px;
                text-align: justify;
            }
        }
    </style>
</head>
<body>
    <div class="main">
            <div class="image-left">
                <img src="{#asset /live/image/govtLogo.png @encoding=dataURI}" class="img-left">
            </div>
            <div class="head-body">
                <div class="letter_head">
                    <span  class="font-size-18">{{ isset($app_setting->letter_head_title_1) ? $app_setting->letter_head_title_1 : ''}}</span><br/>
                    <span  class="font-size-18">{{ isset($app_setting->letter_head_title_2) ? $app_setting->letter_head_title_2 : ''}}</span><br/>
                    <span  class="font-size-18">{{ isset($app_setting->letter_head_title_3) ? $app_setting->letter_head_title_3 : ''}}</span><br/>
                    {{-- <span  class="font-size-18">{{ isset($app_setting->letter_head_title_4) ? $app_setting->letter_head_title_4 : ''}}</span><br/> --}}
                </div>
            </div>
            <span style="float: right;"><span class="karyakaram">कार्यसुची संख्या:  {{convertToNepaliNumber(isset($meeting_minute->meeting_request->meeting_code)? $meeting_minute->meeting_request->meeting_code : '')}}</span><br><span class="meeting-code">म.प.बै.सं.: {{convertToNepaliNumber(isset($meeting_minute->meeting_request->meeting_code)? $meeting_minute->meeting_request->meeting_code : '')}}</span></span><br><br><br>
            <hr>

            <div class="body">
                <div class="body-title">
                    सम्वत्  {{$meeting_minute->meeting_request->meetingDateTimeNepali()}} लुम्बिनी प्रदेशको मुख्यमन्त्री तथा मन्त्रिपरिषद्को कार्यालयमा प्रदेश मन्त्रिपरिषद्को बैठकमा छलफल हुने विषय/प्रस्तावको कार्यसूची
                </div>
                <br/>
                <div class="body-content">
                    <div class="text-center center-heading">कार्यसूची</div>
                    <table id="meeting_agenda_table">
                        <thead>
                            <colgroup width="8%"></colgroup>
                            <colgroup width="17%"></colgroup> 
                            <colgroup width="35%"></colgroup>
                            <colgroup width="40%"></colgroup>
                            <tr>
                                <th class="th-center">क्र.सं.</th>
                                <th class="th-center">प्रस्ताव नं.</th>
                                <th class="th-center">मन्त्रालय</th>
                                <th class="th-center">विषय/प्रस्ताव</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($agendas as $agenda)
                            <tr>
                                <td class="td-center">{{convertToNepaliNumber($loop->iteration)}}.</td>
                                <td class="td-center">{{convertToNepaliNumber($agenda->agenda_number)}}</td>
                                <td class="">{{$agenda->ministry_name}}</td>
                                <td>{{$agenda->agenda_title}} बारे।</td>
                            </tr>
                            @endforeach
                        </tbody>
                        </table>
                        <div>
                            <br><br><br>
                            <span style="float: right; text-align: center;"> निर्देशानुसार,<br>
                            <br><br>....................<br>{{ $meeting_minute->getMinistryApproverName() }}<br>{{$meeting_minute->getMinistryApproverDesignation()}}</span><br><br><br>मिति: {{ convertToNepaliNumber(str_replace('-','/',$meeting_minute->meeting_request->start_date_bs)) }}
                        </div>
                </div>
            </div>
    </div>

    <div class="main page-break">
        <div class="image-left">
            <img src="{#asset /live/image/govtLogo.png @encoding=dataURI}" class="img-left">
        </div>
        <div class="head-body">
            <div class="letter_head">
                {{-- <span  class="font-size-36">{{ isset($app_setting->letter_head_title_1) ? $app_setting->letter_head_title_1 : ''}}</span><br/> --}}
                <span  class="font-size-18">{{ isset($app_setting->letter_head_title_1) ? $app_setting->letter_head_title_1 : ''}}</span><br/>
                <span  class="font-size-18">{{ isset($app_setting->letter_head_title_2) ? $app_setting->letter_head_title_2 : ''}}</span><br/>
                <span  class="font-size-18">{{ isset($app_setting->letter_head_title_3) ? $app_setting->letter_head_title_3 : ''}}</span><br/><br>
            </div>
        </div>
        <span style="float: right;">मं.प.बैठक बस्ने<br>मिति: {{ convertToNepaliNumber(str_replace('-','/',$meeting_minute->meeting_request->start_date_bs)) }}</span><br><br>
        <span  class="font-size-18 underline" >माननीय मन्त्रीहरुको उपस्थिति:-</span><br/><br/>

        <div class="body">
            <div class="body-content">
                <table id="meeting_att_table">
                    <colgroup width="10%"></colgroup>
                    <colgroup width="30%"></colgroup>
                    <colgroup width="20%"></colgroup>
                    <colgroup width="50%"></colgroup>
                    <tr>
                        <th class="th-center">क्र. सं.</th>
                        <th class="th-center">नाम, थर</th>
                        <th class="th-center">पद</th>
                        <th class="th-center">कार्य विभाजन</th>
                    </tr>
                    @php $i = 1; @endphp
                    @foreach($mps as $mp)
                        @if($mp['attendance'] == true)
                            <tr>
                                <td class="td-center">{{convertToNepaliNumber($i++)}}.</td>
                                <td>{{$mp['name']}}</td>
                                <td>{{$mp['post']}}</td>
                                <td>{{$mp['ministry']}}</td>
                            </tr>
                        @endif
                       
                    @endforeach
                </table>
                <div class="row">
                    <span class="present">उपस्थित: <span class="present_count">{{$present}}</span> </span><span class="absent">अनुपस्थित: <span class="absent_count">{{$absent}}</span></span>
                </div>
                <br>
                <div>
                    <span class="mantri_parisad">मन्त्रिपरिषद् गठन र पुनर्गठन मिति : {{ convertToNepaliNumber(str_replace('-','/',$app_setting->formation_of_council_ministers_date_bs)) }}</span>
                </div>
                <br/>
            </div>
        </div>
    </div>

    <div class="main page-break">
        <div class="image-left">
            <img src="{#asset /live/image/govtLogo.png @encoding=dataURI}" class="img-left">
        </div>
        <div class="head-body">
            <div class="letter_head">
                {{-- <span  class="font-size-36">{{ isset($app_setting->letter_head_title_1) ? $app_setting->letter_head_title_1 : ''}}</span><br/> --}}
                <span  class="font-size-18">{{ isset($app_setting->letter_head_title_1) ? $app_setting->letter_head_title_1 : ''}}</span><br/>
                <span  class="font-size-18">{{ isset($app_setting->letter_head_title_2) ? $app_setting->letter_head_title_2 : ''}}</span><br/>
                <span  class="font-size-18">{{ isset($app_setting->letter_head_title_3) ? $app_setting->letter_head_title_3 : ''}}</span><br/>
                {{-- <span  class="font-size-18">{{ isset($app_setting->letter_head_title_4) ? $app_setting->letter_head_title_4 : ''}}</span><br/> --}}
            </div>
        </div>
        <span style="float: right;"><span class="karyakaram">निर्णयहरु </span><br><span class="meeting-code">प्र.मं.प.बै.सं.: {{convertToNepaliNumber($meeting_minute->meeting_request->meeting_code)}}</span></span><br><br><br>
        <hr>

        <div class="body">
            <div class="body-title">
                सम्वत् {{$meeting_minute->meeting_request->meetingDateTimeNepali()}} लुम्बिनी प्रदेशको मुख्यमन्त्री तथा मन्त्रिपरिषद्को कार्यालयमा प्रदेश मन्त्रिपरिषद्को बैठकको उपस्थिति तथा निर्णय :-
            </div>
            <br/>
            <div class="body-content">
                <div class="text-left center-heading">उपस्थिति</div>
                <table id="meeting_att_table">
                    {{-- <colgroup width="8%"></colgroup>
                    <colgroup width="22%"></colgroup>
                    <colgroup width="40%"></colgroup>
                    <colgroup width="30%"></colgroup> --}}
                    {{-- <tr>
                        <th class="th-center">क्र. सं.</th>
                        <th class="th-center">पद</th>
                        <th class="th-center">नाम, थर</th>
                        <th class="th-center">हस्ताक्षर</th>
                    </tr> --}}
                    
                    @foreach($mps as $mp)
                        @if($mp['attendance'] == true)
                            <tr class="meeting_att_table_tr">
                                <td  class="meeting_att_table_td">{{$mp['post']}}</td>
                                <td class="meeting_att_table_td">{{$mp['name']}}</td>
                            </tr>
                        @endif
                    @endforeach
                </table>
                <br/>
                <div class="text-center center-heading">प्रदेश सरकार (कार्यसम्पादन) नियमावली, २०७४ बमोजिम देहायको प्रस्तावमा देहायमा लेखिए बमोजिम गर्ने निर्णय भयो-</div>
                <table id="meeting_decision_table">
                    <colgroup width="10%"></colgroup>
                    <colgroup width="90%"></colgroup>
                    @foreach($agendas as $agenda)
                        <tr>
                            <td class="td-center">{{convertToNepaliNumber($loop->iteration)}}.</td>
                            <td>{{$agenda->agenda_title}} बिषयको {{$agenda->ministry_name}}को {{convertToNepaliNumber($agenda->agenda_number)}}को प्रस्ताव -  {{ isset($agenda->agenda_decision_content) ? $agenda->agenda_decision_content : '"निर्णयको प्रकार छानी दिनुहोला।"'}}</td>
                        </tr>
                    @endforeach
                </table>
                <div class="sachibname">
                    <div style="display: flex;justify-content:space-between;">
                      <span>मितिः {{ convertToNepaliNumber(str_replace('-','/',$meeting_minute->meeting_request->start_date_bs)) }} </span>
                      <span>{{ $meeting_minute->getMinistryApproverName() }} <br><div style="text-align: center">{{($meeting_minute->getMinistryApproverDesignation())}}</div></span>
                    </div>
                  </div>
            </div>
        </div>
    </div>
</body>
</html>
