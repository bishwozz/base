<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ministry Report</title>
    <style>

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
                margin-top: 10px;
                margin-left: 20px;
                margin-right: 20px;
            }
        }
        .table{
            border-collapse: collapse;
            width: 100%;
        }

        .table td,
        .table th {
            border: 1px solid lightgrey;
            font-weight:normal;
        }

        th {
            font-weight: bold;
        }
        .dialog-content h5{
        background-color: aqua !important;
        }
        .data-row{
            vertical-align: middle;
        }
        .legend{
           position: absolute;
           bottom:0;
        }
        h5{
            font-size: 15px;
            color:darkgreen;
            /* font-weight: normal; */
        }
        .header{
            color:crimson;
            font-size: 18px !important;

        }
        .milestone_name{
            color:blue;
            font-size: 15px !important;
            padding-left: 10px;


        }
        .month_name{
            color:crimson;
        }

    </style>
</head>

<body>
    <div id="dialog-content" >
    <h3>{{$project->project_name}} प्रगति</h3><hr/>

        @if(count($project_chart))
            @foreach($project_chart as $fiscal_year => $charts)
                <h5>आर्थिक बर्ष: {{$fiscal_year}}</h5>
                <table class="table">
                <thead>
                    <tr>
                    <th scope="col" class="header">माइलस्टोन / महिना </th>
                    @foreach($months as $month)
                        <th style="min-width: 50px;" class="month_name">{{$month->name_lc}}</th>
                    @endforeach
                    </tr>
                </thead>
                <tbody>
                    @if(count($charts))
                        @foreach($charts as $milestone => $chart)
                            <tr>
                                <td style="width:300px; !important;" class="milestone_name">{{$milestone}}</td>
                                @foreach($months as $progress_month)
                                <td class="data-row">
                                    <div style="padding:12px 10px; background-color: {{count($chart[$progress_month->name_lc])?$chart[$progress_month->name_lc]['status_colour']:''}};"></div>

                                </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endif
                </tbody>
                </table>
                <div>
                </div>
                <br />
            @endforeach
            <div class="legend">
                <hr>
                @foreach($statuses as $status)
                    <svg width="20" height="10">
                        <rect width="20" height="10" style="fill: {{$status->status_colour}}" />
                    </svg>
                    <span style="font-size: 10px;">: {{$status->name}}</span>&nbsp;&nbsp;
                @endforeach
                
            </div>
        @endif
    </div>
</body>

</html>
