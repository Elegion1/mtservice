<footer class="pb-1 border_footer p-0 m-0 bg-linear-gradient px-md-5 px-1 pt-1">
    <div class="p-1">

        @if (Breadcrumbs::exists())
            {{ Breadcrumbs::render() }}
        @else
            {{ Breadcrumbs::render('fallback') }}
        @endif

    </div>
    <div class="d-flex pt-3">

        <div id="footerOwnerData" class="mb-3 text-small">
            <a href="/" class="d-flex align-items-center mb-3 link-body-emphasis text-decoration-none">
                <x-responsive-image loading="lazy" image="{{ $ownerdata->images->first()->path }}" alt="logo-footer"
                    class="logo-footer" />
            </a>
            <small>{{ $ownerdata->companyName }}</small><br>
            <small>di {{ $ownerdata->name }} {{ $ownerdata->surname }}</small><br>
            <small>{{ $ownerdata->address }}</small><br>
            <small>{{ $ownerdata->city }}</small><br>
            <small>P.IVA: {{ $ownerdata->pIva }}</small><br>
            <small>C.F.: {{ $ownerdata->codFisc }}</small><br>
            <br>
            <a class="text-decoration-none text-reset" href="{{ route('privacy') }}#privacy"
                target="_blank">{{ __('ui.privacyPolicy') }}</a> <br>
            <a class="text-decoration-none text-reset" href="{{ route('privacy') }}#terms"
                target="_blank">{{ __('ui.termsConditions') }}</a>
        </div>

        <div id="footerNavigation" class="mb-3 text-center">
            <p>{{ __('ui.navigation') }}</p>
            <ul class="list-unstyled">
                <x-footer-links />
                <li>
                    <a class="text-decoration-none text-reset text-capitalize p-0 text-small"
                        href="{{ route('booking.status') }}">{{ __('ui.bookingStatus') }}</a>
                </li>
                <li>
                    <a class="text-decoration-none text-reset text-capitalize p-0 text-small"
                        href="https://tranchidatransfer.it/sitemap.xml">Sitemap</a>
                </li>
            </ul>
        </div>

        <div id="footerContacts" class="mb-3 text-end">
            <p>{{ __('ui.contacts') }}</p>
            <ul class="nav flex-column text-small">
                @php
                    $phones = [
                        ['number' => $ownerdata->phone2 ?? null, 'name' => $ownerdata->phone2Name ?? null],
                        ['number' => $ownerdata->phone3 ?? null, 'name' => $ownerdata->phone3Name ?? null],
                    ];

                    $socials = [
                        [
                            'url' => $ownerdata->facebook ?? null,
                            'icon' => 'facebook',
                            'color' => 'text-primary',
                            'label' => 'Visita la nostra pagina Facebook',
                        ],
                        [
                            'url' => $ownerdata->whatsapp ?? null,
                            'icon' => 'whatsapp',
                            'color' => 'text-success',
                            'label' => 'Chatta su WhatsApp',
                        ],
                    ];
                @endphp

                @foreach ($phones as $phone)
                    @if ($phone['number'] && $phone['name'])
                        <li class="nav-item mb-2">
                            <a href="tel:{{ $phone['number'] }}"
                                class="text-decoration-none text-reset p-0 phone-click"
                                data-number="{{ $phone['number'] }}"
                                aria-label="{{ __('ui.callAction') }} {{ $phone['number'] }}">
                                <i class="bi bi-telephone-fill"></i> {{ $phone['name'] }}
                            </a>
                        </li>
                    @endif
                @endforeach

                @foreach ($socials as $social)
                    @if ($social['url'])
                        <li class="nav-item mb-2">
                            <a href="{{ $social['url'] }}"
                                class="text-decoration-none text-reset p-0 {{ $social['color'] }}"
                                aria-label="{{ $social['label'] }}">
                                <i class="bi bi-{{ $social['icon'] }}"></i>
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
    <div class="container">
        <p class="text-center text-small">{{ __('ui.footerMessage') }}</p>
        <div class="text-center text-small">
            <hr class="m-0">
            <small>
                M.T. Service <i class="bi bi-c-circle"></i> {{ now()->year }} All
                rights reserved</small>
            <br>
            <small>Created and Optimized by
                <a class="text-reset link-underline link-underline-opacity-0 link-underline-opacity-75-hover"
                    href="https://www.linkedin.com/in/giovanni-sugamiele-webdev/">Giovanni Sugamiele</a>
            </small>
        </div>
    </div>
</footer>

