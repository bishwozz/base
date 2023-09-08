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
            padding-bottom: 10px;
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
        table#meeting_agenda_table
        {
            width: 100%;
            border-spacing: 0;
            table-layout: fixed;
            margin-bottom:25px;
            text-align: left;
            font-family:'Kalimati';
        }
        table#meeting_agenda_table tr
        {
            border-spacing: 0;
        }
        table#meeting_agenda_table th
        {
            border-bottom:1px solid black;
            border-right:1px solid black;
            border-top:1px solid black;
            border-spacing: 0;
            padding: 5px 5px;
            font-size:14px;
        }
        table#meeting_agenda_table th:first-
        {
            border-left:1px solid black;
        }
        table#meeting_agenda_table tr td
        {
            font-size:14px;
            border-bottom:1px solid black;
            border-right:1px solid black;
            border-spacing: 0;
            padding: 5px 5px;
            font-family:'Kalimati';
        }
        table#meeting_agenda_table tr td:first-child,
        table#meeting_agenda_table tr th:first-child
        {
            border-left:1px solid black;
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
        .td-left{
            text-align: left;

        }
        .td-center{
            text-align: center;
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
                <span  class="font-size-18">{{ isset($app_setting->letter_head_title_1) ? $app_setting->letter_head_title_1 : ''}}</span><br/>
                <span  class="font-size-18">{{ isset($app_setting->letter_head_title_2) ? $app_setting->letter_head_title_2 : ''}}</span><br/>
                <span  class="font-size-18">{{ isset($app_setting->letter_head_title_3) ? $app_setting->letter_head_title_3 : ''}}</span><br/>
                {{-- <span  class="font-size-18">{{ isset($app_setting->letter_head_title_4) ? $app_setting->letter_head_title_4 : ''}}</span><br/> --}}
                {{-- <span  class="font-size-16">प्रदेश {{ $meeting_request->meeting_code }}</span><br/> --}}
            </div>
        </div>
        <hr>

        <div class="body">
            <div class="body-title">
                {{--सम्वत्  {{$start_date_bs->meetingDateTimeNepali()}} सुदूरपश्चिम प्रदेशको मुख्यमन्त्री तथा मन्त्रिपरिषद्को कार्यालयमा प्रदेश मन्त्रिपरिषद्को बैठकमा छलफल हुने विषय/प्रस्तावको कार्यसूची--}}

                मन्त्रिपरिषद्को बैठक यहि मिति {{$meeting_date_bs}} मा बस्ने निर्णय भएको छ| <br>
                स्थान: मुख्यमन्त्री तथा मन्त्रिपरिषद्को कार्यालय, राप्ती उपत्यका (देउखुरी), नेपाल<br>
                समय: {{ $meeting_start_time }}<br>
            </div>
            <br/>
            <div class="body-content">
                <div class="text-center center-heading">कार्यसूची</div>
                <table id="meeting_agenda_table">
                    <thead>
                        <colgroup width="6%"></colgroup>
                        <colgroup width="38%"></colgroup>
                        <colgroup width="16%"></colgroup>
                        <colgroup width="40%"></colgroup>
                        <tr>
                            <th class="th-center">क्र.सं.</th>
                            <th class="th-center">प्रस्ताव दर्ता गर्ने मन्त्रालय</th>
                            <th class="th-center">प्रस्तावको प्रकार</th>
                            <th class="th-center">प्रस्तावको विषय</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($agendas as $agenda)
                        <tr>
                            <td class="td-center">{{convertToNepaliNumber($loop->iteration)}}.</td>
                            <td class="td-left">{{$agenda->ministry_name}}</td>
                            <td class="td-center">{{$agenda->agenda_type}}</td>
                            <td class="td-left">{{$agenda->agenda_title}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    </table>
            </div>
        </div>
    </div>
</body>
</html>
