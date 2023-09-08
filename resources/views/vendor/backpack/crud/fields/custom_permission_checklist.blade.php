<!-- checklist -->
@php
  $model = new $field['model'];
  $key_attribute = $model->getKeyName();
  $identifiable_attribute = $field['attribute'];

  // calculate the checklist options
  if (!isset($field['options'])) {
      $field['options'] = $field['model']::all()->pluck($identifiable_attribute, $key_attribute)->toArray();
  } else {
      $field['options'] = call_user_func($field['options'], $field['model']::query());
  }

  // calculate the value of the hidden input
  $field['value'] = old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '';
  if ($field['value'] instanceof \Illuminate\Support\Collection) {
    $field['value'] = $field['value']->pluck($key_attribute)->toJson();
  }

  // define the init-function on the wrapper
  $field['wrapper']['data-init-function'] =  $field['wrapper']['data-init-function'] ?? 'bpFieldInitChecklist';


  $permission_collection= [];
  foreach($field['options'] as $key=>$name){
    $entity_arr = explode(' ',$name);
    $permission_collection[end($entity_arr)][$key] = $entity_arr[0];
  }
@endphp
@include('crud::fields.inc.wrapper_start')
<hr/>
    <label>{!! $field['label'] !!}</label>
    @include('crud::fields.inc.translatable_icon')
    <input type="hidden" value="{{$field['value']}}" name="{{ $field['name'] }}">

    @foreach($permission_collection as $key=>$collection)
      <div class="row">
        <div class="col-md-3"><span>{{$key}}</span></div>
          <div class="col-sm-1" style="cursor: pointer !important;">
            <div class="checkbox">
            <label class="font-weight-normal" style="cursor: pointer">
                <input type="checkbox" id="{{$key}}" value onclick="checkall('{{$key}}','{{json_encode($collection)}}')"> All
            </label>
            </div>
          </div>

          @foreach($collection as $key=>$option)
            <div class="col-sm-2" style="cursor: pointer !important;">
                <div class="checkbox">
                <label class="font-weight-normal" style="cursor: pointer">
                    <input type="checkbox" value="{{ $key }}"> {{ $option }}
                </label>
                </div>
            </div>
        @endforeach
    </div>

    @endforeach

    {{-- <div class="row">
        @foreach ($field['options'] as $key => $option)
            <div class="col-sm-4">
                <div class="checkbox">
                  <label class="font-weight-normal">
                    <input type="checkbox" value="{{ $key }}"> {{ $option }}
                  </label>
                </div>
            </div>
        @endforeach
    </div> --}}

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include('crud::fields.inc.wrapper_end')


{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->fieldTypeNotLoaded($field))
    @php
        $crud->markFieldTypeAsLoaded($field);
    @endphp
    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
        <script>
            function bpFieldInitChecklist(element) {
                var hidden_input = element.find('input[type=hidden]');
                var selected_options = JSON.parse(hidden_input.val() || '[]');
                var checkboxes = element.find('input[type=checkbox]');
                var container = element.find('.row');

                // set the default checked/unchecked states on checklist options
                checkboxes.each(function(key, option) {
                  var id = parseInt($(this).val());

                  if (selected_options.includes(id)) {
                    $(this).prop('checked', 'checked');
                  } else {
                    $(this).prop('checked', false);
                  }
                });

                // when a checkbox is clicked
                // set the correct value on the hidden input
                checkboxes.click(function() {
                  var newValue = [];

                  checkboxes.each(function() {
                    if ($(this).is(':checked') && $(this).val() != '') {
                      var id = $(this).val();
                      newValue.push(id);
                    }
                  });

                  hidden_input.val(JSON.stringify(newValue));

                });
            }

            function checkall(key,collection){
              collection = JSON.parse(collection);
              if ($('#'+key).is(':checked')) {
                $.each( collection, function( key ) {
                  $('input[value='+key+']').prop('checked', 'checked');
                });
              }
              else{
                $.each( collection, function( key ) {
                  $('input[value='+key+']').prop('checked', false);
                });
              }
            }
        </script>
    @endpush

@endif
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}