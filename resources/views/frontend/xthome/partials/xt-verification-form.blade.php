@extends('frontend.layouts.xt-app')
@push('css')
<link href="{{static_asset('xt-assets')}}/css/account-details.css" rel="stylesheet">
@endpush

@section('content')

<section class="shop-section account-details pt-5">
   <div class="auto-container">
      <div class="row">
         @include('frontend.xthome.partials.xt-customer-sidebar')
         @php
                $verification_form = get_setting('verification_form');
         @endphp
         <div class="col-lg-8 col-xxl-9">
            <form class="" action="{{ route('seller.shop.verify.store') }}" method="POST" enctype="multipart/form-data">
               @csrf
               <input type="hidden" name="home" value="home">
               <div class="card mb-5">
                  <div class="card-header py-3">
                     <h5 class="m-0">{{ translate('Verification Form')}}</h5>
                  </div>
                  <div class="card-body">
                   <div class="row">
                        @php $fieldID=1; 
                        $fields = json_decode($verification_form);
                        @endphp
                        
                        @foreach (json_decode($verification_form) as $key => $element)
                       
                        @if ($element->type == 'text')
                           <div class="col-sm-6 mb-3">
                              <div class="form-floating">
                                 <input type="{{ ($element->label == 'email' || $element->label == 'Email') ? 'email' : $element->type }}" class="form-control" name="element_{{ $key }}" value="{{ old('element_'.$key) }}"  placeholder="{{ $element->label }}"  @if($element->label == 'email' || $element->label == 'Email') oninput="this.value=this.value.replace(/[^A-Za-z0-9._%+-@]/g,'')"   @else oninput="this.value=this.value.replace(/[^A-Za-z0-9\s@]/g,'')"   @endif  required>
                                 <label for="bankname">{{ $element->label }} 
                                    <span class="text-danger">*</span>
                                 </label>
                              </div>
                              @if ($errors->has('element_'.$key))
                                    <span class="text-danger">{{ $element->label}} is required</span>
                               @endif
                           </div>
                        @elseif($element->type == 'file')
                           <div class="col-sm-6 mb-3">
                              <div class="form-floating">
                                 <input type="{{ $element->type }}" class="form-control" name="element_{{ $key }}"  placeholder="{{ $element->label }}" required>
                              </div>
                           </div>
                        @elseif ($element->type == 'select' && is_array(json_decode($element->options)))
                           <div class="col-sm-6 mb-3">
                              <div class="form-floating">
                                 <select class="form-control selectpicker" data-minimum-results-for-search="Infinity" name="element_{{ $key }}" required>
                                    @foreach (json_decode($element->options) as $value)
                                    <option value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                        @elseif ($element->type == 'multi_select' && is_array(json_decode($element->options)))
                           <div class="col-sm-6 mb-3">
                              <div class="form-floating">
                                 <select class="form-control selectpicker" data-minimum-results-for-search="Infinity" name="element_{{ $key }}[]" multiple required>
                                    @foreach (json_decode($element->options) as $value)
                                    <option value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                                 </select>
                              </div>
                           </div>
                       
                        @elseif ($element->type == 'radio')
                           <div class="col-sm-6 mb-3">
                              <div class="form-floating">
                                 @foreach (json_decode($element->options) as $value)
                                 <div class="radio radio-inline">
                                    <input type="radio" name="element_{{ $key }}" value="{{ $value }}" id="{{ $value }}" required>
                                    <label for="{{ $value }}">{{ $value }}</label>
                                 </div>
                                 @endforeach
                              </div>
                           </div>
                        @endif
                        @endforeach
                        </div>
                        <div class="col-12 pt-2 text-right"><button type="submit" class="theme-btn-one">{{__('Apply')}}</button></div>

                     </div>
               </div>




            </form>
         </div>
      </div>
   </div>
</section>

@endsection