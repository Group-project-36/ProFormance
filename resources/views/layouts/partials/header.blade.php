@php
    // Session values
    $currentCurrency = session('currency', 'gbp');
    $currentLocale = session('locale', 'en');

    $currencies = [
        'gbp' => '£ GBP',
        'eur' => '€ EUR',
        'usd' => '$ USD',
    ];

    $locales = [
        'en' => 'English',
        'es' => 'Español',
        'de' => 'Deutsch',
    ];
@endphp

<div class="kb-top-bar">
    <div class="kb-top-bar-inner">

        {{-- LEFT: Currency --}}
        <div class="kb-top-bar-left">
            <form method="POST" action="{{ route('currency.update') }}">
                @csrf
                <div class="kb-dropdown kb-dropdown-left">
                    <button type="button" class="kb-dropdown-toggle">
                        <span class="kb-selected-option">{{ $currencies[$currentCurrency] }}</span>
                        <span class="kb-chevron">▾</span>
                    </button>

                    <div class="kb-dropdown-menu">
                        @foreach ($currencies as $code => $label)
                            <div class="kb-dropdown-item" data-value="{{ $code }}">
                                {{ $label }}
                            </div>
                        @endforeach
                    </div>

                    <input type="hidden" name="currency" value="{{ $currentCurrency }}">
                </div>

            </form>
        </div>

        <div class="kb-top-bar-centre">
            <div class="kb-promo-text">
                <strong>Get up to 40% off</strong> on your 1st order —
                <a href="#shop">Shop now</a>
            </div>
        </div>

        <div class="kb-top-bar-right">
            <form method="POST" action="{{ route('locale.update') }}">
                @csrf
                <div class="kb-dropdown kb-dropdown-right">
                    <button type="button" class="kb-dropdown-toggle">
                        <span class="kb-selected-option">{{ $locales[$currentLocale] }}</span>
                        <span class="kb-chevron">▾</span>
                    </button>

                    <div class="kb-dropdown-menu">
                        @foreach ($locales as $code => $label)
                            <div class="kb-dropdown-item" data-value="{{ $code }}">
                                {{ $label }}
                            </div>
                        @endforeach
                    </div>

                    <input type="hidden" name="locale" value="{{ $currentLocale }}">
                </div>

            </form>
        </div>
    </div>
</div>

<div class="kb-main-header">
    <div class="kb-main-header-inner">
        <div class="kb-mh-left">
           <a href="{{ route('home') }}" class="kb-logo">
                <img
                    src="{{ asset('images/logo.png') }}"
                    alt="Brand logo"
                    class="kb-logo-img"
                >
                <span class="kb-logo-text">ProFormance</span>
            </a>
        </div>

        <div class="kb-mh-center">
            <div class="kb-search-wrapper">
                {{-- SEARCH BAR (left-most of the 3) --}}
                <form action="{{ route('products.index') }}" method="GET" class="kb-search-bar">

                    {{-- preserve category[] --}}
                    @foreach((array) request('category', []) as $cat)
                        <input type="hidden" name="category[]" value="{{ $cat }}">
                    @endforeach

                    {{-- preserve brand[] --}}
                    @foreach((array) request('brand', []) as $b)
                        <input type="hidden" name="brand[]" value="{{ $b }}">
                    @endforeach

                    {{-- preserve price --}}
                    @if(request()->filled('min_price'))
                        <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                    @endif
                    @if(request()->filled('max_price'))
                        <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                    @endif

                    {{-- preserve sort --}}
                    @if(request()->filled('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif

                    {{-- preserve deals --}}
                    @if(request()->filled('deals'))
                        <input type="hidden" name="deals" value="1">
                    @endif
                    {{-- Reset pagination when searching --}}


                    <input type="text" name="search" placeholder="Search products..."
                        value="{{ request('search') }}">

                    <button type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </form>

            </div>
        </div>

        <div class="kb-mh-right">
            <div class="kb-cart-container-button">
                <a href="/cart" class="kb-cart-btn" aria-label="Cart" id="cart-link">
                    <span class="kb-cart-icon-wrapper">
                        <i class="bi bi-bag kb-cart-icon"></i>
                        <span class="kb-cart-count" id="cart-count">{{ $cartItemCount ?? 0 }}</span>
                    </span>
                    <span class="kb-cart-label">Cart</span>
                </a>
                
                <div class="kb-cart-preview" id="cart-preview">
                    <div class="cart-preview-header">
                        <h4>Your Cart</h4>
                        <small id="preview-total-items">0 items</small>
                    </div>
                    <div class="cart-preview-items" id="preview-items">
                    </div>
                    <div class="cart-preview-total">
                        <strong>Total: <span id="preview-total-price">£0.00</span></strong>
                    </div>
                    <div class="cart-preview-footer">
                        <a href="/cart" class="btn-view-cart">View Cart</a>
                    </div>
                </div>
            </div>
            <div class="kb-account-wrapper">
                <button type="button" class="kb-account-btn">
                    <i class="bi bi-person-circle kb-account-icon"></i>
                    <div class="kb-account-text">
                        @auth
                            <span class="kb-account-greet">Hello, {{ auth()->user()->first_name }}</span>
                            <span class="kb-account-action">My Account</span>
                        @else
                            <span class="kb-account-greet">Welcome</span>
                            <span class="kb-account-action">Sign in</span>
                        @endauth
                    </div>
                </button>

                <div class="kb-account-menu">
                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('account.dashboard') }}">My Account</a>
                            <button type="submit" class="kb-logout-btn">Sign out</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}">Sign in</a>
                        <a href="{{ route('register') }}">Create account</a>
                    @endauth
                </div>
            </div>
            <div>
                <button id="theme-toggle" type="button" class="theme-toggle-btn">
                    <i class="bi bi-moon-fill" id="theme-icon"></i>
                </button>
            </div>
    </div>     
</div>


<div class="kb-bottom-header">
    <div class="kb-bottom-header-inner">

        {{-- LEFT: All + hamburger --}}
        <div class="kb-hamburger-wrapper">
            <button type="button" class="kb-all-btn">
                <i class="bi bi-justify kb-hamburger-icon"></i>

                <span class="kb-all-label">All Categories</span>
            </button>
        </div>

        <div class="kb-bt-center">
            <nav class="kb-nav">
                <ul class="kb-nav-links">
                    <li class="kb-nav-item">
                        <a href="{{ route('products.index') }}" class="kb-nav-link">Products</a>
                    </li>
                    <li class="kb-nav-item">
                        <a href="{{ route('products.index', ['category' => 'gym-equipment']) }}" class="kb-nav-link">
                            Gym Equipment
                        </a>
                    </li>

                    <li class="kb-nav-item">
                        <a href="{{ route('products.index', ['category' => 'gym-apparel']) }}" class="kb-nav-link">
                            Gym Apparel
                        </a>
                    </li>

                    <li class="kb-nav-item">
                        <a href="{{ route('products.index', ['category' => 'nutrition-and-supplements']) }}" class="kb-nav-link">
                            Nutrition &amp; Supplements
                        </a>
                    </li>

                    <li class="kb-nav-item">
                        <a href="{{ route('products.index', ['category' => 'accessories']) }}" class="kb-nav-link">
                            Accessories
                        </a>
                    </li>

                    <li class="kb-nav-item">
                        <a href="{{ route('products.index', ['category' => 'recovery-and-wellness']) }}" class="kb-nav-link">
                            Recovery &amp; Wellness
                        </a>
                    </li>
                    <li class="kb-nav-item">
                        <a href="{{ route('contact') }}" class="kb-nav-link">Contact us</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>





    