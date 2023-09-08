@php
 $agenda = App\Models\Agenda::find($entry->getKey());
@endphp
@if($agenda->is_approved && !$agenda->agenda_number && backpack_user()->hasRole(Config::get('roles.name.cabinet_creator')))
  <button type="button" class="btn btn-sm btn-primary addAgendaNumber" data-key="{{$agenda->ministry_id}}"   data-id="{{$entry->getKey()}}"  data-toggle="modal" data-target="#agendaNumberModal" data-whatever="@mdo">प्रस्ताब नं. राख्नुहोस</button>

  <div class="modal fade" id="agendaNumberModal"  tabindex="-1" role="dialog" aria-labelledby="agendaNumberModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="agendaNumberModalLabel">प्रस्ताव नं. राख्नुहोस</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" action="{{route('store.agendaNumber')}}">
          <div class="modal-body">
              @csrf
            <div class="form-group">
              <input type="hidden" class="form-control" name="agenda_id" id="agenda_id">
              <label for="agenda_number" class="col-form-label">प्रस्ताव नं.</label>
              <input type="text" class="form-control" readonly name="agenda_number" id="agenda_number" autocomplete="off">
              <span class="text-danger" id="agenda_number_error"></span>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('common.cancel')}}</button>
            <button type="submit"  class="btn btn-success save_agenda_number">{{trans('common.save')}}</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script>
      $(".addAgendaNumber").click(function() {
          $('.modal').appendTo('body');
          $('#agenda_id').val($(this).data('id'));
          var ministry_id = $(this).data('key'); // Retrieve ministry_id from data-key attribute of the button
          $.get('/admin/get-agenda-number',{'ministry_id':ministry_id},
            response=>{
              if(response !== 0) {
                $('#agenda_number').val(response)
              }else{
                $(".save_agenda_number").prop("disabled", true);
              }
          });
      
      });

      $("#agendaNumberModalLabel").click(function() {
          $('#agenda_id').val($(this).data('id'));
      });

          





      // $("#agenda_number").on("keyup", function() {
      //   if($("#agenda_number").val()){
      //     $.get('/admin/check-unique-agenda-number',{'agenda_number':$("#agenda_number").val()},
      //       response=>{
      //         if(response==0) {
      //           $('#agenda_number_error').html('')
      //           $(".save_agenda_number").prop("disabled", false);
      //         }else{
      //           $(".save_agenda_number").prop("disabled", true);
      //           $('#agenda_number_error').html("यो प्रस्ताव नं. पहिलेनै प्रयोग भैसकेको छ!!!")
      //         }
      //     });
      //   }else{
      //     $('#agenda_number_error').html('');
      //   }
      // });
  </script>
  @endif