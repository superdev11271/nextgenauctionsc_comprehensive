@extends('seller.layouts.app')

@section('panel_content')
<div class="aiz-titlebar mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Shop Verification')}}
                {{-- <a href="{{ route('shop.visit', $shop->slug) }}" class="btn btn-link btn-sm" target="_blank">({{ translate('Visit Shop')}})<i class="la la-external-link"></i>)</a> --}}
            </h1>
        </div>
    </div>
</div>
@if(!Auth::user()->shop?->agrement_form)
<div class="alert alert-info" role="alert">
    {{ __("Please fill out the user agreement form and then upload it") }}
</div>
@endif
   @php
    use Illuminate\Support\Str;
    $signaturePath = url('storage/app/public/' . Str::replaceFirst('storage/', '', $shop->signature));
@endphp
<style>
    .signature-canvas {
        width: 100%;
        height: 200px;
        border: 1px solid #ccc;
        touch-action: none;
    }
</style>


<!-- <form action="{{ route('seller.shop.update') }}" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="shop_id" value="{{ auth()->user()->shop?->id }}">
    @csrf
    <div class="row">
        <div class="col-md-2">
            <label>User Agreement Form</label>
        </div>
        <div class="col-md-8">
            <div class="custom-file">
                <label class="custom-file-label">
                    <input type="file" accept=".pdf,.jpg" name="aggrement_form" class="custom-file-input" required>
                    <span class="custom-file-name">{{ translate('Choose file') }}</span>
                </label>
            </div>
            @if(Auth::user()->shop?->agrement_form)
            <p class="card-text m-0 text-danger">{{__('Your Uploaded form')}} <a href="{{ uploaded_asset(auth()->user()->shop?->agrement_form) }}" class="text-primary">View</a></p>
            @else
            <p class="card-text m-0 text-danger">{{__('Please download this form, fill in all necessary fields, and then upload it.')}} <a href="{{ route('client-aggrement') }}" class="text-primary">Download</a></p>
            @endif
        </div>

        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Upload</button>

        </div>
    </div>
</form>
<hr> -->
<form id="signature-form" action="{{ route('seller.shop.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="shop_id" value="{{ auth()->user()->shop?->id }}">

    <div class="card">
        <div class="card-header">
            <h4 class="mb-0 h6">Vendor Agreement Form</h4>
        </div>

        <div class="card-body">

            {{-- Business Info --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Legal Business Name (Client/Vendor)</label>
                    <input type="text" name="business_name" class="form-control" required value="{{ old('business_name', $shop->business_name ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label>Vendor Type</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="vendor_type" value="private" required {{ old('vendor_type', $shop->vendor_type ?? '') == 'private' ? 'checked' : '' }}>
                        <label class="form-check-label">Private Company</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="vendor_type" value="sole_trader" {{ old('vendor_type', $shop->vendor_type ?? '') == 'sole_trader' ? 'checked' : '' }}>
                        <label class="form-check-label">Sole Trader</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="vendor_type" value="partnership" {{ old('vendor_type', $shop->vendor_type ?? '') == 'partnership' ? 'checked' : '' }}>
                        <label class="form-check-label">Partnership</label>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label>ABN</label>
                    <input type="number" name="abn" class="form-control" value="{{ old('abn', $shop->abn ?? '') }}">
                </div>
                <div class="col-md-3">
                    <label>ACN</label>
                    <input type="number" name="acn" class="form-control" value="{{ old('acn', $shop->acn ?? '') }}">
                </div>
                <div class="col-md-3">
                    <label>Registered for GST</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gst_registered" value="yes" {{ old('gst_registered', $shop->gst_registered ?? '') == 'yes' ? 'checked' : '' }}>
                        <label class="form-check-label">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gst_registered" value="no" {{ old('gst_registered', $shop->gst_registered ?? '') == 'no' ? 'checked' : '' }}>
                        <label class="form-check-label">No</label>
                    </div>
                </div>
            </div>

            {{-- Directors --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Company Director 1</label>
                    <input type="text" name="director1_name" class="form-control" value="{{ old('director1_name', $shop->director1_name ?? '') }}">
                </div>
                <div class="col-md-3">
                    <label>Mobile</label>
                    <input type="tel" name="director1_phone" class="form-control" value="{{ old('director1_phone', $shop->director1_phone ?? '') }}">
                </div>
                <div class="col-md-3">
                    <label>Email</label>
                    <input type="email" name="director1_email" class="form-control" value="{{ old('director1_email', $shop->director1_email ?? '') }}">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Company Director 2</label>
                    <input type="text" name="director2_name" class="form-control" value="{{ old('director2_name', $shop->director2_name ?? '') }}">
                </div>
                <div class="col-md-3">
                    <label>Mobile</label>
                    <input type="tel" name="director2_phone" class="form-control" value="{{ old('director2_phone', $shop->director2_phone ?? '') }}">
                </div>
                <div class="col-md-3">
                    <label>Email</label>
                    <input type="email" name="director2_email" class="form-control" value="{{ old('director2_email', $shop->director2_email ?? '') }}">
                </div>
            </div>

            {{-- Addresses --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Business Address</label>
                    <input type="text" name="business_address" class="form-control" value="{{ old('business_address', $shop->business_address ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label>Postal Address</label>
                    <input type="text" name="postal_address" class="form-control" value="{{ old('postal_address', $shop->postal_address ?? '') }}">
                </div>
            </div>
            <div class="mb-3">
                <label>Business Phone</label>
                <input type="tel" name="business_phone" class="form-control" value="{{ old('business_phone', $shop->business_phone ?? '') }}">
            </div>

            {{-- Nominated Contacts --}}
            <div class="row mb-3">
                <div class="col-md-4">
                    <label>Nominated Business Contact 1 Mobile</label>
                    <input type="tel" name="contact1_mobile" class="form-control" value="{{ old('contact1_mobile', $shop->contact1_mobile ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label>Contact 2 Mobile</label>
                    <input type="tel" name="contact2_mobile" class="form-control" value="{{ old('contact2_mobile', $shop->contact2_mobile ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label>Contact 3 Mobile</label>
                    <input type="tel" name="contact3_mobile" class="form-control" value="{{ old('contact3_mobile', $shop->contact3_mobile ?? '') }}">
                </div>
            </div>

            {{-- Auctioneer Details --}}
            <h5 class="mt-4">Auctioneer/Licensee Details (Pre-filled)</h5>
            <div class="mb-2">Agency Name: <strong>NextGen Auctions</strong></div>
            <div class="mb-2">Auctioneer: <strong>Samantha Whitelaw</strong></div>
            <div class="mb-2">License Number: <strong>18134</strong> | Expiry: 15th Jan 2027</div>
            <div class="mb-2">Address: Po Box 1054, Toodyay, WA, 6566</div>
            <div class="mb-2">Phone: 0438 646 367</div>
            <div class="mb-2">Email: admin@nextgenauction.com.au</div>

            {{-- Agreement Clause --}}
            <div class="mb-3 mt-4">
                <label>Vendor Premium/Commission %</label>
                <input type="number" step="any" name="commission" class="form-control" value="{{ old('commission', $shop->commission ?? '') }}">
            </div>

            <div class="mb-3">
                <label>Costs bared by Vendor</label>
                <textarea name="vendor_costs" class="form-control" rows="2">{{ old('vendor_costs', $shop->vendor_costs ?? '') }}</textarea>
            </div>

            <div class="mb-3">
                <label>Basis</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="basis[]" value="ongoing" {{ collect(old('basis', $shop->basis ?? []))->contains('ongoing') ? 'checked' : '' }}>
                    <label class="form-check-label">Ongoing</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="basis[]" value="onetime" {{ collect(old('basis', $shop->basis ?? []))->contains('onetime') ? 'checked' : '' }}>
                    <label class="form-check-label">One-time</label>
                </div>
            </div>

            {{-- Optional Costs --}}
            <h6 class="mt-3">Optional Costs (If Required)</h6>
            <div class="row mb-2">
                <div class="col-md-4">
                    <label>Shipping</label>
                    <input type="number" step="any" name="shipping_cost" class="form-control" value="{{ old('shipping_cost', $shop->shipping_cost ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label>Photographing</label>
                    <input type="number" step="any" name="photo_cost" class="form-control" value="{{ old('photo_cost', $shop->photo_cost ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label>Cataloguing</label>
                    <input type="number" step="any" name="catalogue_cost" class="form-control" value="{{ old('catalogue_cost', $shop->catalogue_cost ?? '') }}">
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4">
                    <label>NextGen Staff On Site</label>
                    <input type="number" step="any" name="staff_cost" class="form-control" value="{{ old('staff_cost', $shop->staff_cost ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label>Travel to Site</label>
                    <input type="number" step="any" name="travel_cost" class="form-control" value="{{ old('travel_cost', $shop->travel_cost ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label>Travel by Air</label>
                    <input type="number" step="any" name="air_travel_cost" class="form-control" value="{{ old('air_travel_cost', $shop->air_travel_cost ?? '') }}">
                </div>
            </div>

            <div class="mb-3">
                <label>Other Costs</label>
                <input type="text" name="other_costs" class="form-control" value="{{ old('other_costs', $shop->other_costs ?? '') }}">
            </div>

            {{-- Acknowledgment --}}
            <h5 class="mt-4">Acknowledgment and Agreement</h5>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Name</label>
                    <input type="text" name="ack_name" class="form-control" value="{{ old('ack_name', $shop->ack_name ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label>Company</label>
                    <input type="text" name="ack_company" class="form-control" value="{{ old('ack_company', $shop->ack_company ?? '') }}">
                </div>
            </div>
            <div class="row mb-3">

                <div class="col-md-12">
                    <label>Date</label>
                    <input type="date" name="signed_date" class="form-control" value="{{ old('signed_date', $shop->signed_date ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label>Signature</label>
                    <br>
                    <canvas id="signature-pad" style="border: 1px solid #ccc; width: 100%; height: 200px;"></canvas>
                    <input type="hidden" name="signature" id="signature-input" value="{{ old('signature', $shop->signature ?? '') }}">
                    <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="clearSignature()">Clear</button>
                </div>

                @if ($shop->signature)
                <div class="col-md-6">
                    <label>Saved Signature</label>
                    <br>
                    <!-- <img src="{{ asset($shop->signature) }}" alt="Signature" class="img-fluid border" style="height: 200px; object-fit: contain;"> -->
                    <img src="{{ $signaturePath }}" alt="Signature" class="img-fluid border" style="height: 200px; object-fit: contain;">

                </div>
                @endif

            </div>



            <div class="text-right mt-4">
                <button type="submit" class="btn btn-primary">Submit Agreement</button>
            </div>

        </div>
    </div>
</form>


<hr>
<form class="" action="{{ route('seller.shop.verify.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0 h6">{{ translate('Verification info')}}</h4>
        </div>
        @php
        $verification_form = get_setting('verification_form');
        $fieldID=1 ;
        @endphp
        <div class="card-body">
            @foreach (json_decode($verification_form) as $key => $element)
            @if ($element->type == 'text')
            <div class="row">
                <div class="col-md-2">
                    <label>{{ $element->label }} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-10">
                    <input type="{{ ($element->label == 'email' || $element->label == 'Email') ? 'email' : $element->type }}" class="form-control mb-3" placeholder="{{ $element->label }}" value="{{old('element_'.$key)}}" name="element_{{ $key }}" @if($element->label == 'email' || $element->label == 'Email') oninput="this.value=this.value.replace(/[^A-Za-z0-9._%+-@]/g,'')" @else oninput="this.value=this.value.replace(/[^A-Za-z0-9\s@]/g,'')" @endif required>
                    @if ($errors->has('element_'.$key))
                    <span class="text-danger">{{ $element->label}} is required</span>
                    @endif
                </div>
            </div>
            @elseif($element->type == 'file')
            <div class="row">
                <div class="col-md-2">
                    <label>{{ $element->label }}<span class="text-danger">*</span></label>
                </div>
                <div class="col-md-10">
                    <div class="custom-file">
                        <label class="custom-file-label">
                            <input type="{{ $element->type }}" name="element_{{ $key }}" id="file-{{ $key }}" accept=".pdf,.jpg,.png" class="custom-file-input" required>
                            <span class="custom-file-name">{{ translate('Choose file') }}</span>
                        </label>
                    </div>
                </div>
            </div>
            @elseif ($element->type == 'select' && is_array(json_decode($element->options)))
            <div class="row">
                <div class="col-md-2">
                    <label>{{ $element->label }}</label>
                </div>
                <div class="col-md-10">
                    <div class="mb-3">
                        <select class="form-control selectpicker" data-minimum-results-for-search="Infinity" name="element_{{ $key }}" required>
                            @foreach (json_decode($element->options) as $value)
                            <option value="{{ $value }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            @elseif ($element->type == 'multi_select' && is_array(json_decode($element->options)))
            <div class="row">
                <div class="col-md-2">
                    <label>{{ $element->label }}</label>
                </div>
                <div class="col-md-10">
                    <div class="mb-3">
                        <select class="form-control selectpicker" data-minimum-results-for-search="Infinity" name="element_{{ $key }}[]" multiple required>
                            @foreach (json_decode($element->options) as $value)
                            <option value="{{ $value }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            @elseif ($element->type == 'radio')
            <div class="row">
                <div class="col-md-2">
                    <label>{{ $element->label }}</label>
                </div>
                <div class="col-md-10">
                    <div class="mb-3">
                        @foreach (json_decode($element->options) as $value)
                        <div class="radio radio-inline">
                            <input type="radio" name="element_{{ $key }}" value="{{ $value }}" id="{{ $value }}" required>
                            <label for="{{ $value }}">{{ $value }}</label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
            @endforeach
            <div class="text-right mt-4">
                @if(Auth::user()->shop?->agrement_form)
                <button type="submit" class="btn btn-primary">{{ translate('Apply')}}</button>
                @else
                <button onclick="show_alert()" class="btn btn-primary">{{ translate('Apply')}}</button>
                @endif
            </div>
        </div>
    </div>
</form>
@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.5/dist/signature_pad.umd.min.js"></script>


<script type="text/javascript">
    function show_alert() {
        Swal.fire({
            title: "{{__('Apply Error!')}}?",
            html: '<p class="text-danger">{{ __("Please fill out the user agreement form and then upload it") }}</p>',
            icon: "warning",
            showCancelButton: true,
        })
    }
</script>

<script>
    const canvas = document.getElementById('signature-pad');
    const signatureInput = document.getElementById('signature-input');
    const form = document.getElementById('signature-form');

    function resizeCanvas() {
        const ratio = window.devicePixelRatio || 1;
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        const ctx = canvas.getContext('2d');
        ctx.scale(ratio, ratio);
    }

    // Resize canvas properly
    window.addEventListener("resize", resizeCanvas);
    resizeCanvas();

    // Init signature pad
    const signaturePad = new SignaturePad(canvas);

    // Load old signature if exists
    if (signatureInput.value) {
        const img = new Image();
        img.onload = () => {
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
        };
        img.src = signatureInput.value;
    }

    // Clear function
    window.clearSignature = function() {
        signaturePad.clear();
        signatureInput.value = '';
    }

    // Capture signature on submit
    form.addEventListener('submit', function(e) {
        if (!signaturePad.isEmpty()) {
            signatureInput.value = signaturePad.toDataURL();
        }
    });
</script>




@endsection
