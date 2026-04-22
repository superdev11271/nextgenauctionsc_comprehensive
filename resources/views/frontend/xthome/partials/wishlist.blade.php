
<a href="{{ route('wishlists.auction') }}" class="d-flex align-items-center" data-toggle="tooltip" data-title="{{ translate('My Watchlist') }}" data-placement="top">
    <i class="fa-regular fa-heart"></i> @if(Auth::check() && count(Auth::user()->wishlists)>0)<span>{{ count(Auth::user()->wishlists)}}</span> @endif
</a>
