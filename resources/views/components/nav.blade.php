<nav class="navbar navbar-expand-lg bg_nav border_custom z-3 position-absolute vw-100" id="navbar">
    <div class="container-fluid d-flex justify-content-around align-items-center">

        <div>
            <a class="text-b text-decoration-none d-flex justify-content-center align-items-center m-1"
                href="{{ route('home') }}">
                <img loading="lazy" class="logo-img" src="{{ Storage::url($ownerdata->images->first()->path) }}"
                    alt="LOGO-TRANCHIDA-TRANSFER&RENT">
                <p class="m-1 text-center text-nowrap">{!! $ownerdata->siteName !!}</p>
            </a>
        </div>

        <div>

            <div class="row">

                <div class="col-6 col-md-12">
                    <form method="GET" action="" id="locale-form" class="m-1 text-center">
                        <select id="locale-select" class="form-select-sm locale-select" onchange="changeLocale()">
                            @foreach (config('app.available_locales') as $locale)
                                <option class="text-center" value="{{ updateLocaleInUrl($locale) }}"
                                    {{ app()->getLocale() == $locale ? 'selected' : '' }}>
                                    {{ strtoupper(__('ui.' . $locale)) }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="col-12">
                    <div class="container d-none d-lg-block">
                        <ul class="navbar-nav d-block d-flex align-items-center justify-content-start mb-2 mb-lg-0">
                            <x-links :linksToShow="['transfer', 'escursioni', 'noleggio']">
                                text-uppercase text-white text-nowrap
                            </x-links>
                        </ul>
                    </div>
                </div>
            </div>

        </div>

        {{-- dropdown menu --}}
        <button class="navbar-toggler p-0 border-0 navbar_toggler_focused" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <i id="toggler-icon" class="bi"></i>
        </button>
        
        <div class="container-fluid d-block d-lg-none">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav d-flex align-items-center justify-content-center mb-2 mb-lg-0">
                    <x-links>
                        text-uppercase text-white text-shadow
                    </x-links>
                </ul>
            </div>
        </div>

    </div>
</nav>

<script>
    function changeLocale() {
        const select = document.getElementById('locale-select');
        const selectedUrl = select.value;
        window.location.href = selectedUrl; // Cambia la lingua reindirizzando alla nuova URL
    }
    document.addEventListener('DOMContentLoaded', () => {
        const navbarToggler = document.querySelector('.navbar-toggler');
        const togglerIcon = document.querySelector('#toggler-icon');
        const navbar = document.querySelector('#navbar');

        // Funzione per gestire il cambio delle icone
        const toggleIcons = () => {
            const isExpanded = navbarToggler.getAttribute('aria-expanded') === 'true';
            if (isExpanded) {
                togglerIcon.classList.remove('bi-list');
                togglerIcon.classList.add('bi-x-lg');
                navbar.classList.add('bg-blur');
            } else {
                togglerIcon.classList.remove('bi-x-lg');
                togglerIcon.classList.add('bi-list');
                navbar.classList.remove('bg-blur');
            }
        };

        // Aggiungi l'evento click per aggiornare le icone
        navbarToggler.addEventListener('click', toggleIcons);

        // Assicura che le icone siano corrette al caricamento della pagina
        toggleIcons();
    });
</script>


{{-- <div class="d-flex justify-content-between align-items-center flex-column">
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
        </div> --}}
