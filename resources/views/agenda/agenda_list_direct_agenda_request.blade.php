@php 
    $minute_tab = Session::get('minute_tab');
@endphp

@foreach($agendas as $agenda)
<tr>
    <td style="text-align: center">{{convertToNepaliNumber($loop->iteration)}}</td>
    <td>{{$agenda->ministry_name}}</td>
    <td style="text-align: center">{{$agenda->agenda_code}}</td>
    <td>{{$agenda->agenda_title}}</td>
</tr>
@endforeach
