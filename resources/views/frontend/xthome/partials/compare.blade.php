<a href="{{ route('compare') }}" class="d-flex align-items-center" data-toggle="tooltip" data-title="{{ translate('Compare') }}" data-placement="top">
      <i class="fa-sharp fa-solid fa-code-compare"></i><div id="compare_items_sidenav">@if(Session::has('compare'))<span id="compare_items_count">{{ count(Session::get('compare'))}}</span>@endif</div>
</a>
