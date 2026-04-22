@php
    $description = $detailedProduct->getTranslation('description');
    $productInfo = json_decode($detailedProduct->choice_options);
    $noTranslations = is_null($description) && is_null($productInfo) ? 'show active' : '';

@endphp


<div class="row">
    <div class="col-lg-12">
        <div class="product-content-area mt-5">


            @php
                $description = trim($detailedProduct->getTranslation('description'));
            @endphp

            <div class="row">
                @if (!empty($description))
                <div class="col-md-6">
                    
                    <div class="product-content-block">
                        <h3>Description</h3>
                        <div class="product-content-content">
                            @php echo $detailedProduct->getTranslation('description'); @endphp
                        </div>
                    </div>
                   
                    @if (!empty(json_decode($detailedProduct->choice_options)))
                        <div class="product-content-block">
                            <h3>Product Info</h3>
                            <div class="product-content-content product-content-table">
                                <table>
                                    <tbody>
                                        @foreach (json_decode($detailedProduct->choice_options) as $choice)
                                            <tr>
                                                <th>{{ get_single_attribute_name(id: $choice->attribute_id) }}:</th>
                                                @foreach ($choice->values as $value)
                                                    <td>{{ $value }}</td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
                @endif
                <div class="col-md-6 ps-ms-5 mt-5 mt-md-0">
                    <div class="product-content-block">
                        @if (count($reviews))
                        <h3>Reviews</h3>
                        @endif
                        <div class="product-content-content product-content-review">
                            <div class="reviews mb-3">
                                @if (!empty($reviews))
                                    @foreach ($reviews as $key => $review)
                                        <div class="d-flex gap-3 align-items-start">
                                            @if ($review->user != null)
                                                <div class="review-author pull-left">
                                                    <img src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                        @if ($review->user->avatar_original != null) data-src="{{ uploaded_asset($review->user->avatar_original) }}"
                                        @else
                                        data-src="{{ static_asset('assets/img/placeholder.jpg') }}" @endif>
                                                </div>
        
                                                <div class="review-content">
                                                    <div class="review-stars">
                                                        <span class="product-rating">
                                                            @for ($i = 0; $i < $review->rating; $i++)
                                                                <i class="fa fa-star "></i>
                                                            @endfor
                                                            @for ($i = 0; $i < 5 - $review->rating; $i++)
                                                                <i class="fa fa-star-o"></i>
                                                            @endfor
                                                        </span>
                                                    </div>
                                                    <p>{{ $review->comment }}</p>
                                                    <cite> - {{ $review->user->name }} -
                                                        {{ date('d-m-Y', strtotime($review->created_at)) }}</cite>
                                                    <!-- Review Images -->
                                                    <div class="spotlight-group d-flex flex-wrap">
                                                        @if ($review->photos != null)
                                                            @foreach (explode(',', $review->photos) as $photo)
                                                                <a class="spotlight mr-2 mr-md-3 mb-2 mb-md-3 size-60px size-md-90px border overflow-hidden has-transition hov-scale-img hov-border-primary"
                                                                    href="{{ uploaded_asset($photo) }}">
                                                                    <img class="img-fit h-100 lazyload has-transition"
                                                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                                        data-src="{{ uploaded_asset($photo) }}"
                                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                                </a>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
        
        
                                        <div class="space-25">&nbsp;</div>
                                    @endforeach
                                    {{ $reviews->appends(request()->input())->links('frontend.xthome.partials.custom_pagination') }}
                                @else
                                    <div class="col-xl-12 col-lg-12 col-md-12 py-3">
                                        <div class="bg-dark mt-5 p-3 rounded" role="alert">
                                            No Data found
                                        </div>
                                    </div>
                                @endif
        
                                @if (Auth::check())
                                    @php
                                        $commentable = false;
                                    @endphp
                                    @foreach ($detailedProduct->orderDetails as $key => $orderDetail)
                                        @if (
                                            $orderDetail->order != null &&
                                                $orderDetail->order->user_id == Auth::user()->id &&
                                                $orderDetail->delivery_status == 'delivered' &&
                                                \App\Models\Review::where('user_id', Auth::user()->id)->where('product_id', $detailedProduct->id)->first() == null)
                                            @php
                                                $commentable = true;
                                            @endphp
                                        @endif
                                    @endforeach
        
                                    <div id="add-review" class="space-top-30">
                                        <form class="form-default" role="form" action="{{ route('reviews.store') }}"
                                            method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $detailedProduct->id }}">
        
                                            <div class="row gx-3">
                                                <div class="col-md-12">
                                                    <h4 class="pb-3">{{ translate('Write a review') }}</h4>
                                                </div>
                                                <div class="col-md-6 col-lg-4 col-xl-5 review-form">
                                                    <input type="text" class="form-control" value="{{ Auth::user()->name }}"
                                                        readonly placeholder="{{ translate('Your name') }}*">
        
                                                </div>
                                                <div class="col-md-6 col-lg-4 col-xl-5 review-form">
                                                    <input type="email" class="form-control"
                                                        value="{{ Auth::user()->email }}" readonly
                                                        placeholder="{{ translate('Email') }}*">
                                                </div>
                                                <div class="col-md-12 col-lg-4 col-xl-2 review-form">
                                                    <select name="rating" class="form-control">
                                                        <option value="5">5 Stars</option>
                                                        <option value="4">4 Stars</option>
                                                        <option value="3">3 Stars</option>
                                                        <option value="2">2 Stars</option>
                                                        <option value="1">1 Star</option>
                                                    </select>
                                                </div>
                                                <div class="col-dm-12 review-form">
        
                                                    <div class="input-group form-control" data-toggle="aizuploader"
                                                        data-type="image">
                                                        <div class="input-group-prepend pt-1">
                                                            <div
                                                                class="input-group-text bg-soft-secondary font-weight-medium rounded-0">
                                                                Browse</div>
                                                        </div>
                                                        <div class="file-amount px-2 pt-2">Choose File</div>
                                                        <input type="hidden" name="photo[]" value=""
                                                            class="selected-files">
                                                    </div>
                                                  
                                                </div>
                                                <div class="col-sm-12 review-form">
                                                    <textarea rows="7" name="comment" class="form-control" required
                                                        oninput="this.value = this.value.replace(/[^\w\s]/g, '').replace(/^\s+/g, '')" placeholder="Review*"></textarea>
                                                    <button type="submit" class="theme-btn-one mt-3">
                                                        {{ translate('Submit review') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
        
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

    </div>
</div>
