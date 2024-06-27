<nav class="navbar navbar-expand-lg bg-b">
    <div class="container">
        <div class="row w-100">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img class="logo-img" src="{{ Storage::url($ownerdata->images->first()->path) }}" alt="">
                </a>
                <div class=" d-flex justify-content-center align-items-center flex-column">
                    <span class="text-white fs-5">Prenota online o chiama</span>
                    <div class="d-flex justify-content-center aling-items-center">
                        <a class="p-md-2 m-md-1 nav-link text-white border rounded-pill"
                            href="tel:{{ $ownerdata->phone2 }}"><span><i class="bi bi-telephone-fill"></i></span> {{ $ownerdata->phone2 }}</a>

                        <a class="p-md-2 m-md-1 nav-link text-white border rounded-pill"
                            href="tel:{{ $ownerdata->phone3 }}"><i class="bi bi-telephone-fill"></i> {{ $ownerdata->phone3 }}</a>
                    </div>
                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
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
                    <ul class="navbar-nav d-flex align-items-center justify-content-center flex-wrap mx-auto mb-2 mb-lg-0">
                        <x-links>
                            active text-uppercase
                        </x-links>
                    </ul>
                    {{-- <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form> --}}
                </div>
            </div>
        </div>



    </div>
</nav>
