@php 
    $minute_tab = Session::get('minute_tab');
@endphp

@foreach($agendas as $agenda)
<tr>
    <td >{{convertToNepaliNumber($loop->iteration)}}</td>
    <td >{{convertToNepaliNumber($agenda->agenda_number)}}</td>
    <td >{{$agenda->ministry_name}}</td>
    <td >{{$agenda->agenda_title}}</td>
    <td >
        @if($minute_tab == "committee" || backpack_user()->hasRole('committee'))
            <textarea rows="4" style="width: 98% !important;" name="agenda_committee_decision[{{$agenda->agenda_history_id}}]" name="agenda_committee_decision[{{$agenda->agenda_history_id}}]">{{$agenda->decision_of_committee}}</textarea>
        @else
            <textarea rows="4" style="width: 98% !important;" id="agenda_ministry_decision{{$agenda->agenda_history_id}}" onchange="decisionContentChange(this,{{$agenda->agenda_history_id}})" name="agenda_ministry_decision[{{$agenda->agenda_history_id}}]">{{$agenda->decision_of_cabinet}}</textarea>
        @endif
    </td>
    <td style="font-size: 12px; position: relative;">
        <select name="agenda_decision_type_id[{{$agenda->agenda_history_id}}]" id="agenda_decision_type_id-{{$agenda->agenda_history_id}}" onchange="decisionType(this,{{$agenda->agenda_history_id}})"  style="width: 95%;border-radius: 5px; background:white;padding: 8px; z-index: 999;">
            <option value="">-</option>
            @foreach($agenda_decision_type as $decision_type)
                <option value="{{ $decision_type->id }}" {{ $decision_type->id == $agenda->agenda_decision_type_id ? 'selected' : '' }}> {{ $decision_type->agenda_decision_code }}
                </option>
            @endforeach
        </select>
    </td>

    <td >    
        <a class="btn btn-primary" style="font-size: 12px; text-decoration: none;" target="_blank" href="/admin/print-agenda/{{$agenda->id}}/report" data-toggle="tooltip" title="PDF Print">Print</a>
    </td>
    <td >
        @if($agenda->file_upload)
        <a href="{{asset('storage/uploads/'. $agenda->file_upload)}}" id="agenda_file{{$agenda->agenda_history_id}}" data-fancybox data-caption="" ><i class="las la-file-pdf"  style="color:red; font-size:30px "></i></a></br>
        @endif
        <a href="#"  class="btn btn-success btn-sm" onclick="myFunction(this)" class="fancybox" id="btn_choose_file{{$agenda->agenda_history_id}}" data-id="{{$agenda->agenda_history_id}}"  data-fancybox data-src="#uploadModal{{$agenda->agenda_history_id}}">Upload PDF</a>
        <span id="uploadedFileName{{$agenda->agenda_history_id}}"></span>

        <div id="uploadModal{{$agenda->agenda_history_id}}" style="display: none;">
            <h2>PDF अपलोड गर्नुहोस।</h2>
            <form id="uploadForm{{$agenda->agenda_history_id}}" enctype="multipart/form-data">
                <input id="pdf_upload{{$agenda->agenda_history_id}}" required type="file" name="file_upload[{{$agenda->agenda_history_id}}]">
                <button class="btn btn-success" type="button" onclick="savePdf({{$agenda->agenda_history_id}})">Save This PDF</button>
            </form>
        </div>
        

    </td>
    
</tr>
@endforeach


<script>
    $(document).ready(function() {
        $('.fancybox').fancybox({
            afterLoad: function(instance, current) {
            var src = current.opts.$orig.data('src');
            this.src = src;
            }
            
        });
      
    });

    function uploadPdf(event, agendaHistoryId) {
        event.preventDefault();
        $('#uploadForm' + agendaHistoryId).trigger('submit');
    }

    function savePdf(agendaHistoryId) {
        var formData = new FormData($('#uploadForm' + agendaHistoryId)[0]);
        $.ajax({
            url: '{{ route("uploadPdf") }}',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#file-upload-' + agendaHistoryId).addClass('hide-upload'); // Hide the upload button
                $('#pdf-icon-' + agendaHistoryId).removeClass('hide-upload'); // Show the PDF icon
                $('#cross-sign-' + agendaHistoryId).removeClass('hide-upload'); // Show the cross sign
                $.fancybox.close();
                $('#agenda_file' + agendaHistoryId).setAttribute('href', response.file_link);
            },
            error: function(xhr, status, error) {
                // Handle error response
            }
        });
    }
    
    function myFunction(element) {
        var id = $(element).data('id');
        console.log(id); // Output: 123
        // Rest of your code here
        const fileInput = document.getElementById('pdf_upload'+id);
        console.log(fileInput)
        const uploadedFileName = document.getElementById('uploadedFileName'+id);

        fileInput.addEventListener('change', (event) => {
            const fileName = event.target.files[0].name;
            uploadedFileName.textContent = fileName;
        });
    }

    function decisionContentChange(e,agendaId){
        let decision = $('#agenda_ministry_decision' + agendaId).val()
        let decisionId = $('#agenda_decision_type_id-' + agendaId).val()
        saveContent(decision, agendaId, decisionId)
    }

    function decisionType(e,agendaId){
        // for getting decision type id 
        let decisionId = $('#agenda_decision_type_id-' + agendaId).val()
        $.ajax({
            url: '/admin/getDecisionContent/' + decisionId,
            type: 'GET',
            success: function(response){
                $('#agenda_ministry_decision' + agendaId).val(response)
                saveContent(response, agendaId, decisionId)
            }
        })


    }

    // Save decision content
    function saveContent(decision, agendaId, decisionId){
        $.ajax({
            url: '/admin/save-decision-content',
            type: 'POST',
            data: {agendaId: agendaId, decision_of_cabinet: decision, decisionId: decisionId},
            success: function(response){
                if(response.status == 'success'){
                }else{
                    alert(response.message)
                }
            }
        })
    }

    
    // create new record
    function openModal() {
        $.fancybox.open({
            src: '#addInlineAgendaFancyBox',
            type: 'inline',
            opts: {
                closeExisting: true,
                touch: false
            }
        });
    }

    $(document).on('click', '#submitBtn', function() {
        var formData = new FormData($('#createForm')[0]);

        $.ajax({
            url: '/create-record',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Handle the response from the server
                // For example, update the UI with the newly created record
                // $.fancybox.close();
            }
        });
    });
    
    
</script>

