@extends('frontend.layouts.xt-app')

@push('css')
    <link href="{{ static_asset('xt-assets') }}/css/blog.css" rel="stylesheet">
@endpush

@section('content')
    <!-- account details -->
    <div class="auto-container pt-5">
        <div class="row">
            <div class="col-xl-8 col-lg-8">
                @foreach ($blogs as $blog)
                    <div class="postbox__wrapper pr-20 aos-init aos-animate  border-bottom pb-5" data-aos="fade-up">
                        <div class="row">
                            <div class="col-xl-3 col-sm-12">
                                <div class="postbox__thumb"><a href="{{ url('news') . '/' . $blog->slug }}"><img
                                            src="{{ uploaded_asset($blog->banner) }}" alt="{{ $blog->title }}"
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';" class="news-thumb" /></a>
                                </div>
                            </div>
                            <div class="col-xl-9 col-sm-12">
                                <div class="postbox__content">
                                    <div class="postbox__meta font-weight-normal">
                                        <span><a href="#"><i class="fa-regular fa-clock"></i>{{ date('M d, Y', strtotime($blog->created_at)) }}</a></span>
                                        {{-- @if ($blog->category != null)
                                            <span><a href="javascript:void(0)"><i class="fa-regular fa-eye"></i>{{ $blog->category->category_name }}</a></span>
                                        @endif --}}
                                    </div>
                                    <h3><a href="{{ url('news') . '/' . $blog->slug }}">{{ $blog->title }}</a></h3>
                                    <p>{!! \Illuminate\Support\Str::limit($blog->short_description, 300, $end = '...') !!}</p>
                                    <div class="post__button pt-4"><a class="theme-btn-one" href="{{ url('news') . '/' . $blog->slug }}">{{ translate('Read More') }}</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="aiz-pagination aiz-pagination-center mt-4">
                    {{-- {{ $blogs->appends(request()->input())->links('pagination::bootstrap-5') }}    --}}
                    {{ $blogs->appends(request()->input())->links('frontend.xthome.partials.custom_pagination') }}
                </div>

            </div>



            <div class="col-xl-4 col-lg-4 aos-init aos-animate" data-aos="fade-up">
                <div class="StickySection">
                    <form id="search-form" action="" method="GET">
                        <div class="sidebar__wrapper">
                            <div class="sidebar__widget mb-40">
                                <h3 class="sidebar__widget-title">{{ translate('Search Here') }}</h3>
                                <div class="sidebar__widget-content">
                                    <div class="sidebar__search">
                                        <div class="sidebar__search-input-2">
                                            <input type="text" name="search" value="{{ $search }}"
                                                placeholder="{{ translate('Search...') }}" autocomplete="off">
                                            <button type="submit">
                                                <i class="fa-solid fa-magnifying-glass"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sidebar__wrapper">
                            <div class="sidebar__widget mb-40">
                                <h3 class="sidebar__widget-title">{{ translate('Categories') }}</h3>
                                <div class="sidebar__widget-content">
                                    <ul>
                                        @foreach (get_all_blog_categories() as $category)
                                            <li>
                                                <div class="mb-3 form-check">
                                                    <input type="checkbox" class="form-check-input" name="selected_categories[]"
                                                    value="{{ $category->slug }}"
                                                    @if (in_array($category->slug, $selected_categories)) checked @endif onchange="filter()">
                                                    <label class="form-check-label" for="exampleCheck1">{{ $category->category_name }}</label>
                                                </div>
                                                <!-- <input type="checkbox" name="selected_categories[]"
                                                    value="{{ $category->slug }}"
                                                    @if (in_array($category->slug, $selected_categories)) checked @endif onchange="filter()">
                                                {{ $category->category_name }} -->

                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="sidebar__wrapper">
                        <div class="sidebar__widget mb-40">
                            <h3 class="sidebar__widget-title">{{ translate('Recent News') }}</h3>
                            <div class="sidebar__widget-content">
                                <div class="sidebar__post rc__post">
                                    @foreach ($recent_blogs as $recent_blog)
                                        <div class="rc__post mb-20 d-flex align-items-center">
                                            <div class="rc__post-thumb mr-20"><a
                                                    href="{{ url('news') . '/' . $recent_blog->slug }}"><img
                                                        src="{{ uploaded_asset($recent_blog->banner) }}"
                                                        alt="{{ $recent_blog->title }}"></a></div>
                                            <div class="rc__post-content">
                                                <div class="rc__meta">
                                                    <span>{{ date('M d, Y', strtotime($recent_blog->created_at)) }}</span>
                                                </div>
                                                <h3 class="rc__post-title"><a
                                                        href="{{ url('news') . '/' . $recent_blog->slug }}">{{ $recent_blog->title }}</a>
                                                </h3>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sidebar__wrapper">
                        <div class="sidebar__widget mb-40">
                            <h3 class="sidebar__widget-title">{{ translate('Tags') }}</h3>
                            <div class="sidebar__widget-content">
                                <div class="tagcloud">
                                    @foreach (get_all_blog_categories() as $category)
                                        <a href="javascript:void(0)">{{ $category->category_name }}</a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script type="text/javascript">
    function filter() {
        $('#search-form').submit();
    }
</script>
