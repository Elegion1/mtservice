<nav class="navbar navbar-expand-lg bg_white_transparent z-3">
    <div class="container">
        <div class="row w-100">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img class="logo-img" src="{{ Storage::url($ownerdata->images->first()->path) }}" alt="">
                </a>
                <div class="d-flex justify-content-end align-items-center">
                    <x-_locale lang="it" />
                    <x-_locale lang="en" />
                </div>
                <div class=" d-flex justify-content-between align-items-center flex-column">
                    <span class="text-d fs-5">{{__('ui.navTitle')}}</span>
                    <div class="d-flex justify-content-center aling-items-center">
                        <a class="p-md-2 m-md-1 mx-auto nav-link rounded-pill"
                            href="tel:{{ $ownerdata->phone2 }}"><span><i class="bi bi-telephone-fill"></i></span>
                            Giuseppe</a>

                        <a class="p-md-2 m-md-1 mx-auto nav-link rounded-pill" href="tel:{{ $ownerdata->phone3 }}"><i
                                class="bi bi-telephone-fill"></i> Maurizio</a>
                    </div>
                </div>
                <button class="navbar-toggler p-0 border-0 navbar_toggler_focused" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            {{-- <div class="col-12 d-md-none d-block">
                <small>Chiama per info</small>
                <br>
                <a class="text-decoration-none" href="tel:{{ $ownerdata->phone2 }}">{{ $ownerdata->phone2 }}</a>
                <a class="text-decoration-none" href="tel:{{ $ownerdata->phone3 }}">{{ $ownerdata->phone3 }}</a>
            </div> --}}

            <div class="col-12">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul
                        class="navbar-nav d-flex align-items-center justify-content-center flex-wrap mx-auto mb-2 mb-lg-0">
                        <x-links>
                            text-uppercase text-d
                        </x-links>
                    </ul>
                    {{-- <form class="d-flex" role="search">
                <input class="form-control form_input_focused me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form> --}}
                </div>
            </div>
        </div>



    </div>
</nav>
