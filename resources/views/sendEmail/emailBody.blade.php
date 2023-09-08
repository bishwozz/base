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

        span {
            text-decoration: underline;
        }

        .sign {
            margin-top: 20px;
            float: right;
            text-align: right;
        }
        .minister_name{
            text-align: center;
        }
        .pramukh{
            text-align: center;
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
                font-size: 13px !important;
                text-align: justify !important;
            }
        }

        body {
            font-family: 'Kalimati' !important;
            font-size: 13px !important;
            text-align: justify !important;
            font-family: 'Kalimati' !important;

        }
    </style>
</head>

<body>
    <div style="color: red;">
        <div>
            <p class="p-body">श्री सचिव ज्यू, <br> {{ $agenda->ministry_entity->name_lc }} ।</p><br>
        </div>
        <div>
            <p class="p-body">
                {{ $agenda->agenda_title }} विषयको {{ $agenda->ministry_entity->name_lc }} को नं.
                {{ convertToNepaliNumber($agenda->agenda_number) }} को प्रस्ताव म.प.बै.सं.
                {{ convertToNepaliNumber(isset($agenda->ecMeetingRequest->meeting_code)? $agenda->ecMeetingRequest->meeting_code : '') }} मिति
                {{ convertToNepaliNumber(str_replace('-', '/', isset($agenda->ecMeetingRequest->start_date_bs)? $agenda->ecMeetingRequest->start_date_bs: '' )) }} को प्रदेश
                मन्त्रिपरिषद्को बैठकमा पेश हुँदा त्यसमा प्रदेश सरकार, मन्त्रिपरिषद्ले
                देहायबमोजिम निर्णय गरेकाले सो बमोजिम कार्यान्वयन हुन प्रदेश सरकार (कार्यसम्पादन) नियमावली,
                २०७४ को नियम २४ बमोजिम अनुरोध गरेको छु।</p><br>

            <div style="text-align: center">
                <span >प्रदेश सरकारको निर्णय
                </span> <br><br>
                <tr>
                    <td>{{ isset($agenda_history->decision_of_cabinet) ? $agenda_history->decision_of_cabinet : '"निर्णयको प्रकार छानी दिनुहोला।"' }}</td>
                </tr>
            </div><br>
            <div class="sign">
                .............................<br>
                <div class="minister_name">
                    {{ $agenda_history->employee_entity()}} 
                </div>
                <div class="pramukh">
                प्रमुख सचिव
                </div>
            </div>
            <p>मिति: {{ convertToNepaliNumber(str_replace('-', '/', isset($agenda->ecMeetingRequest->start_date_bs)? $agenda->ecMeetingRequest->start_date_bs: '' )) }}</p>
        </div>
    </div>
</body>

</html>
