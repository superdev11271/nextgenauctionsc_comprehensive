@extends('seller.layouts.app')

@php
    $packageInvalidAt = \Carbon\Carbon::parse(Auth::user()->shop?->package_invalid_at);
    $now = \Carbon\Carbon::now();
    $isExpired = $packageInvalidAt < $now;
    $daysRemaining = $now->diffInDays($packageInvalidAt, false);  // false ensures negative values if expired
@endphp

@section('panel_content')

    <div class="aiz-titlebar mt-2 mb-4">
      <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Products') }}</h1>
        </div>
      </div>
    </div>

    <div class="row gutters-10 justify-content-center">
        @if (addon_is_activated('seller_subscription'))
            <div class="col-md-4 mx-auto mb-3" >
                <div class="bg-grad-3 text-white rounded-lg overflow-hidden">
                  <span class="size-30px rounded-circle mx-auto bg-soft-primary d-flex align-items-center justify-content-center mt-3">
                      <i class="las la-upload la-2x text-white"></i>
                  </span>
                  <div class="px-3 pt-3 pb-3">
                      <div class="h4 fw-700 text-center">{{ max(0, auth()->user()->shop?->product_upload_limit - auth()->user()->shop?->created_product_count) }}</div>
                      <div class="opacity-50 text-center">{{  translate('Remaining Uploads') }}</div>
                        @if ($isExpired)
                            <div style="display: flex; justify-content: center; align-items: center;">
                                <span class="border border-secondry text-secondry p-1">{{__('Package Expired')}}</span>
                            </div>
                        @elseif($daysRemaining == 0)
                            <div style="display: flex; justify-content: center; align-items: center;">
                                <span class="border border-secondry text-secondry p-1">{{__('Expire Today')}}</span>
                            </div>
                        @else
                        <div style="display: flex; justify-content: center; align-items: center;">
                            <span class="border border-secondry text-secondry p-1">{{ $daysRemaining }} {{__('Days remaining')}}</span>
                        </div>
                        @endif
                  </div>
                </div>
            </div>
        @endif

        <div class="col-md-4 mx-auto mb-3" >
            <a href="{{ route('seller.products.create')}}">
              <div class="p-3 rounded mb-3 c-pointer text-center bg-white shadow-sm hov-shadow-lg has-transition">
                  <span class="size-60px rounded-circle mx-auto bg-secondary d-flex align-items-center justify-content-center mb-3">
                      <i class="las la-plus la-3x text-white"></i>
                  </span>
                  <div class="fs-18 text-primary">{{ translate('Add New Product') }}</div>
              </div>
            </a>
        </div>

        @if (addon_is_activated('seller_subscription'))
        @php
            $seller_package = \App\Models\SellerPackage::find(Auth::user()->shop?->seller_package_id);

        @endphp
        <div class="col-md-4">
            <a href="{{ route('seller.seller_packages_list') }}" class="text-center bg-white shadow-sm hov-shadow-lg text-center d-block p-3 rounded">
                @if($seller_package != null)
                    <img src="{{ uploaded_asset($seller_package->logo) }}" height="44" class="mw-100 mx-auto">
                    <span class="d-block sub-title mb-2">{{ translate('Current Package')}}: {{ $seller_package->getTranslation('name') }}</span>
                @else
                    <i class="la la-frown-o mb-2 la-3x"></i>
                    <div class="d-block sub-title mb-2">{{ translate('No Package Found')}}</div>
                @endif
                @if($seller_package == null)
                    <div class="btn btn-outline-primary py-1">{{ translate('Buy Package')}}</div>
                @else
                    <div class="btn btn-outline-primary py-1">{{ translate('Upgrade Package')}}</div>
                @endif
            </a>
        </div>
        @endif

    </div>

    <div class="card">
        <form class="" id="sort_products" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-md-0 h6">{{ translate('All Products') }}</h5>
                </div>

                <div class="dropdown mb-2 mb-md-0 mr-2">
                    <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                        {{translate('Bulk Action')}}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item confirm-alert" href="javascript:void(0)"  data-target="#bulk-delete-modal"> {{translate('Delete selection')}}</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group input-group-md">
                        <input type="text" class="form-control" id="search" name="search" @isset($search) value="{{ $search }}" @endisset placeholder="{{ translate('Search product') }}">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>
                                <div class="form-group">
                                    <div class="aiz-checkbox-inline">
                                        <label class="aiz-checkbox">
                                            <input type="checkbox" class="check-all">
                                            <span class="aiz-square-check"></span>
                                        </label>
                                    </div>
                                </div>
                            </th>
                            <th width="30%">{{ translate('Name')}}</th>
                            {{-- <th data-breakpoints="md">{{ translate('Category')}}</th> --}}
                            <th data-breakpoints="md">{{ translate('Current Qty')}}</th>
                            <th>{{ translate('Base Price')}}</th>
                            @if(get_setting('product_approve_by_admin') == 1)
                                <th data-breakpoints="md">{{ translate('Approval')}}</th>
                            @endif
                            <th data-breakpoints="md">{{ translate('Published')}}</th>
                            <th data-breakpoints="md">{{ translate('Featured')}}</th>
                            @if(get_setting('marketplace_product_expiry'))
                            <th data-breakpoints="md">{{ translate('Will Expired')}}</th>
                            @endif
                            <th data-breakpoints="md" class="text-right">{{ translate('Options')}}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($products as $key => $product)
                            <tr>
                                <td>
                                    <div class="form-group d-inline-block">
                                        <label class="aiz-checkbox">
                                            <input type="checkbox" class="check-one" name="id[]" value="{{encrypt($product->id)}}">
                                            <span class="aiz-square-check"></span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    {{-- route('product', $product->slug) --}}

                                    <div class="row gutters-5 w-200px w-md-300px mw-100">
                                        <div class="col-auto">
                                            <img src="{{ uploaded_asset($product->thumbnail_img) }}" alt="Image"
                                                class="size-50px img-fit">
                                        </div>
                                        <div class="col">
                                            <span class="text-muted text-truncate-2">
                                                <a href="#"  class="text-reset">
                                                    {{ $product->getTranslation('name') }}
                                                </a>
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                {{-- <td>
                                    @if ($product->main_category != null)
                                        {{ $product->main_category->getTranslation('name') }}
                                    @endif
                                </td> --}}
                                <td>
                                    @php
                                        $qty = 0;
                                        foreach ($product->stocks as $key => $stock) {
                                            $qty += $stock->qty;
                                        }
                                        echo $qty;
                                    @endphp
                                </td>
                                <td>{{ $product->unit_price }}</td>
                                @if(get_setting('product_approve_by_admin') == 1)
                                    <td>
                                        @if ($product->approved == 1)
                                            <span class="badge badge-inline badge-success">{{ translate('Approved')}}</span>
                                        @else
                                            <span class="badge badge-inline badge-info">{{ translate('Pending')}}</span>
                                        @endif
                                    </td>
                                @endif
                                <td>
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input onchange="update_published(this)" value="{{ $product->id }}" type="checkbox" <?php if($product->published == 1) echo "checked";?> >
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input onchange="update_featured(this)" value="{{ encrypt($product->id) }}" type="checkbox" <?php if($product->seller_featured == 1) echo "checked";?> >
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                @if(get_setting('marketplace_product_expiry'))
                                <td>
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        @if($product->auction_end_date)
                                        <span class="text-danger">{{ 
                                            \Carbon\Carbon::parse($product->auction_end_date)->format('F j, Y g:i A')
                                        }}</span>
                                        @else
                                        no expiry
                                        @endif
                                    </label>
                                </td>
                                @endif
                                <td class="text-right">
                                <a class="btn btn-soft-info btn-icon btn-circle btn-sm" href="{{route('seller.products.edit', ['id'=> encrypt($product->id), 'lang'=>env('DEFAULT_LANGUAGE')])}}" title="{{ translate('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>
                                <a href="{{route('seller.products.duplicate', encrypt($product->id)) }}" @if(addon_is_activated('seller_subscription')) onclick="duplicateProduct(event, '{{route('seller.products.duplicate', encrypt($product->id))}}')" @endif class="btn btn-soft-success btn-icon btn-circle btn-sm" title="{{ translate('Relist') }}">
                                    <i class="las la-copy"></i>
                                </a>
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('seller.products.destroy', encrypt($product->id))}}" title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination">
                    {{ $products->links() }}
                </div>
            </div>
        </form>
    </div>

@endsection

@section('modal')
    <!-- Delete modal -->
    @include('modals.delete_modal')
    <!-- Bulk Delete modal -->
    @include('modals.bulk_delete_modal')
@endsection

@section('script')

    <script type="text/javascript">
        $(document).on("change", ".check-all", function() {
            if(this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function() {
                    this.checked = false;
                });
            }

        });

        function update_featured(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('seller.products.featured') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Featured products updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                    location.reload();
                }
            });
        }

        function update_published(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('seller.products.published') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Published products updated successfully') }}');
                }
                else if(data == 2){
                    AIZ.plugins.notify('danger', '{{ translate('Please upgrade your package.') }}');
                    location.reload();
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                    location.reload();
                }
            });
        }

        function bulk_delete() {
            var data = new FormData($('#sort_products')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('seller.products.bulk-delete')}}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if(response == 1) {
                        location.reload();
                    }
                }
            });
        }

        function duplicateProduct(event, url) {
            event.preventDefault();
            Swal.fire({
                title: "{{__('Are you sure')}}?",
                html: '<p class="text-danger">{{ __("It will be deducted from your remaining upload quota.") }}</p>',
                icon: "info",
                showCancelButton: true,
                confirmButtonColor: "rgb(42, 173, 89)",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, duplicate it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url; // Redirect to the duplication URL
                }
            });
        }

    </script>
@endsection
