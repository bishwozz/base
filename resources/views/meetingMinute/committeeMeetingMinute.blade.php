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
            font-size: 32px;
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
            height:150px;
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
        .th-center{
            text-align: center;
        }
        .td-center{
            text-align: center;
        }
        .page-break{
            page-break-before: always;
        }
        @media print {
            @page{
                size: A4 portrait;  
                size: A4 portrait;
                margin-top: 40px;
                margin-left: 50px;
                margin-right: 20px;
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
                <span  class="font-size-36">{{ isset($app_setting->letter_head_title_1) ? $app_setting->letter_head_title_1 : ''}}</span><br/>
                <span  class="font-size-24">{{ isset($app_setting->letter_head_title_2) ? $app_setting->letter_head_title_2 : ''}}</span><br/>
                <span  class="font-size-18">{{ isset($app_setting->letter_head_title_3) ? $app_setting->letter_head_title_3 : ''}}</span><br/>
                {{-- <span  class="font-size-16">{{ isset($app_setting->letter_head_title_4) ? $app_setting->letter_head_title_4 : ''}}</span><br/> --}}
                <span  class="font-size-16">प्रदेश {{ $meeting_minute->meeting_request->meeting_code }}</span><br/>
            </div>
        </div>
        <span style="float: right;">{{($meeting_minute->verified_date_bs)?convertToNepaliNumber($meeting_minute->verified_date_bs):''}}</span><br>
        <hr>

        <div class="body">
            <div class="body-title">
                सम्वत् {{$meeting_minute->meeting_request->meetingDateTimeNepali()}} मुख्यमन्त्री तथा मन्त्रिपरिसद्को कार्यालय, लुम्बिनी प्रदेश दाङ, देउखुरीमा बसेको मन्त्रिपरिषद्को राजनीतिक समितिको बैठकको उपस्थिति तथा निर्णय:-
            </div>
            <br/>
            <div class="body-title">उपस्थिति:</div>
            <div class="body-content">
                {!! $meeting_minute->committee_attendance_detail !!}
            </div>
            <div class="body-content">
                    <div class="text-center center-heading">कार्यसूची</div>
                    <table id="meeting_agenda_table">
                        <thead>
                            <colgroup width="8%"></colgroup>
                            <colgroup width="15%"></colgroup>
                            <colgroup width="25%"></colgroup>
                            <colgroup width="50%"></colgroup>
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
                                <td class="td-center">{{$agenda->ministry_name}}</td>
                                <td>{{$agenda->agenda_title}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        </table>
                </div>
            <div class="body-content">
                <div class="text-center center-heading">निर्णयहरु</div>
                <table id="meeting_decision_table">
                    <colgroup width="10%"></colgroup>
                    <colgroup width="90%"></colgroup>
                    @foreach($agendas as $agenda)
                        <tr>
                            <td class="td-center">{{convertToNepaliNumber($loop->iteration)}}.</td>
                            <td>{{$agenda->decision_of_committee}}</td>
                        </tr>
                    @endforeach
                    {{-- @foreach(json_decode($meeting_minute->meeting_decisions) as $decisions)
                        <tr>
                            <td class="td-center">{{convertToNepaliNumber($loop->iteration)}}.</td>
                            <td>{{$decisions->decision}}</td>
                        </tr>
                    @endforeach --}}
                </table>

            </div>
        </div>
    </div>
</body>
</html>