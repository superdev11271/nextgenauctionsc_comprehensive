<!-- news-section -->

@if(count($recent_blogs) > 0)
<section class="news-section">
            <div class="auto-container">
                <div class="sec-title">
                    <h2>News</h2>
                    {{-- <p>Excepteur sint occaecat cupidatat non proident sunt</p> --}}
                    <span class="separator" style="background-image: url({{ static_asset('xt-assets')}}/images/icons/separator-1.png);"></span>
                </div>
                <div class="row clearfix">
                    @foreach($recent_blogs as $blog)
                        <div class="col-lg-4 col-md-6 col-sm-12 news-block">
                            <div class="news-block-one wow fadeInUp animated animated" data-wow-delay="00ms" data-wow-duration="1500ms">
                                <div class="inner-box">
                                    <figure class="image-box">
                                        <a href={{ url("news").'/'. $blog->slug }}>
                                       <img src="{{ uploaded_asset($blog->banner, 'thumbnail') }}" alt="{{ $blog->title }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';" >

                                    </a>
                                    </figure>
                                    <div class="lower-content">
                                        <span class="post-date">{{ date('M d, Y',strtotime($blog->created_at)) }}</span>

                                        <h3><a href="{{ route("blog.details",$blog->slug) }}" class="line-clamp2">{{ $blog->title }}</a></h3>

                                        @if($blog->category != null)
                                            <ul class="post-info clearfix">
                                                <li><a href="javascript:void(0)">{{ $blog->category->category_name }} {{ $blog->banner }}</a></li>
                                            </ul>
                                        @endif

                                        <p class="line-clamp">{!! \Illuminate\Support\Str::limit($blog->short_description, 150, $end='...') !!}</p>
                                        <div class="link"><a href="{{ url("news").'/'. $blog->slug }}">{{ translate('Read more') }}<i class="fa-solid fa-arrow-right"></i></a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="more-btn centred"><a href="{{ route('blog') }}" class="theme-btn-one">View All<i
                class="fa-solid fa-arrow-right"></i></a></div>
        </section>
@endif
        <!-- news-section end -->
