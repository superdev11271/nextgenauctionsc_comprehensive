<div class="row">
    <!-- Personal Information Card -->
    <div class="col-lg-6 col-md-12 mb-4">
        <div class="card shadow-sm rounded border-0 h-100">
            <div class="card-header bg-primary text-white rounded-top">
                <i class="las la-user-circle me-2"></i> {{ translate('Personal Information') }}
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th class="w-40">{{ translate('Name') }}</th>
                                <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                            </tr>
                            <tr>
                                <th>{{ translate('Email') }}</th>
                                <td class="text-break">{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>{{ translate('Phone') }}</th>
                                <td>{{ $user->phone }}</td>
                            </tr>
                            <tr>
                                <th>{{ translate('Registration Date') }}</th>
                                <td>{{ date('d-m-Y H:i', strtotime($user->created_at)) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Address Information Card -->
    <div class="col-lg-6 col-md-12 mb-4">
        <div class="card shadow-sm rounded border-0 h-100">
            <div class="card-header bg-success text-white rounded-top">
                <i class="las la-map-marker-alt me-2"></i> {{ translate('Address Information') }}
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th class="w-40">{{ translate('Address') }}</th>
                                <td>
                                    <div class="d-flex flex-column">
                                        @if($user->street_number || $user->street_name)
                                        <span>{{ $user->street_number }} {{ $user->street_name }}</span>
                                        @endif
                                        @if($user->suburb)
                                        <span>{{ $user->suburb }}</span>
                                        @endif
                                        @if($user->city || $user->state || $user->postcode)
                                        <span>
                                            {{ $user->city }}{{ $user->city && ($user->state || $user->postcode) ? ',' : '' }}
                                            {{ $user->state }} {{ $user->postcode }}
                                        </span>
                                        @endif
                                        @if($user->country)
                                        <span>{{ $user->country }}</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@if($user->is_business)
<!-- Business Information Card -->
<div class="row">
    <div class="col-12 mb-4">
        <div class="card shadow-sm rounded border-0">
            <div class="card-header bg-warning text-dark rounded-top">
                <i class="las la-briefcase me-2"></i> {{ translate('Business Information') }}
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th class="w-30">{{ translate('Business Name') }}</th>
                                <td>{{ $user->business_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>{{ translate('ABN/CAN') }}</th>
                                <td>{{ $user->abn_can ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>{{ translate('Business Phone') }}</th>
                                <td>{{ $user->business_phone ?? 'N/A' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<!-- ID Proof Card -->
<div class="col-lg-6 col-md-12 mb-4">
    <div class="card shadow-sm rounded border-0 h-100">
        <div class="card-header bg-info text-white rounded-top">
            <i class="las la-id-card me-2"></i> {{ translate('ID Proof') }}
        </div>
        <div class="card-body">
            <table class="table table-borderless mb-0">
                <tbody>
                    <tr>
                        <th class="w-40">{{ translate('Govt ID Number') }}</th>
                        <td>{{ $user->govt_id ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>{{ translate('Uploaded ID Image') }}</th>
                        <td>
                           @php
    $upload = \App\Models\Upload::find($user->id_photo);
    $file_path = public_path($upload->file_name ?? '');
@endphp

@if ($upload && file_exists($file_path))
    <a href="{{ asset('public/' . $upload->file_name) }}" target="_blank">
        <img src="{{ asset('public/' . $upload->file_name) }}" alt="ID Image" class="img-fluid rounded" style="max-width: 150px;">
    </a>
@else
    <span class="text-muted">No ID image uploaded.</span>
@endif

                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

