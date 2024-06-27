<div class="container ">
    <footer class="row py-5 my-5 border-top">
        <div class="col-6 mb-3">
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
        </div>

        <div class="col-3 mb-3">
            <h5>Navigazione</h5>
            <ul class="nav flex-column">
                <x-links>
                    text-body-secondary text-capitalize p-0
                </x-links>
            </ul>
        </div>

        <div class="col-3 mb-3">
            <h5>Contatti</h5>
            <ul class="nav flex-column">
                    <li class="nav-item mb-2"><a href="tel:{{$ownerdata->phone2}}" class="nav-link p-0 text-body-secondary">Giuseppe</a></li>
                    <li class="nav-item mb-2"><a href="tel:{{$ownerdata->phone3}}" class="nav-link p-0 text-body-secondary">Maurizio</a></li>
                    <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary"><i class="bi bi-facebook"></i></a></li>
                    <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary"><i class="bi bi-whatsapp"></i></a></li>

            </ul>
        </div>
    </footer>
</div>
