<nav class="navbar navbar-expand-lg bg_nav border_custom shadow z-3 d-flex flex-column">
    <div class="container">
        <a href="{{ route('home') }}">
            <img class="logo-img" src="{{ Storage::url($ownerdata->images->first()->path) }}" alt="">
        </a>

        <div class="d-flex justify-content-center align-items-center">
            <x-_locale lang="it" />
            <x-_locale lang="en" />
        </div>

        <div class="d-flex justify-content-between align-items-center flex-column">
            <span class="text-d">{{ __('ui.navTitle') }}</span>
            <div class="d-flex justify-content-around aling-items-center">
                <a class="me-1 nav-link rounded-pill" href="tel:{{ $ownerdata->phone2 }}"><span><i
                            class="bi bi-telephone-fill"></i></span>
                    {{ $ownerdata->phone2Name }}</a>

                <a class="nav-link rounded-pill" href="tel:{{ $ownerdata->phone3 }}"><i
                        class="bi bi-telephone-fill"></i>
                    {{ $ownerdata->phone3Name }}</a>
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
