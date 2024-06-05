<x-layout>
    <div class="container">
        <div class="row">
            <div class="col-6 d-flex justify-content-center align-items-center ">
                <img src="https://picsum.photos/400" alt="">
            </div>
            <div class="col-6 mt-5">
                <p>
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Harum consequatur ut nam similique
                    ducimus temporibus enim incidunt inventore dolores! Veniam autem corporis facere aut incidunt
                    tempore quam, velit ratione perspiciatis. Lorem ipsum dolor, sit amet consectetur adipisicing
                    elit. Perspiciatis aliquid sunt dicta itaque rerum aspernatur consequatur sit, odio, sint omnis
                    molestias rem molestiae quis nisi veritatis nam. Animi, sed. Lorem ipsum dolor sit amet
                    consectetur adipisicing elit. Odio earum libero maiores nobis, explicabo omnis repudiandae vel
                    eveniet nemo hic distinctio iste quis nisi? Deserunt magnam repellendus facere unde veritatis.
                    Lorem ipsum dolor sit amet consectetur, adipisicing elit. Nisi mollitia maiores at. Voluptatum
                    non dicta iusto distinctio ea officiis consectetur quo aliquam? Ipsam ipsa eligendi nihil
                    corporis fugiat, magnam numquam. Lorem ipsum dolor, sit amet consectetur adipisicing elit. Nihil
                    aspernatur dignissimos ratione unde, at delectus alias autem corporis reiciendis recusandae ea
                    quia dicta atque, temporibus eaque mollitia nesciunt quaerat quisquam! Lorem ipsum dolor sit
                    amet consectetur adipisicing elit. Corporis natus cum fugiat error neque hic, dignissimos eos,
                    dolores iusto consequatur aliquam. Repellat harum in fugit iusto ut saepe voluptas natus.
                </p>
                @livewire('car-rent')
                @if (session()->has('bookingData'))
                    @livewire('booking-summary', ['bookingData' => session('bookingData')])
                @endif

            </div>
        </div>
    </div>
</x-layout>
