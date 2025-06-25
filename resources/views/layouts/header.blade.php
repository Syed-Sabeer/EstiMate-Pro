<div class="page-header">
    <div class="header-wrapper row m-0">
        <div class="header-logo-wrapper col-auto p-0">
            <div class="logo-wrapper"><a href="{{ route('login') }}"><img class="img-fluid"
                        src="{{ asset(\App\Helpers\Helper::getLogoLight()) }}" alt=""></a></div>
            <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="align-center"></i>
            </div>
        </div>
        <div class="left-header col-xxl-5 col-xl-6 col-lg-5 col-md-4 col-sm-3 p-0">
            <ul class="nav-menus">
                @can(['create package'])
                    <li>
                        <a href="{{ route('dashboard.packages.send_in_one_click') }}" type="button"
                            class="btn btn-primary">
                            {{ __('Send In One Click') }}
                        </a>
                    </li>
                @endcan
            </ul>
        </div>
        <div class="nav-right col-xxl-7 col-xl-6 col-md-7 col-8 pull-right right-header p-0 ms-auto">
            <ul class="nav-menus">
                @if(env('ALLOW_TRANSLATION') == true)
                <li class="language-nav">
                    <div class="translate_wrapper">
                        <div class="current_lang">
                            <div class="lang">
                                <i
                                    class="flag-icon flag-icon-{{ App::getLocale() == 'en' ? 'us' : (App::getLocale() == 'cn' ? 'cn' : 'ae') }}"></i>
                                <span class="lang-txt">{{ strtoupper(App::getLocale()) }}</span>
                            </div>
                        </div>
                        {{-- <div class="more_lang">
                        <a href="{{route('lang')}}" class="lang selected" data-value="en"><i class="flag-icon flag-icon-us"></i><span class="lang-txt">English<span> (US)</span></span></a>
                        <a href="{{route('lang','ch')}}" class="lang" data-value="cn"><i class="flag-icon flag-icon-cn"></i><span class="lang-txt">简体中文</span></a>
                        <a class="lang" data-value="ae"><i class="flag-icon flag-icon-ae"></i><span class="lang-txt">لعربية <span> (ae)</span></span></a>
                        </div> --}}
                        <div class="more_lang">
                            <a href="{{ route('lang', ['lang' => 'en']) }}"
                                class="lang {{ App::getLocale() == 'en' ? 'selected' : '' }}" data-value="en">
                                <i class="flag-icon flag-icon-us"></i>
                                <span class="lang-txt">English<span> (US)</span></span>
                            </a>
                            <a href="{{ route('lang', ['lang' => 'cn']) }}"
                                class="lang {{ App::getLocale() == 'cn' ? 'selected' : '' }}" data-value="cn">
                                <i class="flag-icon flag-icon-cn"></i>
                                <span class="lang-txt">简体中文<span>(CN)</span></span>
                            </a>
                            <a href="{{ route('lang', ['lang' => 'ae']) }}"
                                class="lang {{ App::getLocale() == 'ae' ? 'selected' : '' }}" data-value="ae">
                                <i class="flag-icon flag-icon-ae"></i>
                                <span class="lang-txt">لعربية<span> (AE)</span></span>
                            </a>
                        </div>
                    </div>
                </li>
                @endif
                <li>
                    <div class="mode">
                        <svg>
                            <use href="{{ asset('assets/svg/icon-sprite.svg#moon') }}"></use>
                        </svg>
                    </div>
                </li>
                <li class="profile-nav onhover-dropdown pe-0 py-0">
                    <div class="media profile-media profile-pic"><img class="b-r-10"
                            src="{{ asset(Auth::user()->profile_pic ?? 'assets/images/dashboard/profile.png') }}"
                            alt="">
                        <div class="media-body"><span>{{ __(Auth::user()->name) }}</span>
                            <p class="mb-0 font-roboto">User ID: {{ Auth::user()->user_id ?? ''}}</p>
                        </div>
                    </div>
                    <ul class="profile-dropdown onhover-show-div">
                        <li><a href="{{ route('profile.index') }}"><i data-feather="user"></i><span>Profile </span></a></li>
                        {{-- <li><a href="#"><i data-feather="mail"></i><span>Inbox</span></a></li>
                        <li><a href="#"><i data-feather="file-text"></i><span>Taskboard</span></a></li> --}}
                        {{-- <li><a href="#"><i data-feather="settings"></i><span>Settings</span></a></li> --}}
                        <li> <a href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                   document.getElementById('logout-form').submit();">
                                <i data-feather="log-in"> </i>
                                <span> {{ __('Logout') }}</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <script class="result-template" type="text/x-handlebars-template">
      <div class="ProfileCard u-cf">                        
      <div class="ProfileCard-avatar"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay m-0"><path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path><polygon points="12 15 17 21 7 21 12 15"></polygon></svg></div>
      <div class="ProfileCard-details">
      {{-- <div class="ProfileCard-realName">{{name}}</div> --}}
      </div>
      </div>
    </script>
        <script class="empty-template" type="text/x-handlebars-template"><div class="EmptyMessage">{{ __('Your search turned up 0 results. This most likely means the backend is down, yikes!') }}</div></script>
    </div>
</div>
