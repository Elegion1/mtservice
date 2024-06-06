<x-layout>
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6 mt-5">
                <h1>TITOLO</h1>
                <p>
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Eligendi sed illum earum inventore commodi
                    vitae exercitationem facilis aliquam molestiae iste, error corrupti impedit ullam nihil ipsum. Fuga
                    alias aspernatur minus?
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Veniam quasi necessitatibus id repellat
                    nulla eius minus possimus quidem iure, rem quas voluptate optio veritatis reiciendis eligendi
                    asperiores sunt labore cupiditate.
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ullam consectetur doloremque sed velit in
                    beatae assumenda culpa, dignissimos nobis error? Fuga adipisci reprehenderit deserunt quis ut
                    molestias animi, in quasi?
                    Lorem ipsum, dolor sit amet consectetur adipisicing elit. Velit, officia eius suscipit adipisci quis
                    ipsam. Quod, vero. Provident sunt eaque illo quae non molestiae illum odio obcaecati esse!
                    Cupiditate, sunt?

                </p>
            </div>
            <div class="col-12 col-md-6 mt-5">
                <div class="container border rounded text-center">
                    @foreach ($tratte as $tratta)
                        <div class="my-5">
                            <h5>Da <span class="text-primary">{{ $tratta->departure->name }}</span> a <span
                                    class="text-primary">{{ $tratta->arrival->name }}</span></h5>
                            <p>A partire da <strong class="h4">{{ $tratta->price }} €</strong> a persona</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-layout>
