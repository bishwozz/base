
<small>
  <a href="javascript:;" class="btn  btn-info show-btn " data-toggle="modal" data-target="#showAgendaModal-{{ $entry->getKey() }}" title="फाइल हेर्नुहोस">
    <i class="la la-file-o">फाइल</i>
  </a>
</small>
@php
$agenda_detail = \App\Models\Agenda::FindOrFail($entry->getKey());
$existing_files = DB::table('multiple_agenda_files')->where('agenda_id', $entry->getKey())->get();
// $this->data['existing_files'] = $files;
@endphp
<style>
    .table_agendas_show {
      width: 100%;
      border-collapse: collapse;
	  border: 2px solid black;
    }
    
    .table_agendas_show_th, .table_agendas_show_td {
      border: 1px solid black;
      padding: 8px;
      text-align: center;
	  border: 1px solid black;

    }
    
    .table_agendas_show_thh {
      background-color: #f2f2f2;
    }

	/* CSS styles for the list */
    .file-list {
      list-style: decimal; /* Use decimal numbers for ordered list */
      padding-left: 20px; /* Indentation for list items */
    }

    .file-row {
      margin-bottom: 10px; /* Spacing between each file row */
    }

    .file-preview {
      display: inline-block;
      border: 1px solid #ccc;
      padding: 5px 10px;
      border-radius: 5px;
    }
  </style>

<div class="modal fade bd-example-modal-lg" id="showAgendaModal-{{ $entry->getKey() }}" tabindex="-1" role="dialog"
	aria-labelledby="showAgendaModalTitle" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header text-center">
       			<h5>प्रस्तावमा समाबेश फाइलहरु  :</h5>
			</div>
          <div class="col-md-12 mt-4">
				<strong> </strong>
				@if(isset($existing_files))
				<ol class="file-list">
					@foreach($existing_files as $file)
					  <li class="file-row">
						<div class="file-preview">
						  <a target="_blank" style="text-decoration: none;" href="{{ asset(\Storage::disk('uploads')->url($file->path)) }}">{{ $file->name }}</a>
						</div>
					  </li>
					@endforeach
				  </ol>
				@else
					-----******----- No Files -----******-----
        		@endif
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>



<script>
	$('.modal').appendTo('body');
</script>