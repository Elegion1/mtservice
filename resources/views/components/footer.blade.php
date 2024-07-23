<div class="container-lg">
    <footer class="d-flex justify-content-between p-3 mt-5 border-top">
        <div class="mb-3">
            <a href="/" class="d-flex align-items-center mb-3 link-body-emphasis text-decoration-none">
                <img width="100px" src="{{ Storage::url($ownerdata->images->first()->path) }}" alt="">
            </a>
            <small>© 2024 {{ $ownerdata->companyName }}</small><br>
            <small>di {{ $ownerdata->name }} {{ $ownerdata->surname }}</small><br>
            <small>{{ $ownerdata->address }}</small><br>
            <small>{{ $ownerdata->city }}</small><br>
            <small>P.IVA: {{ $ownerdata->pIva }}</small><br>
            <small>C.F.: {{ $ownerdata->codFisc }}</small><br>
            {{-- <small>Cell.: <a href="tel:+393283650762">+39 328 36 50 762</a></small><br> --}}
            <br>
            <a class="nav-link" href="{{ route('privacy') }}" target="_blank">{{ __('ui.privacyPolicy') }}</a>

        </div>

        <div class="mb-3 text-center">
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
                <li class="nav-item mb-2"><a href="tel:{{ $ownerdata->phone2 }}"
                        class="nav-link p-0 text-body-secondary">Giuseppe</a></li>
                <li class="nav-item mb-2"><a href="tel:{{ $ownerdata->phone3 }}"
                        class="nav-link p-0 text-body-secondary">Maurizio</a></li>
                <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary"><i
                            class="bi bi-facebook"></i></a></li>
                <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary"><i
                            class="bi bi-whatsapp"></i></a></li>

            </ul>
        </div>
    </footer>
    <div class="text-end">
        <hr class="m-0">
        <small class="text-b visually-hidden">Optimized by Giovanni Sugamiele <i class="bi bi-c-circle"></i> 2024 All
            rights reserved </small>
    </div>
</div>
