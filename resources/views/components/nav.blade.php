<nav class="navbar navbar-expand-lg bg_nav border_custom shadow z-3 d-flex flex-column">
    <div class="container">
        <a href="{{ route('home') }}">
            <img class="logo-img" src="{{ Storage::url($ownerdata->images->first()->path) }}"
                alt="LOGO-TRANCHIDA-TRANSFER&RENT">
        </a>

        <div class="d-flex justify-content-center align-items-center">
            @foreach (config('app.available_locales') as $locale)
                <a href="{{ updateLocaleInUrl($locale) }}"
                    class="{{ app()->getLocale() == $locale ? 'active-locale' : '' }} nav-link m-1">
                    {{ strtoupper($locale) }}
                </a>
            @endforeach
        </div>

        @php
            function updateLocaleInUrl($newLocale)
            {
                $currentUrl = request()->url(); // Get the current URL without query parameters
                $segments = request()->segments(); // Get all URL segments

                // Check if the first segment is a locale and replace it
                if (in_array($segments[0], config('app.available_locales'))) {
                    $segments[0] = $newLocale; // Update the locale segment
                } else {
                    array_unshift($segments, $newLocale); // Add the new locale as the first segment
                }

                return url(implode('/', $segments)); // Rebuild the URL and return
            }
        @endphp
        
        <div class="d-flex justify-content-between align-items-center flex-column">
            <span class="text-a">{{ __('ui.navTitle') }}</span>
            <div class="d-flex justify-content-around aling-items-center">
                @if ($ownerdata->phone2 && $ownerdata->phone2Name)
                    <a class="me-1 nav-link text-small" href="tel:{{ $ownerdata->phone2 }}"><span><i
                                class="bi bi-telephone-fill"></i></span>
                        {{ $ownerdata->phone2Name }}</a>
                @endif
                @if ($ownerdata->phone3 && $ownerdata->phone3Name)
                    <a class="me-1 nav-link text-small" href="tel:{{ $ownerdata->phone3 }}"><span><i
                                class="bi bi-telephone-fill"></i></span>
                        {{ $ownerdata->phone3Name }}</a>
                @endif
            </div>
        </div>


        <button class="navbar-toggler p-0 border-0 " type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>


        <div class="container d-block d-lg-none">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav d-flex align-items-center justify-content-center flex-wrap mx-auto mb-2 mb-lg-0">
                    <x-links>
                        text-uppercase text-d
                    </x-links>
                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid d-none d-lg-block">
        <ul class="navbar-nav d-block d-flex align-items-center justify-content-center flex-wrap mx-auto mb-2 mb-lg-0">
            <x-links>
                text-uppercase text-d
            </x-links>
        </ul>
    </div>
</nav>
