<div class="container-lg pb-1">
    <footer>
        <div class="d-flex justify-content-between p-1 pt-3">


            <div class="mb-3 text-small">
                <a href="/" class="d-flex align-items-center mb-3 link-body-emphasis text-decoration-none">
                    <img width="100px" src="{{ Storage::url($ownerdata->images->first()->path) }}" alt="">
                </a>
                <small>© 2024 {{ $ownerdata->companyName }}</small><br>
                <small>di {{ $ownerdata->name }} {{ $ownerdata->surname }}</small><br>
                <small>{{ $ownerdata->address }}</small><br>
                <small>{{ $ownerdata->city }}</small><br>
                <small>P.IVA: {{ $ownerdata->pIva }}</small><br>
                <small>C.F.: {{ $ownerdata->codFisc }}</small><br>
                <br>
                <a class="nav-link" href="{{ route('privacy') }}"
                    target="_blank">{{ __('ui.privacyPolicy') }}</a>

            </div>

            <div class="mb-3 text-center me-1">
                <h5>{{ __('ui.navigation') }}</h5>
                <ul class="nav flex-column">
                    <x-links>
                        text-body-secondary text-capitalize p-0 text-small
                    </x-links>

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
                    <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary"><i
                                class="bi bi-facebook"></i></a></li>
                    <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary"><i
                                class="bi bi-whatsapp"></i></a></li>

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
                <small>Created and Optimized by Giovanni Sugamiele </small>
            </div>
        </div>
    </footer>
</div>
