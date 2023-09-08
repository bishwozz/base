<div id="dialog-content" style="min-width:90%;">
    <h3>{{$project->project_name}} प्रगति</h3><hr><a class="btn btn-sm btn-success  p-1  float-right mb-2" href="{{route('printTimelineBar',[$project->id,$project->project_name])}}"><i class="fa fa-print"></i> Timeline Chart</a>
    @if(count($project_chart))
        @foreach($project_chart as $fiscal_year => $charts)
            <h5>आर्थिक बर्ष: {{$fiscal_year}}</h5>
            <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    @foreach($months as $month)
                        <th style="min-width: 70px;" scope="col">{{$month->name_lc}}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @if(count($charts))
                    @foreach($charts as $milestone => $chart)
                        <tr style="border: 1px solid white;">
                            <th scope="row">{{$milestone}}</th>
                            @foreach($months as $progress_month)
                                <td style="vertical-align:middle;">
                                    <div style="padding:15px; background-color: {{count($chart[$progress_month->name_lc])?$chart[$progress_month->name_lc]['status_colour']:''}};"></div>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                @endif
            </tbody>
            </table>
            <div>
            </div>
        @endforeach
        @foreach($statuses as $status)
                <svg width="20" height="10">
                <rect width="20" height="10" style="fill: {{$status->status_colour}}" />
                </svg>&nbsp;
                <span style="font-size: 10px;">: {{$status->name}}</span>&emsp;
        @endforeach
    @endif
</div>