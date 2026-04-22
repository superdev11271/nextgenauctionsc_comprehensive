@extends('frontend.layouts.xt-app')
@push('css')
    <link href="{{ static_asset('xt-assets') }}/css/account-details.css" rel="stylesheet">
@endpush
@push('css')
    <link href="{{ static_asset('xt-assets') }}/css/checkout.css" rel="stylesheet">
    <link href="{{ static_asset('xt-assets') }}/css/bootstrap-select.css" rel="stylesheet">
@endpush
@section('content')
    <section class="shop-section account-details pt-5">
        <div class="auto-container">
            <div class="row">
                @include('frontend.xthome.partials.xt-customer-sidebar')
                <div class="col-lg-8 col-xxl-9">
                    {{-- @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif --}}
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="pb-3 add_head">{{ translate('Shipping Address') }}</h5>
                                    <div class="border-dark p-2 p-lg-3">
                                        @foreach (isset($adderess['1']) ? $adderess['1'] : [] as $key => $address)
                                            <!-- ***********address start********* -->
                                            <div class="row pb-3 mb-3 border-bottom">
                                                <div class="col-md-8">
                                                    <div class="d-flex pY-3 aiz-megabox-elem border-0 gap-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input update_default_btn"
                                                                type="radio"
                                                                data-addresstype="{{ $address->address_type }}"
                                                                name="shipping_address_id" value="{{ old('shipping_address_id', $address->id) }}"
                                                                @checked($address->set_default == 1)>
                                                            <label class="form-check-label"
                                                                for="{{ $address->id }}"></label>

                                                        </div>

                                                        <!-- Address -->
                                                        <div class="flex-grow-1 pl-3 text-left">
                                                            <div class="row">
                                                                <div class="fs-14 col-12 col-lg-4 fw-700">
                                                                    {{ translate('Address') }}</div>
                                                                <div class="fs-14 fw-500 ml-2 col-12">
                                                                    {{ $address->address }}</div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="fs-14 col-4 col-lg-4">
                                                                    {{ translate('Postal Code') }}</div>
                                                                <div class="fs-14 fw-500 ml-2 col">
                                                                    {{ $address->postal_code }}</div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="fs-14 col-4 col-lg-4">
                                                                    {{ translate('City') }}</div>
                                                                <div class="fs-14 fw-500 ml-2 col">
                                                                    {{ optional($address->city)->name }}
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="fs-14 col-4 col-lg-4">
                                                                    {{ translate('State') }}</div>
                                                                <div class="fs-14 fw-500 ml-2 col">
                                                                    {{ optional($address->state)->name }}
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="fs-14 col-4 col-lg-4">
                                                                    {{ translate('Country') }}</div>
                                                                <div class="fs-14 fw-500 ml-2 col">
                                                                    {{ optional($address->country)->name }}
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="fs-14 col-4 col-lg-4">
                                                                    {{ translate('Phone') }}</div>
                                                                <div class="fs-14 fw-500 ml-2 col">
                                                                    {{ $address->phone }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Edit Address Button -->
                                                <div class="col-md-4 p-3 text-right mt-2 right_icons">
                                                    <div class="d-flex gap-2 justify-content-end">
                                                        <span>
                                                            <a href="javascript:void(0)" class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                                                onclick="edit_address('{{ $address->id }}')">
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                        </span>
                                                        <span>
                                                            <a href="javascript:void(0)"
                                                            data-id="{{$address->id}}"
                                                                class="btn btn-soft-primary btn-icon btn-circle btn-sm deletebtn"

                                                                {{-- href="{{route("address.delete", $address->id)}}" --}}
                                                                title="Delete">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- ***********address end********* -->
                                        @endforeach

                                        <div class="row">
                                            <div class="mt-3">

                                                <div class="border-dark p-3 c-pointer text-center bg-dark has-transition hov-bg-soft-light w-100 h-100 flex-column justify-content-center"
                                                    data-addresstype="billing" data-bs-target="#new-address-modal"
                                                    data-bs-toggle="modal">
                                                    {{-- <button class="btn bg-gray text-white px-1 py-1" type="button" data-toggle="dropdown">
                                                        <i class="la la-ellipsis-v"></i>
                                                    </button> --}}
                                                    <i class="fa-solid fa-plus la-2x mb-0"></i>
                                                    <div class="alpha-7 fw-700">
                                                        {{ translate('Add new address') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                        </div>
                        <div class="col-md-6">
                            <h5 class="pb-3 add_head">{{ translate('Billing Address') }}</h5>
                                    <div class="border-dark p-2 p-lg-3">
                                        @foreach (isset($adderess['2']) ? $adderess['2'] : [] as $key => $address)
                                            <!-- ***********address start********* -->
                                            <div class="row pb-3 mb-3 border-bottom">
                                                <div class="col-md-8">
                                                    <div class="d-flex pY-3 aiz-megabox-elem border-0 gap-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input update_default_btn"
                                                                type="radio"
                                                                data-addresstype="{{ $address->address_type }}"
                                                                name="billing_address_id" value="{{ old('shipping_address_id', $address->id) }}"
                                                                @checked($address->set_default == 1)>
                                                            <label class="form-check-label"
                                                                for="{{ $address->id }}"></label>

                                                        </div>

                                                        <!-- Address -->
                                                        <div class="flex-grow-1 pl-3 text-left">
                                                            <div class="row">
                                                                <div class="fs-14 col-12 col-lg-4 fw-700">
                                                                    {{ translate('Address') }}</div>
                                                                <div class="fs-14 fw-500 ml-2 col-12">
                                                                    {{ $address->address }}</div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="fs-14 col-4 col-lg-4">
                                                                    {{ translate('Postal Code') }}</div>
                                                                <div class="fs-14 fw-500 ml-2 col">
                                                                    {{ $address->postal_code }}</div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="fs-14 col-4 col-lg-4">
                                                                    {{ translate('City') }}</div>
                                                                <div class="fs-14 fw-500 ml-2 col">
                                                                    {{ optional($address->city)->name }}
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="fs-14 col-4 col-lg-4">
                                                                    {{ translate('State') }}</div>
                                                                <div class="fs-14 fw-500 ml-2 col">
                                                                    {{ optional($address->state)->name }}
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="fs-14 col-4 col-lg-4">
                                                                    {{ translate('Country') }}</div>
                                                                <div class="fs-14 fw-500 ml-2 col">
                                                                    {{ optional($address->country)->name }}
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="fs-14 col-4 col-lg-4">
                                                                    {{ translate('Phone') }}</div>
                                                                <div class="fs-14 fw-500 ml-2 col">
                                                                    {{ $address->phone }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Edit Address Button -->
                                                <div class="col-md-4 p-3 text-right mt-2 right_icons">
                                                    <div class="d-flex gap-2 justify-content-end">
                                                        <span>
                                                            <a href="javascript:void(0)" class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                                                onclick="edit_address('{{ $address->id }}')">
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                        </span>

                                                        <span>
                                                            <a href="javascript:void(0)"
                                                            data-id="{{$address->id}}"
                                                                class="btn btn-soft-primary btn-icon btn-circle btn-sm deletebtn"

                                                                {{-- href="{{route("address.delete", $address->id)}}" --}}
                                                                title="Delete">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        <span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- ***********address end********* -->
                                        @endforeach

                                        <div class="row">
                                            <div class="mt-3">
                                                <div class="border-dark p-3 w-100 c-pointer text-center bg-dark has-transition hov-bg-soft-light h-100 flex-column justify-content-center"
                                                    data-addresstype="billing" data-bs-target="#new-address-modal"
                                                    data-bs-toggle="modal">
                                                    <i class="fa-solid fa-plus la-2x mb-0"></i>
                                                    <div class="alpha-7 fw-700">
                                                        {{ translate('Add new address') }}</div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>

  <!-- Modal -->
  <div class="modal fade" id="delete_address" tabindex="-1" aria-labelledby="delete_address_Label" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="delete_address_Label">Delete Address</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Do you really want to delete address parmanentaly.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <form action="" method="POST" id="delete_form">
            @csrf
              <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('modal')
        @include('frontend.' . get_setting('homepage_select') . '.modal.xt_address_modal')
@endsection
@push('js')
    <script>
        $(".update_default_btn").on("click", function(ele) {

            let addresstype = $(this).data("addresstype")
            let address_id = $(this).val()

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('update-default-address') }}",
                type: 'POST',
                data: {
                    addresstype: addresstype,
                    address_id: address_id
                },
                success: function(response) {
                    AIZ.plugins.notify(response.status, response.msg);
                }
            });
        })


        // var modal = new bootstrap.Modal('#delete_address')

        $(".deletebtn").on("click",function(){
            let deleteUrl = '{{route("address.delete", ":id")}}'
            let delete_id = $(this).data("id");
            replacedDeleteUrl = deleteUrl.replace(":id", delete_id)
            $("#delete_form").attr("action", replacedDeleteUrl)
            $("#delete_address").modal('show');
        })

    </script>
@endpush
