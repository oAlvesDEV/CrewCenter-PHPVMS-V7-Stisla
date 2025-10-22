<!-- Navbar (Top) -->
<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
        </ul>
    </form>
    @if(Auth::check())
        <ul class="navbar-nav navbar-right">
            <li class="dropdown">
                <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                    @if (Auth::user()->avatar == null)
                        <img src="{{ Auth::user()->gravatar(38) }}" class="rounded-circle mr-1">
                    @else
                        <img src="{{ Auth::user()->avatar->url }}" class="rounded-circle mr-1">
                    @endif
                    <div class="d-sm-none d-lg-inline-block">Hi, {{ Auth::user()->name_private }}</div>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-title">Logged in 5 min ago</div>
                    <a href="{{ route('frontend.profile.index') }}" class="dropdown-item has-icon">
                        <i class="far fa-user"></i> @lang('common.profile')
                    </a>

                    <div class="dropdown-divider"></div>
                    @ability('admin', 'admin-access')
                    <a href="{{ url('/admin') }}" class="dropdown-item has-icon">
                        <i class="fas fa-circle-notch"></i> @lang('common.administration')
                    </a>
                    @endability

                    <a href="{{ url('/logout') }}" class="dropdown-item has-icon text-danger">
                        <i class="fas fa-sign-out-alt"></i> @lang('common.logout')
                    </a>
                </div>
            </li>
        </ul>
    @endif
</nav>
<!-- End Navbar (Top) -->

<!-- Main Sidebar (Left with menus) -->
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <!-- Logo Normal -->
        <div class="sidebar-brand">
            <a href="{{ route('frontend.dashboard.index') }}">
                <img src="https://flyazoresvirtual.com/dva/dva-logo.png" alt="Fly Azores Virtual" style="height: 50px;">
            </a>
        </div>

        <!-- Logo Pequena -->
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('frontend.dashboard.index') }}">
                <img src="https://flyazoresvirtual.com/dva/dva-logo.png" alt="FAV" style="height: 35px;">
            </a>
        </div>

        @if(Auth::check())
            <div class="mini sidebar-menu">
                <div class="main-sidebar-user">
                    <div class="nav-profile-image">
                        @if (Auth::user()->avatar == null)
                            <img src="{{ Auth::user()->gravatar(38) }}" class="rounded-circle" style="margin-left: 5px;">
                        @else
                            <img src="{{ Auth::user()->avatar->url }}" class="rounded-circle" style="margin-left: 5px;">
                        @endif
                    </div>
                    <div class="nav-profile-info d-flex flex-column">
                        <span class="nav-profile-text">{{ Auth::user()->name_private }}</span>
                        <span class="nav-profile-link">
                            <a class="no-margin" href="{{ route('frontend.profile.index') }}" data-toggle="tooltip" data-placement="bottom" title="@lang('common.profile')"><i class="fas fa-user"></i></a>
                            <a class="no-margin" href="{{ route('frontend.flights.index') }}" data-toggle="tooltip" data-placement="bottom" title="{{ trans_choice('common.flight', 2) }}"><i class="fab fa-avianex"></i></a>
                            <a class="no-margin" href="{{ route('frontend.pireps.index') }}" data-toggle="tooltip" data-placement="bottom" title="{{ trans_choice('common.pirep', 2) }}"><i class="fas fa-cloud-upload-alt"></i></a>
                            <a class="no-margin" href="{{ url('/logout') }}" data-toggle="tooltip" data-placement="bottom" title="@lang('common.logout')"><i class="fas fa-sign-out-alt" style="color: #bd2130;"></i></a>
                        </span>
                    </div>
                </div>
            </div>
        @endif

        <ul class="sidebar-menu">
            @if(Auth::check())
                <li class="menu-header">@lang('common.dashboard')</li>
                <li>
                    <a class="nav-link" href="{{ route('frontend.dashboard.index') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>@lang('common.dashboard')</span>
                    </a>
                </li>
            @endif

            <li class="menu-header">@lang('stisla.pilotadmin')</li>
            <li>
                <a class="nav-link" href="{{ route('frontend.livemap.index') }}">
                    <i class="fas fa-globe"></i>
                    <span>@lang('common.livemap')</span>
                </a>
            </li>

            <li>
                <a class="nav-link" href="{{ route('frontend.pilots.index') }}">
                    <i class="fas fa-users"></i>
                    <span>{{ trans_choice('common.pilot', 2) }}</span>
                </a>
            </li>

            @if(!Auth::check())
                <li class="menu-header">@lang('stisla.loginsystem')</li>
                <li>
                    <a class="nav-link" href="{{ url('/register') }}">
                        <i class="far fa-id-card"></i>
                        <span>@lang('common.register')</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="{{ url('/login') }}">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>@lang('common.login')</span>
                    </a>
                </li>
            @else
                <li class="menu-header">@lang('stisla.flightop')</li>
                <li>
                    <a class="nav-link" href="{{ route('frontend.flights.index') }}">
                        <i class="fab fa-avianex"></i>
                        <span>{{ trans_choice('common.flight', 2) }}</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="{{ route('frontend.pireps.index') }}">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span>{{ trans_choice('common.pirep', 2) }}</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="{{ route('frontend.flights.bids') }}">
                        <i class="fab fa-avianex"></i>
                        <span>{{ trans_choice('flights.mybid', 2) }}</span>
                    </a>
                </li>

                <li class="menu-header">@lang('stisla.resources')</li>
                <li>
                    <a class="nav-link" href="{{ route('frontend.downloads.index') }}">
                        <i class="fas fa-download"></i>
                        <span>{{ trans_choice('common.download', 2) }}</span>
                    </a>
                </li>

                {{-- Show the module links for being logged in --}}
                @foreach($moduleSvc->getFrontendLinks($logged_in=true) as &$link)
                <li>
                    <a class="nav-link" href="{{ url($link['url']) }}">
                        <i class="{{ $link['icon'] }}"></i>
                        <span>{{ ($link['title']) }}</span>
                    </a>
                </li>
                @endforeach

                @foreach($page_links as $page)
                <li>
                    <a class="nav-link" href="{{ url(route('frontend.pages.show', ['slug' => $page->slug])) }}">
                        @if ($page['icon'])
                            <i class="{{ $page['icon'] }}"></i>
                        @else
                            <i class="fas fa-scroll"></i>
                        @endif
                        <span>{{ $page['name'] }}</span>
                    </a>
                </li>
                @endforeach
            @endif

            {{-- Show the module links that don't require being logged in --}}
            @foreach($moduleSvc->getFrontendLinks($logged_in=false) as &$link)
            <li>
                <a class="nav-link" href="{{ url($link['url']) }}">
                    <i class="{{ $link['icon'] }}"></i>
                    <span>{{ ($link['title']) }}</span>
                </a>
            </li>
            @endforeach
        </ul>

        @ability('admin', 'admin-access')
        <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
            <a href="{{ url('/admin') }}" class="btn btn-primary btn-lg btn-block btn-icon-split">
                <i class="fas fa-circle-notch"></i> @lang('stisla.administration')
            </a>
        </div>
        @endability
    </aside>
</div>
<!-- End Main Sidebar (Left with menus) -->

<!-- CSS Section -->
@yield('css')
<style>
    .main-sidebar-user {
        display: inline-block;
        width: 100%;
        padding: 10px;
        margin-left: 9px;
    }

    .nav-profile-image, .nav-profile-image img {
        width: 55px;
        height: 55px;
        float: left;
    }

    .nav-profile-info {
        margin-left: 5rem;
        margin-top: 8px;
    }

    .nav-profile-text {
        font-size: 15px;
        font-weight: 600;
        line-height: 23px;
        color: black;
    }

    .nav-profile-link {
        font-size: 14px;
    }

    .no-margin {
        color: #c3bdbd !important;
        margin-left: 0px !important;
        margin-right: 14px !important;
    }

    .nav-profile-info a:hover {
        color: #242424 !important;
    }

    .no-margin i {
        margin-left: 0px !important;
    }

    body.sidebar-mini .main-sidebar .mini {
        display: none;
    }
</style>
