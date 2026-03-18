<!-- Language -->
<ul class="navbar-nav navbar-nav-scroll me-3 d-none d-xl-block">
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="language" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-globe me-2"></i>
            <span class="d-none d-lg-inline-block">{{ tr('Language') }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end min-w-auto" aria-labelledby="language">
            @if(get_option('lms_options.enable_localization'))
            @foreach (config('languages') as $lang => $language)
                @if ($lang != app()->getLocale())
                    <a class="dropdown-item me-4" href="{{ route('lang.switch', $lang) }}">
                        <img class="fa-fw me-2" src="/assets/images/flags/{{ $language['flag-icon'] }}.svg" alt="{{ $language['display'] }} flag">
                        {{ $language['display'] }}
                    </a>
                @endif
            @endforeach
        @else
            <p class="dropdown-item disabled">{{ tr('Language localization is not enabled') }}</p>
        @endif
        </ul>
    </li>
</ul>
