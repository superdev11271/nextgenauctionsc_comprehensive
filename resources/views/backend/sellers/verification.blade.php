@extends('backend.layouts.app')

@section('content')

@php
    use Carbon\Carbon;

    $emailVerifiedAt = $shop->user->email_verified_at ? Carbon::parse($shop->user->email_verified_at) : null;
    $phoneVerifiedAt = $shop->user->phone_verified_at ? Carbon::parse($shop->user->phone_verified_at) : null;
@endphp


<div class="card">
  <div class="card-header">
      <h5 class="mb-0 h6">{{ translate('Seller Verification') }}</h5>
      @if ($shop->verification_status != 1 && $shop->verification_info != null)
        <div class="pull-right clearfix">
            <a href="{{ route('sellers.reject', $shop->id) }}" class="btn btn-circle btn-danger d-innline-block">{{translate('Reject')}}</a></li>
            <a href="{{ route('sellers.approve', $shop->id) }}" class="btn btn-circle btn-success d-innline-block">{{translate('Accept')}}</a>
        </div>
      @endif
  </div>
  <div class="card-body row">
      <div class="col-md-5">
          <h6 class="mb-4">{{ translate('User Info') }} @if($shop->verification_status==1) <img  src="{{ static_asset('assets/img/verified1.png') }}" class="float-right"> @endif</h6>
          <p class="text-muted">
              <strong>{{ translate('Name') }} :</strong>
              <span class="ml-2">{{ $shop->user->name }}</span>
          </p>
          <p class="text-muted">
              <strong>{{translate('Email')}}</strong>
              <span class="ml-2"> <i class="las la-user"></i>{{ $shop->user->email }}</span>
          </p>
          <p class="text-muted">
              <strong>{{translate('Phone')}}</strong>
              <span class="ml-2">{{ $shop->user->phone }}</span>
          </p>
          <p class="text-muted">
            <strong>{{translate('Default User Address')}}</strong>
              <hr>
              @foreach ($shop->user?->addresses as $key => $address)
                    @if($address->set_default == 1)
                    <span>{{$key+1}}</span>
                    <span class="ml-2"> <i class="las la-map-marker"></i>{{ $address->address }}</span>

                    <span class="ml-2"><i class="las la-flag"></i> {{ optional($address->country)->name }}</span>
                    <span class="ml-2"><i class="las la-city"></i> {{ optional($address->city)->name }}</span>
                    <span class="ml-2">{{ optional($address->state)->name }}</span>
                    <span class="ml-2"><i class="las la-map-pin"></i> {{ $address->postal_code }}</span>
                    <hr>
                    @endif
                @endforeach
          </p>



          <h6 class="mb-4">{{ translate('Shop Info') }}</h6>
          <p class="text-muted">
              <strong>{{translate('Shop Name')}}</strong>
              <span class="ml-2">{{ $shop->user->shop?->name }}</span>
          </p>
          <p class="text-muted">
              <strong>{{translate('Address')}}</strong>
              <span class="ml-2"> <i class="las la-map-marker"></i> {{ $shop->address }}</span>
          </p>

          <p class="text-muted">
              <strong>{{translate('User Agrement form')}} <i class="las la-book"></i></strong>
              <!-- <a href="{{uploaded_asset($shop->user->shop?->agrement_form)}}" class="ml-2">View Agrement</a> -->
             <a href="{{ route('sellers.show_agreement', $shop->id) }}" class="ml-2" target="_blank">
                    {{ translate('View Agreement') }}
            </a>

          </p>

          <a  onclick="remark()" class="btn btn-primary" title="{{__('Please add remark in-case of missing information')}}">Add/Update Remark</a>
          @if($shop->remark)
          <div class="alert alert-info mt-1" role="alert">
            {{$shop->remark}}
          </div>
          @endif
      </div>
      <div class="col-md-5">

        <h6 class="mb-4">{{ translate('Verification Info') }} </h6>

        @if ($shop->verification_info != null)
          <table class="table table-striped table-bordered" cellspacing="0" width="100%">
              <tbody>
                  @foreach (json_decode($shop->verification_info) as $key => $info)
                      <tr>
                          <th class="text-muted">{{ $info->label }}</th>
                          @if ($info->type == 'text' || $info->type == 'select' || $info->type == 'radio')
                              <td>{{ $info->value }}</td>
                          @elseif ($info->type == 'multi_select')
                              <td>
                                  {{ implode(', ', json_decode($info->value)) }}
                              </td>
                          @elseif ($info->type == 'file')
                              <td>
                                  <a href="{{ my_asset($info->value) }}" target="_blank" class="btn-info">{{translate('Click here')}}</a>
                              </td>
                          @endif
                      </tr>
                  @endforeach
              </tbody>
          </table>
        @endif
        @if ($shop->verification_status != 1 && $shop->verification_info != null)
          <div class="text-center">
              <a href="{{ route('sellers.reject', $shop->id) }}" class="btn btn-sm btn-danger d-innline-block">{{translate('Reject')}}</a></li>
              <a href="{{ route('sellers.approve', $shop->id) }}" class="btn btn-sm btn-success d-innline-block">{{translate('Accept')}}</a>
          </div>
        @endif

      </div>
  </div>
</div>
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0 h6">{{ translate('Complete Seller Information') }}</h5>
    </div>
    <div class="card-body row">
        <div class="col-md-6">
            <h6 class="mb-3 text-primary">{{ translate('Basic Info') }}</h6>
            <p><strong>{{ translate('User ID') }}:</strong> {{ $shop->user->id }}</p>
            <p><strong>{{ translate('Name') }}:</strong> {{ $shop->user->name }}</p>
            <p><strong>{{ translate('Email') }}:</strong> {{ $shop->user->email }}</p>
            <p><strong>{{ translate('Phone') }}:</strong> {{ $shop->user->phone }}</p>
            <p><strong>{{ translate('Account Created At') }}:</strong> {{ \Carbon\Carbon::parse($shop->user->created_at)->format('d M Y h:i A') }}</p>
            <p><strong>{{ translate('Email Verified') }}:</strong>
                @if ($shop->user->email_verified_at)
                    ✅ {{ \Carbon\Carbon::parse($shop->user->email_verified_at)->format('d M Y') }}
                @else
                    ❌ {{ translate('Not Verified') }}
                @endif
            </p>

            <h6 class="mt-4 mb-3 text-primary">{{ translate('Bank Info') }}</h6>
            <p><strong>{{ translate('Bank Name') }}:</strong> {{ $shop->bank_name }}</p>
            <p><strong>{{ translate('Account Name') }}:</strong> {{ $shop->bank_acc_name }}</p>
            <p><strong>{{ translate('Account No') }}:</strong> {{ $shop->bank_acc_no }}</p>
            <p><strong>{{ translate('Routing No') }}:</strong> {{ $shop->bank_routing_no }}</p>
        </div>

        <div class="col-md-6">

            <h6 class="mt-4 mb-3 text-primary">{{ translate('Shop / Business Info') }}</h6>
            <p><strong>{{ translate('Shop Name') }}:</strong> {{ $shop->name }}</p>
            <p><strong>{{ translate('Shop Address') }}:</strong> {{ $shop->address }}</p>
            <p><strong>{{ translate('ABN') }}:</strong> {{ $shop->abn ?? 'N/A' }}</p>
            <p><strong>{{ translate('ACN') }}:</strong> {{ $shop->acn ?? 'N/A' }}</p>
            <p><strong>{{ translate('Business Phone') }}:</strong> {{ $shop->business_phone ?? 'N/A' }}</p>
        </div>
    </div>
</div>


@endsection

@section('script')
 <script type="text/javascript">
   async function remark(){
        const { value: text } = await Swal.fire({
                input: "textarea",
                inputLabel: "Message",
                inputPlaceholder: "Type your message here...",
                inputAttributes: {
                    "aria-label": "Type your message here"
                },
                inputValue: "Missing information, Please fill your proper information!",
                showCancelButton: true
            });
            if (text) {
                $.post('{{ route('seller.remark') }}', {
                        _token: '{{ csrf_token() }}',
                        remark: text,
                        id: '{{ $shop->id }}'
                }, function(data) {
                    location.reload();
                });
            }
    }
</script>
@endsection
