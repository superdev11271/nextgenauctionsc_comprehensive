@extends('backend.layouts.app')

@section('content')


   @php
    use Illuminate\Support\Str;
    $signaturePath = url('storage/app/public/' . Str::replaceFirst('storage/', '', $shop->signature));
@endphp

<form>
    @csrf

    <div class="card">
        <div class="card-header">
            <h4 class="mb-0 h6">Vendor Agreement Form</h4>
        </div>

        <div class="card-body">
            {{-- Business Info --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Legal Business Name (Client/Vendor)</label>
                    <input type="text" class="form-control" readonly value="{{ $shop->business_name }}">
                </div>
                <div class="col-md-6">
                    <label>Vendor Type</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" disabled {{ $shop->vendor_type == 'private' ? 'checked' : '' }}>
                        <label class="form-check-label">Private Company</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" disabled {{ $shop->vendor_type == 'sole_trader' ? 'checked' : '' }}>
                        <label class="form-check-label">Sole Trader</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" disabled {{ $shop->vendor_type == 'partnership' ? 'checked' : '' }}>
                        <label class="form-check-label">Partnership</label>
                    </div>
                </div>
            </div>

            {{-- ABN/ACN/GST --}}
            <div class="row mb-3">
                <div class="col-md-3">
                    <label>ABN</label>
                    <input type="number" class="form-control" readonly value="{{ $shop->abn }}">
                </div>
                <div class="col-md-3">
                    <label>ACN</label>
                    <input type="number" class="form-control" readonly value="{{ $shop->acn }}">
                </div>
                <div class="col-md-3">
                    <label>Registered for GST</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" disabled {{ $shop->gst_registered == 'yes' ? 'checked' : '' }}>
                        <label class="form-check-label">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" disabled {{ $shop->gst_registered == 'no' ? 'checked' : '' }}>
                        <label class="form-check-label">No</label>
                    </div>
                </div>
            </div>

            {{-- Directors --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Company Director 1</label>
                    <input type="text" class="form-control" readonly value="{{ $shop->director1_name }}">
                </div>
                <div class="col-md-3">
                    <label>Mobile</label>
                    <input type="tel" class="form-control" readonly value="{{ $shop->director1_phone }}">
                </div>
                <div class="col-md-3">
                    <label>Email</label>
                    <input type="email" class="form-control" readonly value="{{ $shop->director1_email }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Company Director 2</label>
                    <input type="text" class="form-control" readonly value="{{ $shop->director2_name }}">
                </div>
                <div class="col-md-3">
                    <label>Mobile</label>
                    <input type="tel" class="form-control" readonly value="{{ $shop->director2_phone }}">
                </div>
                <div class="col-md-3">
                    <label>Email</label>
                    <input type="email" class="form-control" readonly value="{{ $shop->director2_email }}">
                </div>
            </div>

            {{-- Addresses --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Business Address</label>
                    <input type="text" class="form-control" readonly value="{{ $shop->business_address }}">
                </div>
                <div class="col-md-6">
                    <label>Postal Address</label>
                    <input type="text" class="form-control" readonly value="{{ $shop->postal_address }}">
                </div>
            </div>

            <div class="mb-3">
                <label>Business Phone</label>
                <input type="tel" class="form-control" readonly value="{{ $shop->business_phone }}">
            </div>

            {{-- Contacts --}}
            <div class="row mb-3">
                <div class="col-md-4">
                    <label>Contact 1 Mobile</label>
                    <input type="tel" class="form-control" readonly value="{{ $shop->contact1_mobile }}">
                </div>
                <div class="col-md-4">
                    <label>Contact 2 Mobile</label>
                    <input type="tel" class="form-control" readonly value="{{ $shop->contact2_mobile }}">
                </div>
                <div class="col-md-4">
                    <label>Contact 3 Mobile</label>
                    <input type="tel" class="form-control" readonly value="{{ $shop->contact3_mobile }}">
                </div>
            </div>

            {{-- Pre-filled Details --}}
            <h5 class="mt-4">Auctioneer/Licensee Details (Pre-filled)</h5>
            <div class="mb-2">Agency Name: <strong>NextGen Auctions</strong></div>
            <div class="mb-2">Auctioneer: <strong>Samantha Whitelaw</strong></div>
            <div class="mb-2">License Number: <strong>18134</strong> | Expiry: 15th Jan 2027</div>
            <div class="mb-2">Address: Po Box 1054, Toodyay, WA, 6566</div>
            <div class="mb-2">Phone: 0438 646 367</div>
            <div class="mb-2">Email: admin@nextgenauction.com.au</div>

            {{-- Commission & Costs --}}
            <div class="mb-3 mt-4">
                <label>Vendor Premium/Commission %</label>
                <input type="number" class="form-control" readonly value="{{ $shop->commission }}">
            </div>

            <div class="mb-3">
                <label>Costs bared by Vendor</label>
                <textarea class="form-control" rows="2" readonly>{{ $shop->vendor_costs }}</textarea>
            </div>

            <div class="mb-3">
                <label>Basis</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" disabled {{ collect($shop->basis)->contains('ongoing') ? 'checked' : '' }}>
                    <label class="form-check-label">Ongoing</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" disabled {{ collect($shop->basis)->contains('onetime') ? 'checked' : '' }}>
                    <label class="form-check-label">One-time</label>
                </div>
            </div>

            {{-- Optional Costs --}}
            <h6 class="mt-3">Optional Costs (If Required)</h6>
            <div class="row mb-2">
                <div class="col-md-4">
                    <label>Shipping</label>
                    <input type="number" class="form-control" readonly value="{{ $shop->shipping_cost }}">
                </div>
                <div class="col-md-4">
                    <label>Photographing</label>
                    <input type="number" class="form-control" readonly value="{{ $shop->photo_cost }}">
                </div>
                <div class="col-md-4">
                    <label>Cataloguing</label>
                    <input type="number" class="form-control" readonly value="{{ $shop->catalogue_cost }}">
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4">
                    <label>NextGen Staff On Site</label>
                    <input type="number" class="form-control" readonly value="{{ $shop->staff_cost }}">
                </div>
                <div class="col-md-4">
                    <label>Travel to Site</label>
                    <input type="number" class="form-control" readonly value="{{ $shop->travel_cost }}">
                </div>
                <div class="col-md-4">
                    <label>Travel by Air</label>
                    <input type="number" class="form-control" readonly value="{{ $shop->air_travel_cost }}">
                </div>
            </div>

            <div class="mb-3">
                <label>Other Costs</label>
                <input type="text" class="form-control" readonly value="{{ $shop->other_costs }}">
            </div>

            {{-- Acknowledgment --}}
            <h5 class="mt-4">Acknowledgment and Agreement</h5>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Name</label>
                    <input type="text" class="form-control" readonly value="{{ $shop->ack_name }}">
                </div>
                <div class="col-md-6">
                    <label>Company</label>
                    <input type="text" class="form-control" readonly value="{{ $shop->ack_company }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label>Date</label>
                    <input type="date" class="form-control" readonly value="{{ $shop->signed_date }}">
                </div>
                <div class="col-md-6">
                    <label>Signature</label><br>
                    <img src="{{ asset($shop->signature) }}" alt="Signature" class="img-fluid border" style="height: 200px; object-fit: contain;">
                    <!-- <img src="{{ $signaturePath }}" alt="Signature" class="img-fluid border" style="height: 200px; object-fit: contain;"> -->

                </div>
            </div>
        </div>
    </div>
</form>


@endsection
