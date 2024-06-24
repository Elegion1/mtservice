<x-layout>

    <div class="row">

        <div class="col-12 col-md-6 ">
            <div class="container shadow rounded bg-white">
                <livewire:prenotazione />
                <div class="d-flex justify-content-center align-items-center flex-column">
                    <img class="my-3" src="https://picsum.photos/500" alt="">
                    <p>
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Cupiditate culpa eos, quidem,
                        asperiores debitis ea illo corporis autem eligendi molestias consectetur? Explicabo esse
                        itaque
                        facilis culpa blanditiis ducimus aut magnam.
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Cumque necessitatibus tempora
                        eaque
                        corporis assumenda? Perspiciatis, dolor. Eveniet error earum ipsa, iusto nihil harum nisi
                        ratione eum eos sint. Rerum, vel.
                        Lorem ipsum dolor sit, amet consectetur adipisicing elit. Minus maxime quidem omnis
                        eligendi? Ut
                        molestias nesciunt veritatis incidunt architecto recusandae quisquam vitae, accusamus sit
                        sunt
                        deleniti, eligendi ducimus minima animi!
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Qui ipsum exercitationem maiores
                        explicabo aspernatur quos et sint quaerat molestiae! Atque officiis modi cumque nostrum,
                        doloremque commodi dolorum ducimus corrupti blanditiis?
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Obcaecati quis facere praesentium
                        eligendi sapiente maxime dolore, laborum odio eaque temporibus sed, similique vel voluptas
                        assumenda harum nemo! Omnis, asperiores fugiat!
                        Lorem ipsum dolor, sit amet consectetur adipisicing elit. Accusamus repudiandae non pariatur
                        aspernatur laudantium iusto optio unde animi, magni consequuntur placeat tenetur itaque
                        alias
                        obcaecati, modi omnis quas rerum impedit.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 ">
            <div class="container d-flex justify-content-center align-items-center flex-column shadow rounded bg-white">
                @foreach ($excursions as $excursion)
                    <div class="card border-0 mb-3" style="max-width: 540px;">
                        <div class="row g-0">
                            <div class="col-md-4 d-flex justify-content-center align-items-center">
                                @if ($excursion->images->isNotEmpty())
                                    <img src="{{ Storage::url($excursion->images->first()->path) }}"
                                        class="img-fluid rounded-start" alt="...">
                                @else
                                    <img src="https://picsum.photos/1000" class="img-fluid rounded-start"
                                        alt="immagine non disponibile">
                                @endif
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $excursion->name }}</h5>
                                    <p class="card-text">{{ $excursion->abstract }}</p>
                                    <p class="card-text"><small
                                            class="text-body-secondary">{{ $excursion->description }}</small>
                                    </p>
                                    <p class="card-text">
                                        <small class="text-body-secondary">A partire da</small>
                                        {{ $excursion->price }} €
                                        <span>
                                            <a class="btn rounded-4 bg-a text-white " href="">Dettagli</a>
                                            <a class="btn rounded-4 bg-b text-white " href="">Prenota</a>
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <!-- Controlli di paginazione -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    {{ $excursions->links('vendor.pagination.bootstrap-5') }}
                </div>

            </div>
        </div>

    </div>

</x-layout>
