	<!-- Language selector -->
    <div class="dropup mt-0 text-center text-sm-end">
        <a class="dropdown-toggle nav-link" href="#" role="button" id="languageSwitcher" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-globe me-2"></i>{{ tr('Language') }}
        </a>
        <ul class="dropdown-menu min-w-auto" aria-labelledby="languageSwitcher">
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
    </div>
