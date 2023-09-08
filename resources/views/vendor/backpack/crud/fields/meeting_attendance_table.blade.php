
<link rel="stylesheet" type="text/css" href="{{ asset('packages/datatable-editor/css/jquery.dataTables.min.css') }}">
<script type="text/javascript" src="{{ asset('packages/datatables.net/js/jquery.dataTables.min.js') }}"></script>

<div class="form-group col-md-12">
<div @include('crud::inc.field_wrapper_attributes') >
    <label class="table-title">{!! $field['label'] !!}</label>
    <div class="card col-md-12">
            <table id="meeting_attendance_table" class="display responsive" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>क्र.सं.</th>
                    <th>नाम,थर</th>
                    <th>पद</th>
                    <th>उपस्थिति</th>
                    <th>कार्य विभाजन</th>
                </tr>
            </thead>
            <tbody>
                @foreach($att_members as $member)
                <tr>
                    <td>{{convertToNepaliNumber($loop->iteration) }}</td>
                    <td data-member_id="{{$member->id}}">{{ $member->member_name }}</td>
                    <td>{{ $member->post }}</td>
                    <td>
                        <select class="form-control-sm att_status" name="att_status" id="att_status-{{$member->id}}" data-mid={{$member->id}} style="width: 100%;" onchange="highlightColor()">
                            @if(isset($member->is_present))
                                <option value="0" {{ $member->is_present==false ? 'selected': ''}}>अनुपस्थित</option>
                                <option value="1" {{ $member->is_present==true ? 'selected': ''}}>उपस्थित</option>
                                @else
                                <option value="0">अनुपस्थित</option>
                                <option value="1" selected>उपस्थित</option>
                            @endif

                        </select>

                    </td>
                    <td data-ministry_id="{{$member->ministry_id}}">{{ $member->ministry }}</td>
                </tr>
                @endforeach
            </tbody>
            </table>

    </div>
</div>
</div>

<script type="text/javascript">

$(document).ready(function(){highlightColor()});
    function highlightColor()
    {
       $('.att_status').each(function(index,ele){
        if(ele.value == 1){
            $('#att_status-'+$(ele).data('mid')).removeClass('absent').addClass('present');
        }else{
            $('#att_status-'+$(ele).data('mid')).removeClass('present').addClass('absent');
        }
       });
    }

    $('#meeting_attendance_table').DataTable({
                searching: false,
                paging:false, 
                bInfo: false,
                ordering:false
            });
        
   
</script>
<style>
#meeting_attendance_table{
  margin: 0 auto;
  width: 100%;
  clear: both;
  border-collapse: collapse;
  table-layout: fixed; 
  word-wrap:break-word;
  text-align: left;
}
#meeting_attendance_table td{
    font-size: 14px;
    padding:5px 0px 5px 20px;    
    color:black;
}
#meeting_attendance_table td:nth-child(4){
    color:darkgreen;
}

.table-title{
    color:blue;
    font-weight:bold;
    font-size:16px;
}
.present{
    color:green !important;
    font-size:14px;
}
.absent{
    color:red !important;
    font-size:14px;
}
</style>
