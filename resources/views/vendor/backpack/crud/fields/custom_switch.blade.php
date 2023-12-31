<!-- toggle field -->
<link href="{{ URL::asset('css/toggle.css') }}" rel="stylesheet" type="text/css" >
<div @include('crud::inc.field_wrapper_attributes') >
    @include('crud::inc.field_translatable_icon')
    <div class="checkbox">
        <label class="switch">

            <input type="hidden" name="{{ $field['name'] }}" value="0">
            <input type="checkbox" value="1"

                   name="{{ $field['name'] }}"

                   @if (old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? false)
                   checked="checked"
            @endif

            @if (isset($field['attributes']))
                @foreach ($field['attributes'] as $attribute => $value)
                    {{ $attribute }}="{{ $value }}"
                @endforeach
            @endif
            >
            <span class="slider round"></span>
        </label><span>{!! $field['label'] !!}</span>

        {{-- HINT --}}
        @if (isset($field['hint']))
            <p class="help-block">{!! $field['hint'] !!}</p>
        @endif
    </div>
</div>