
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Report</title>
    <style>
        #table {
            font-family: "Kalimati";
            border-collapse: collapse;
            font-size: 12px;
            width: 100%;
        }

        @font-face {
            font-family: 'Kalimati';
            font-style: normal;
            font-weight: normal;
            src: url({#asset /Kalimati.ttf @encoding=dataURI});
            format('truetype');
        }

        @media print {
            @page {
                size: A4 landscape;
                margin-top: 40px;
                margin-left: 40px;
                margin-right: 40px;
            }
        }

        #table td,
        #table th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            font-weight: bold;
        }
        .even-row {
            background-color: #f2f2f2;
        }
        .odd-row {
            background-color: #ffffff;
        }

    </style>
</head>

<body>
    <table id="table">
        <thead>
            <tr>
                <th colspan="19" style="text-align: center; background-color:black;color:white;"> मन्त्रिपरिषदको बैठकमा भएका विभिन्न प्रस्तावका निर्णय विवरणहरु </th>
            </tr>
            <tr>
                <th style="background-color: #9eda74;"> क्र.सं. </th>
                <th style="background-color: #9eda74;"> कार्यसूची संख्या </th>
                <th style="background-color: #9eda74;"> बैठक संख्या </th>
                <th style="background-color: #9eda74;"> आ.व. </th>
                <th style="background-color: #9eda74;"> साल </th>
                <th style="background-color: #9eda74;"> महिना </th>
                <th style="background-color: #9eda74;"> गते  </th>
                <th style="background-color: #9eda74;"> समय </th>
                <th style="background-color: #9eda74;"> बार </th>
                <th style="background-color: #9eda74;"> प्रस्ताव दर्ता गर्ने मन्त्रालय </th>
                <th style="background-color: #9eda74;"> प्रस्तावको प्रकार  </th>
                <th style="background-color: #9eda74;"> प्रस्तावको विषय </th>
                <th style="background-color: #9eda74;"> प्रस्ताव संख्या </th>
                <th style="background-color: #9eda74;"> प्रस्ताव स्वीकृत मिति </th>
                <th style="background-color: #9eda74;"> निर्णय  </th>
                <th style="background-color: #9eda74;"> निर्णय प्रमाणित गर्ने </th>
                <th style="background-color: #9eda74;"> पद </th>
                <th style="background-color: #9eda74;"> निर्णय प्रमाणित मिति </th>
                <th style="background-color: #9eda74;"> कैफियत </th>

            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $key => $report)
                @php
                    $dateexplode = convert_bs_to_word($report->minister_approval_date_bs);
                    $day_word = isset($dateexplode['day_in_word'])?$dateexplode['day_in_word']:'-';
                    $month = $dateexplode['month_in_word']??'-';
                    $day = $dateexplode['day']??'-';
                    $background =  $key % 2 == 0 ? 'background-color: #f2f2f2;' : 'background-color: #ffffff';
                @endphp
                <tr >
                    <td style="{{ $background }}">{{ $key + 1 }}</td>
                    <td style="{{ $background }}">{{ $report->agenda_number??'-' }}</td>
                    <td style="{{ $background }}">{{ $report->meeting_code??'-' }}</td>
                    <td style="{{ $background }}">{{ $report->fiscal_year??'-' }}</td>
                    <td style="{{ $background }}">{{ $report->year??'-' }}</td>
                    <td style="{{ $background }}">{{ $month }}</td>
                    <td style="{{ $background }}">{{ $day }}</td>
                    <td style="{{ $background }}">{{ $report->minister_approval_date_bs }}</td>
                    <td style="{{ $background }}">{{ $day_word }}</td>
                    <td style="{{ $background }}">{{ $report->ministry }}</td>
                    <td style="{{ $background }}">{{ $report->agenda_type }}</td>
                    <td style="{{ $background }}">{{ $report->agenda_title }}</td>
                    <td style="{{ $background }}">{{ $report->meeting_code??'-' }}</td>
                    <td style="{{ $background }}">{{ $report->submitted_date_time }}</td>
                    <td style="{{ $background }}">{{ $report->agenda_description }}</td>
                    <td style="{{ $background }}">{{ $report->mp_name }}</td>
                    <td style="{{ $background }}">{{ $report->post_name }}</td>
                    <td style="{{ $background }}">{{ $report->minister_approval_date_bs }}</td>
                    <td style="{{ $background }}"></td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
