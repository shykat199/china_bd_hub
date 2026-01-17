<!-- Menu Bar Start -->
<style>
    .category-scroll {
        display: flex;
        gap: 20px;
        overflow-x: auto;
        overflow-y: hidden;
        white-space: nowrap;

        scrollbar-width: none;
        -ms-overflow-style: none;
        cursor: grab;
    }

    .category-scroll::-webkit-scrollbar {
        display: none;
    }

    .category-scroll li {
        flex-shrink: 0;
    }

    /* 🔽 Mobile view */
    @media (max-width: 768px) {
        .category-scroll {
            display: block;        /* stack vertically */
            overflow-x: hidden;
            white-space: normal;
        }

        .category-scroll li {
            margin-bottom: 12px;   /* spacing between items */
        }

        .category-scroll a {
            display: block;        /* full-width tap area */
        }
    }
</style>

<style>
    .category-container {
        position: relative;
    }

    .main-manu {
        position: relative;
        height: 50px;                /* match your red bar height */
        background: #c4161c;         /* your red */
        overflow: hidden;
    }

    .category-scroll {
        display: flex;
        align-items: center;
        height: 100%;
        overflow-x: auto;
        white-space: nowrap;
        scrollbar-width: none;
    }

    .category-scroll::-webkit-scrollbar {
        display: none;
    }

    .category-scroll li a {
        color: #fff;
        padding: 0 16px;
        line-height: 50px;           /* same as menu height */
        display: block;
    }

    .more-indicator {
        position: absolute;
        right: 0;                /* OUTSIDE the bar */
        top: 50%;
        transform: translateY(-50%);
        font-size: 22px;
        font-weight: bold;
        color: #fff !important;
        background: #c4161c !important;
        padding: 0 6px;
        cursor: pointer;

        pointer-events: auto;   /* ✅ allow click */
        z-index: 100;           /* ✅ stay above menu items */
    }

</style>
<div class="manu-bar">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-3 pl-0">
                <div class="dropdown category-manu">
                    <button class="dropdown-toggle" type="button" id="category-manu-btn" data-bs-toggle="dropdown" Area-expanded="false"><span class="icon"><svg viewBox="0 0 385 385"><path d="M371,122.3H14c-7.7,0-14-6.3-14-14v0c0-7.7,6.3-14,14-14H371c7.7,0,14,6.3,14,14v0C385,116,378.7,122.3,371,122.3z"/><path d="M243,206.2H12.6c-6.8,0-12.3-5.5-12.3-12.3v0c0-8.7,7-15.7,15.7-15.7h227c6.8,0,12.3,5.5,12.3,12.3v3.4 C255.3,200.7,249.8,206.2,243,206.2z"/><path d="M141,290.7H14c-7.7,0-14-6.3-14-14v0c0-7.7,6.3-14,14-14h127c7.7,0,14,6.3,14,14v0C155,284.4,148.7,290.7,141,290.7z"/></svg></span>
                        <span class="text">{{ __('All Category') }}</span>
                    </button>
                    <div class="dropdown-menu category-list" Area-labelledby="category-manu-btn">
                        <ul>
                            @foreach(menus() as $menu)
                                <li>
                                    <a class="px-0" href="{{ route('category',$menu->slug??'undefined') }}" >
{{--                                        <img--}}
{{--                                            src="{{ asset('uploads/categories/32x32') }}/{{ $menu->icon }}"--}}
{{--                                            alt="img"--}}
{{--                                            style="--}}
{{--                                            width: 30px;--}}
{{--                                            height: 30px;--}}
{{--                                            border-radius: 50%;--}}
{{--                                            object-fit: cover;--}}
{{--                                            margin-right: 6px;--}}
{{--                                        ">--}}
                                        <span class="text" style="font-size: 15px">{{ $menu->name }}</span>
                                        <span class="arrow"><svg viewBox="0 0 451.8 451.8"><path d="M354.7,225.9c0,8.1-3.1,16.2-9.3,22.4L151.2,442.6c-12.4,12.4-32.4,12.4-44.8,0c-12.4-12.4-12.4-32.4,0-44.7l171.9-171.9 L106.4,54c-12.4-12.4-12.4-32.4,0-44.7c12.4-12.4,32.4-12.4,44.7,0l194.3,194.3C351.6,209.7,354.7,217.8,354.7,225.9z"/></svg></span></a>
                                    <div class="mega-manu">
                                        <div class="row">
                                            @foreach($menu->subCategories as $subMenu)
                                                <div class="col-lg-4">
                                                    <ul>
                                                        @if ($subMenu->subCategories->take(4)->count() > 0)
                                                        <li>
                                                            <a href="{{ route('category', $subMenu->slug ?? 'undefined') }}" style="font-size: 15px">
                                                                <h6 class="title">{{ $subMenu->name }}</h6>
                                                            </a>
                                                        </li>
                                                        @foreach($subMenu->subCategories->take(4) as $subSubMenu)
                                                        <li>
                                                            <a href="{{ route('category',$subSubMenu->slug??'undefined') }}" style="font-size: 15px">{{ $subSubMenu->name }}</a>
                                                        </li>
                                                        @endforeach
                                                        @else
                                                        <li>
                                                            <a href="{{ route('category', $subMenu->slug ?? 'undefined') }}">{{ $subMenu->name }}</a>
                                                        </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 category-container">
                <nav class="main-manu">
                    <button class="close-btn">
                        <span></span>
                        <span></span>
                    </button>
                    <ul class="category-scroll">
                        <li>
                            <a href="{{ url('shop') }}" class="{{ isActiveMenu('shop') }}">{{ __('All Products') }}</a>
                        </li>

                        @foreach (menubars() as $menubar)
                            <li>
                                <a href="{{ route('category', $menubar->slug) }}"
                                   class="{{ isActiveMenu($menubar->slug) }}">{{ $menubar->name }}</a>
                            </li>
                        @endforeach
                    </ul>

                    <span class="more-indicator text-white" id="scrollRight">&raquo;</span>
                </nav>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const scrollBtn = document.getElementById('scrollRight');
        const menu = document.querySelector('.category-scroll');

        scrollBtn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const maxScrollLeft = menu.scrollWidth - menu.clientWidth;

            menu.scrollTo({
                left: maxScrollLeft,
                behavior: 'smooth'
            });
        });
    });
</script>
<!-- Menu Bar End -->
