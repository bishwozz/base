
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
        table#direct_agenda_table tr th,
        table#normal_agenda_table tr th{
            border:1px solid black;
            font-size:14px;
            border-spacing: 0;
            padding: 5px 5px;
            font-family:'Kalimati';
        }
        table#direct_agenda_table tr td,
        table#normal_agenda_table tr td{
            border:1px solid black;
            font-size:14px;
            border-spacing: 0;
            padding: 5px 5px;
            font-family:'Kalimati';
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
    <div class="mainp" id="fancybox-main" style="height: 100%;">
            <div class="image-left">
                <img src="{#asset /live/image/govtLogo.png @encoding=dataURI}" class="img-left">
            </div>
            <div class="head-body">
                <div class="letter_head">
                    <span  class="font-size-36">{{ isset($app_setting->letter_head_title_1) ? $app_setting->letter_head_title_1 : ''}}</span><br/>
                    <span  class="font-size-24">{{ isset($app_setting->letter_head_title_2) ? $app_setting->letter_head_title_2 : ''}}</span><br/>
                    <span  class="font-size-18">{{ isset($app_setting->letter_head_title_3) ? $app_setting->letter_head_title_3 : ''}}</span><br/>
                    {{-- <span  class="font-size-16">{{ isset($app_setting->letter_head_title_4) ? $app_setting->letter_head_title_4 : ''}}</span><br/> --}}
                </div>
            </div>
            <br>
            <br>
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
                            <colgroup width="5%"></colgroup>
                            {{-- <colgroup width="15%"></colgroup> --}}
                            <colgroup width="35%"></colgroup>
                            <colgroup width="50%"></colgroup>
                            <tr>
                                <th class="th-center">क्र.सं.</th>
                                {{-- <th class="th-center">प्रस्ताव नं.</th> --}}
                                <th class="th-center">मन्त्रालय</th>
                                <th class="th-center">विषय/प्रस्ताव</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($agendas as $agenda)
                            <tr>
                                <td class="td-center">{{convertToNepaliNumber($loop->iteration)}}.</td>
                                {{-- <td class="td-center">{{convertToNepaliNumber($agenda->agenda_number)}}</td> --}}
                                <td class="">{{$agenda->ministry_name}}</td>
                                <td>{{$agenda->agenda_title}} बारे।</td>
                            </tr>
                            @endforeach
                        </tbody>
                        </table><br><br>
                        <div class="text-center center-heading">प्रस्ताव विवरण</div>
                        <table id="normal_agenda_table">
                            <thead>
                                <colgroup width="6%"></colgroup>
                                <colgroup width="10%"></colgroup>
                                <colgroup width="20%"></colgroup>
                                <colgroup width="32%"></colgroup>
                                <colgroup width="32%"></colgroup>
                                <tr>
                                    <th class="th-center">क्र.सं.</th>
                                    <th class="th-center">प्रस्ताव नं.</th>
                                    <th class="th-center">मन्त्रालय</th>
                                    <th class="th-center">विषय/प्रस्ताव</th>
                                    <th class="th-center">म.प.को निर्णय</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($normal_agendas as $normal_agenda)
                                    <tr>
                                        <td class="td-center" style="font-size: 12px">{{convertToNepaliNumber($loop->iteration)}}</td>
                                        <td class="td-center" style="font-size: 12px">{{convertToNepaliNumber($normal_agenda->agenda_number)}}</td>
                                        <td class="td-center" style="font-size: 12px">{{$normal_agenda->ministry_name}}</td>
                                        <td class="td-center" style="font-size: 12px">{{$normal_agenda->agenda_title}}</td>
                                        <td class="td-center" style="font-size: 12px">{{$normal_agenda->decision_of_cabinet}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table><br><br><br>
                        <div class="text-center center-heading">ठाडो प्रस्ताव</div>
                        <table id="direct_agenda_table">
                            <thead>
                                <tr>
                                    <colgroup width="6%"></colgroup>
                                    <colgroup width="10%"></colgroup>
                                    <colgroup width="20%"></colgroup>
                                    <colgroup width="32%"></colgroup>
                                    <colgroup width="32%"></colgroup>

                                    <th class="th-center">क्र.सं.</th>
                                    <th class="th-center">प्रस्ताव नं.</th>
                                    <th class="th-center">मन्त्रालय</th>
                                    <th class="th-center">विषय/प्रस्ताव</th>
                                    <th class="th-center">म.प.को निर्णय</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($agendas as $agenda)
                                    <tr>
                                        <td class="td-center" style="font-size: 12px">{{convertToNepaliNumber($loop->iteration)}}</td>
                                        <td class="td-center" style="font-size: 12px">{{convertToNepaliNumber($agenda->agenda_code)}}</td>
                                        <td class="td-center" style="font-size: 12px">{{$agenda->ministry_name}}</td>
                                        <td class="td-center" style="font-size: 12px">{{$agenda->agenda_title}}</td>
                                        <td class="td-center" style="font-size: 12px">{{$agenda->decision_of_cabinet}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                </div>
            </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js" integrity="sha512-uURl+ZXMBrF4AwGaWmEetzrd+J5/8NRkWAvJx5sbPSSuOb0bZLqf+tOzniObO00BjHa/dD7gub9oCGMLPQHtQA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>