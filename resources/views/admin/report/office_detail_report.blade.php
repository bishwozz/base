<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
                margin-top: 10px;
                margin-left: 40px;
                margin-right: 40px;
            }
        }

        #table td,
        #table th {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            font-weight: bold;
            font-size: 14px;
        }

        .report-name {
            padding: 0 !important;
            margin-bottom: -1em;
        }

        h5 {
            font-weight: normal;
            font-family: Kalimati;
            font-size: 17px;
            text-decoration: underline;
        }

        .ministry_name {
            font-size: 14px;
            font-weight: bold;
            background-color: rgb(238, 236, 236);
            color: blue;
        }

        .total {
            font-size: 12px;
            font-weight: bold;
            background-color: rgba(0, 0, 0, 0.235);
            color: red;
        }

        .text-left {
            text-align: left !important;
        }
        .text-justify{
            text-align: justify !important;
        }
    </style>


</head>

<body>
    @php
    $nepalicurrency = new \App\Base\Helpers\NepaliNumber;

    @endphp
    @if (!isset(request()->is_excel))
    @include('admin.report.report_header')
    <div class="report-name">
        <h5> {{ $report_name }}</h5>
    </div>
    @else
    @endif
    <table id="table">
        <thead>
            <tr>
                @foreach ($columns as $key => $column)
                    @if (isset($columns2))
                    <th class="report-heading {{array_key_exists('class',$column) ? $column['class'] : '' }}" 
                    @foreach($column as $colKey=>$colValue)
                        {{ $colKey }} = "{{ $colValue }}"
                    @endforeach>
                        {{ $key }}
                    </th>
                    @else
                    @if(is_array($column))
                        @foreach($column as  $c)
                            <th class="report-heading"> {{ $key }}</th>
                        @endforeach
                    @else
                        <th class="report-heading "> {{ $column }}
                    @endif        
                    </th>
                    @endif
                @endforeach
            </tr>
            @if (isset($columns2))
            <tr>
                @foreach ($columns2 as $key => $column)
                <th class="report-heading">{{ $column }}</th>
                @endforeach
            </tr>
            @endif
        </thead>
        <tbody>
            @php
            if (isset($columns2)) {
            $count = count(array_merge($columns, $columns2)) - $columns_count;
            } else {
            $count = count($columns);
            }
            @endphp
            @if (isset($report_type) && $report_type === 'milestone')

                    @foreach ($output as $ministry_name => $data)
                        <tr>
                            <td colspan="{{ count($columns) + 1 }}" class="ministry_name">{{ $ministry_name }}</td>
                        </tr>
                        @foreach ($data as $project_name => $item)
                            <tr>
                                <td rowspan="{{ count($item['milestone']) }}" class="report-data">{{ $loop->iteration }}</td>
                                <td rowspan="{{ count($item['milestone']) }}" class="report-data">{{ $item['project']['fy'] }}</td>
                                <td rowspan="{{ count($item['milestone']) }}" class="report-data text-left">{{ $project_name }}</td>
                                <td rowspan="{{ count($item['milestone']) }}" class="report-data">{{ $item['project']['budget'] }}</td>

                                @foreach ($item['milestone'] as $key => $milestone)
                                    @if ($key > 0)
                                    <tr>
                                    @endif
                                        @foreach ($milestone as $d)
                                        <td class="report-data text-left">{{ $d }}</td>
                                        @endforeach
                                    @if ($key > 0)
                                    </tr>
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                    @endforeach
            @elseif (isset($report_type) && $report_type === "progress")
                @foreach ($output as $ministry_name => $data)

                    @php
                    $total_progress_financial = 0;
                    $total_progress_physical = 0;
                    $total_beruju_farchyat_percent = 0;
                    foreach ($data as $item) {
                        if (isset($item['वित्तीय खर्च जम्मा (%)'])) {
                        $total_progress_financial += $item['वित्तीय खर्च जम्मा (%)'];
                        }
                        if (isset($item['भौतिक प्रगति जम्मा (%)'])) {
                        $total_progress_physical += $item['भौतिक प्रगति जम्मा (%)'];
                        }
                        if (isset($item['बेरुजु फछर्यौटको प्रगति (%)'])) {
                        $total_beruju_farchyat_percent += $item['बेरुजु फछर्यौटको प्रगति (%)'];
                        }
                    }

                    @endphp
                    <td colspan={{ $count }} class="ministry_name">{{ $ministry_name }}</td>
                        @foreach ($data as $item)
                            <tr>
                                @foreach ($item as $key=>$d)
                                <td
                                    class="report-data {{ array_key_exists($key,$columns) && array_key_exists('class',$columns[$key]) ? $columns[$key]['class'] : '' }}">
                                    {!! $d !!}</td>
                                @endforeach
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" class="total">जम्मा</td>
                            <td colspan="1" class="total">{{ $total_progress_financial }}</td>
                            <td colspan="1" class="total">{{ $total_progress_physical }}</td>
                            <td colspan="1" class="total">{{ $total_beruju_farchyat_percent }}</td>
                        </tr>
                    @endforeach
            @elseif (isset($report_type) && $report_type === "ministry-budget")
                @foreach ($output as $ministry_name => $data)
                    <td colspan={{ $count }} class="ministry_name">{{ $ministry_name }}</td>
                    @foreach ($data as $item)
                            <tr>
                                @foreach ($item as $key=>$d)
                                <td
                                    class="report-data {{ array_key_exists($key,$columns) && array_key_exists('class',$columns[$key]) ? $columns[$key]['class'] : '' }}">
                                    {!! $d !!}</td>
                                @endforeach
                            </tr>
                        @endforeach

                        <tr>
                            <td colspan="2" class="total">कुल खर्च</td>
                                @foreach ($output_total[$ministry_name] as $key_total=>$item)
                                    <td colspan="1" class="total">
                                        {{ $nepalicurrency->currencyFormat($item) }}
                                    </td>
                                @endforeach
                        </tr>
                @endforeach
            @else
                @foreach ($output as $ministry_name => $data)
                        <td colspan={{ $count }} class="ministry_name">{{ $ministry_name }}</td>
                        @foreach ($data as $item)
                            <tr>
                                @foreach ($item as $key=>$d)
                                <td
                                    class="report-data {{ array_key_exists($key,$columns) && array_key_exists('class',$columns[$key]) ? $columns[$key]['class'] : '' }}">
                                    {!! $d !!}</td>
                                @endforeach
                            </tr>
                        @endforeach
                @endforeach
            @endif    
        </tbody>
    </table>

</body>

</html>