<li class="dropdown mega_dropdown">
    <a href="{{route($menu_route)}}" class="nav-link">
        {{$title}}
    </a>
    <div class="dropdown-menu category__menu_box">
        <div class="category__menu">
            <ul class="category__menu__list">
                @foreach ($categories as $index => $category)
                <li class="category__menu__item @if($index==0)active @endif">
                    <a href="{{route("$category_route",$category->slug)}}" class="category__menu__link">{{$category->name}}</a>
                    <div class="category__menu__right">
                        <h4 class="d-none d-lg-block">{{$category->name}}</h4>
                        <div class="category__menu__right_boxes">
                            <div class="category__menu__right_box">
                                <ul class="category__menu__right_list">
                                    @foreach ($category->childrenCategories as $subCat)
                                        <li class="category__menu__right_item">
                                            <a href="{{route("$category_route",$subCat->slug)}}" class="category__menu__right_link">{{$subCat->name}}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</li>
