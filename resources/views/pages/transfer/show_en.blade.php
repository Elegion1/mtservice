<x-layout>
    <x-seo-data :seoTitle="$seoTitle" :seoDescription="$seoDescription" />
    <div class="container mb-5">
        <h2 class="text-uppercase mb-3">
            Transfer from 
            <span class="text-primary">
                {{ $route->departure->name }}
            </span>
            to 
            <span class="text-primary">
                {{ $route->arrival->name }}
            </span>
        </h2>

        <p>
            Book your safe and fast transfer from <strong>{{ $route->departure->name }}</strong> to
            <strong>{{ $route->arrival->name }}</strong>. We offer private taxi and transfer services in Trapani and
            throughout Sicily, perfect for travelers who want to move comfortably without stress or wasted time.
        </p>

        <h3 class="mt-4">Why choose our transfer service</h3>
        <ul>
            <li>Modern, clean and comfortable vehicles</li>
            <li>Professional local drivers</li>
            <li>Transparent and competitive rates</li>
            <li>24/7 availability for airports and stations</li>
        </ul>

        <h3 class="mt-4">Pricing and passenger management</h3>
        <p>
            The standard price for this transfer starts at <strong>{{ $route->price }} €</strong> per person.
            The cost remains the same up to <strong>{{ $route->increment_passengers }} passengers</strong>.
            For each additional passenger, up to a maximum of 8 people, an extra charge of
            <strong>{{ $route->price_increment }} €</strong> per person applies.
        </p>
        <p>
            If the number of passengers exceeds 8, we use a larger van to ensure comfort and safety for everyone.
        </p>

        <h4 class="mt-4">Additional services available</h4>
        <x-services />

        <p>
            Check out our <a href="{{ route('escursioni') }}">tours and excursions in Sicily</a> and combine your
            transfer with unforgettable experiences.
        </p>

        <p class="mt-4">
            Book your transfer now from <strong>{{ $route->departure->name }}</strong> to
            <strong>{{ $route->arrival->name }}</strong> and enjoy a comfortable, safe, and tailor-made journey in
            Sicily.
        </p>
    </div>
</x-layout>
