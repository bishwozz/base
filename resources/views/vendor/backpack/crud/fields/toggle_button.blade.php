<div @include('crud::inc.field_wrapper_attributes') >
    <span
        name="{{ $field['name'] }}"
        class="{{'accordion'}} w-100 d-flex justify-content-between"
        @include('crud::inc.field_attributes')
    >
        <div>

            {!!  $field['value'] !!}
        </div>
        <span>&#9660;</span>
        
    </span>
    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
<!-- </div> -->
</div>

@if ($crud->checkIfFieldIsFirstOfItsType($field))
  {{-- FIELD EXTRA CSS  --}}
  {{-- push things in the after_styles section --}}

      @push('crud_fields_styles')
          <!-- no styles -->
          <style>
              .accordion {
  /* background-color: #eee; */
  color: ##000000;
  cursor: pointer;
  padding: 10px;
  width: 100%;
  text-align: center;
  border: none;
  outline: none;
  transition: 0.4s;
}

/* Add a background color to the button if it is clicked on (add the .active class with JS), and when you move the mouse over it (hover) */
 .accordion:hover {
  background-color:#468547;
}
        </style>
    @endpush

  {{-- FIELD EXTRA JS --}}
  {{-- push things in the after_scripts section --}}

      @push('crud_fields_scripts')
          <!-- no scripts -->  
          <script>
          $(document).ready(function () {
  var acc = document.getElementsByClassName("accordion");
  var i;

  for (i = 0; i < acc.length; i++) {
      acc[i].addEventListener("click", function () {
          /* Toggle between adding and removing the "active" class,
          to highlight the button that controls the panel */
          this.classList.toggle("active");

          /* Toggle between hiding and showing the active panel */
          var panel = this.nextElementSibling;
          if (panel.style.display === "block") {
              panel.style.display = "none";
          } else {
              panel.style.display = "block";
          }
      });
  }

});

      </script>
      @endpush
@endif
