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

<style>
    /* ===============================
   MOBILE CATEGORY MENU
   =============================== */

    .mobile-category-menu {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        background: #111;
        z-index: 1050;

        transform: translateX(-100%);
        transition: transform 0.3s ease-in-out;

        overflow-y: auto;
    }

    .mobile-category-menu.show {
        transform: translateX(0);
    }

    /* close button */
    .mobile-menu-close {
        position: absolute;
        top: 12px;
        right: 15px;
        font-size: 32px;
        background: none;
        border: none;
        color: #fff;
        cursor: pointer;
    }

    /* mobile list */
    .mobile-category-list {
        list-style: none;
        padding: 60px 0 0;
        margin: 0;
    }

    .mobile-category-list li {
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .mobile-category-list li a {
        display: block;
        padding: 14px 16px;
        text-align: center;
        background: #c4161c;
        color: #fff;
        text-decoration: none;
    }

    .mobile-category-list li a:hover,
    .mobile-category-list li a.active {
        background: #a31216;
    }

    /* hide mobile menu on desktop */
    @media (min-width: 992px) {
        .mobile-category-menu {
            display: none !important;
        }
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
            <div class="col-lg-9 category-container d-none d-lg-block">
                <nav class="main-manu">
                    <ul class="category-scroll">
                        <li>
                            <a href="{{ url('shop') }}" class="{{ isActiveMenu('shop') }}">
                                {{ __('All Products') }}
                            </a>
                        </li>

                        @foreach (menubars() as $menubar)
                            <li>
                                <a href="{{ route('category', $menubar->slug) }}"
                                   class="{{ isActiveMenu($menubar->slug) }}">
                                    {{ $menubar->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <span class="more-indicator text-white" id="scrollRight">&raquo;</span>
                </nav>
            </div>

            <!-- MOBILE ONLY -->
            <div class="col-12 d-block d-lg-none">
                <nav class="mobile-category-menu" id="mobileCategoryMenu">

                    <button type="button" class="mobile-menu-close" id="closeMobileMenu">
                        &times;
                    </button>

                    <ul class="mobile-category-list">
                        <li>
                            <a href="{{ url('shop') }}" class="{{ isActiveMenu('shop') }}">
                                {{ __('All Products') }}
                            </a>
                        </li>

                        @foreach (menubars() as $menubar)
                            <li>
                                <a href="{{ route('category', $menubar->slug) }}"
                                   class="{{ isActiveMenu($menubar->slug) }}">
                                    {{ $menubar->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>

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
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const menuBtn = document.querySelector('.menu-btn'); // hamburger
        const manuBar = document.querySelector('.manu-bar');
        const mobileMenu = document.getElementById('mobileCategoryMenu');
        const closeBtn = document.getElementById('closeMobileMenu');

        // OPEN mobile menu
        if (menuBtn) {
            menuBtn.addEventListener('click', function () {
                mobileMenu.classList.add('show');
                document.body.classList.add('overflow-hidden');

                // mark active
                menuBtn.classList.add('active');
                manuBar?.classList.add('active');
            });
        }

        // CLOSE mobile menu
        closeBtn.addEventListener('click', closeMenu);

        function closeMenu() {
            mobileMenu.classList.remove('show');
            document.body.classList.remove('overflow-hidden');

            // 🔥 REMOVE ACTIVE STATES (THIS WAS MISSING)
            menuBtn?.classList.remove('active');
            manuBar?.classList.remove('active');

            // remove active menu links
            document.querySelectorAll('.mobile-category-list a.active')
                .forEach(el => el.classList.remove('active'));
        }

    });
</script>
<!-- Menu Bar End -->
