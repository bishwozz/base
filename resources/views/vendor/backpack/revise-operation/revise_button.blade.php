@if ($crud->hasAccess('revise') && count($entry->revisionHistory))
    @if(!backpack_user()->hasRole('minister'))
    <small>
        <a href="{{ url($crud->route.'/'.$entry->getKey().'/revise') }}" class="btn  btn-info show-btn " title="परिवर्तन लग">
        <i class="las la-history"></i>
        </a>
    </small>
    @endif
@endif
