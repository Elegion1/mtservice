<footer class="pb-1 border_footer p-0 m-0 bg-linear-gradient px-md-5 px-1 pt-1">
    <div class="p-1">
        {{ Breadcrumbs::render() }}
    </div>
    <div class="d-flex justify-content-between p-1 pt-3">

        <div class="mb-3 text-small">
            <a href="/" class="d-flex align-items-center mb-3 link-body-emphasis text-decoration-none">
                <img width="100px" src="{{ Storage::url($ownerdata->images->first()->path) }}" alt="">
            </a>
            <small>{{ $ownerdata->companyName }}</small><br>
            <small>di {{ $ownerdata->name }} {{ $ownerdata->surname }}</small><br>
            <small>{{ $ownerdata->address }}</small><br>
            <small>{{ $ownerdata->city }}</small><br>
            <small>P.IVA: {{ $ownerdata->pIva }}</small><br>
            <small>C.F.: {{ $ownerdata->codFisc }}</small><br>
            <br>
            <a class="nav-link" href="{{ route('privacy') }}#privacy" target="_blank">{{ __('ui.privacyPolicy') }}</a>
            <a class="nav-link" href="{{ route('privacy') }}#terms" target="_blank">{{ __('ui.termsConditions') }}</a>

        </div>

        <div class="mb-3 text-center me-1">
            <h5>{{ __('ui.navigation') }}</h5>
            <ul class="nav flex-column">
                <x-links>
                    text-body-secondary text-capitalize p-0 text-small
                </x-links>
                <li>
                    <a class="nav-link text-body-secondary text-capitalize p-0 text-small"
                        href="{{ route('booking.status') }}">{{ __('ui.bookingStatus') }}</a>
                </li>
                <li>
                    <a class="nav-link text-body-secondary text-capitalize p-0 text-small"
                        href="https://tranchidatransfer.it/sitemap.xml">Sitemap</a>
                </li>
            </ul>
        </div>

        <div class="mb-3 text-end">
            <h5>{{ __('ui.contacts') }}</h5>
            <ul class="nav flex-column">
                @if ($ownerdata->phone2 && $ownerdata->phone2Name)
                    <li class="nav-item mb-2">
                        <a class="nav-link p-0 text-body-secondary" href="tel:{{ $ownerdata->phone2 }}"><span><i
                                    class="bi bi-telephone-fill"></i></span>
                            {{ $ownerdata->phone2Name }}</a>
                    </li>
                @endif
                @if ($ownerdata->phone3 && $ownerdata->phone3Name)
                    <li class="nav-item mb-2">
                        <a class="nav-link p-0 text-body-secondary" href="tel:{{ $ownerdata->phone3 }}"><span><i
                                    class="bi bi-telephone-fill"></i></span>
                            {{ $ownerdata->phone3Name }}</a>
                    </li>
                @endif
                @if ($ownerdata->facebook)
                    <li class="nav-item mb-2"><a href="{{ $ownerdata->facebook }}" class="nav-link p-0 text-primary"><i
                                class="bi bi-facebook"></i></a></li>
                @endif
                @if ($ownerdata->whatsapp)
                    <li class="nav-item mb-2"><a href="{{ $ownerdata->whatsapp }}" class="nav-link p-0 text-success"><i
                                class="bi bi-whatsapp"></i></a></li>
                @endif
            </ul>
        </div>
    </div>
    <div class="container">
        <p class="text-center text-small">{{ __('ui.footerMessage') }}</p>
        <div class="text-center text-small">
            <hr class="m-0">
            <small>
                M.T. Service <i class="bi bi-c-circle"></i> 2024 All
                rights reserved</small>
            <br>
            <small>Created and Optimized by <a
                    class="text-reset link-underline link-underline-opacity-0 link-underline-opacity-75-hover"
                    href="https://www.linkedin.com/in/giovanni-sugamiele-webdev/">Giovanni Sugamiele</a></small>
        </div>
    </div>
</footer>
