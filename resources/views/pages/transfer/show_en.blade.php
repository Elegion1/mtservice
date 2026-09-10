<x-layout :seo-title="$seoTitle" :seo-description="$seoDescription">
    <div class="container mb-5">
        <h1 class="text-uppercase mb-3">
            Transfer from 
            <span class="text-primary">
                {{ $route->departure->name }}
            </span>
            to 
            <span class="text-primary">
                {{ $route->arrival->name }}
            </span>
        </h1>

        <p>
            Book your safe and fast transfer from <strong>{{ $route->departure->name }}</strong> to
            <strong>{{ $route->arrival->name }}</strong>. We offer private taxi and transfer services in Trapani and
            throughout Sicily, perfect for travelers who want to move comfortably without stress or wasted time.
        </p>

        <div class="row g-2 my-4" aria-label="Service benefits">
            <div class="col-6 col-md-3"><div class="border rounded p-3 h-100"><strong>24/7</strong><br><small>Availability on request</small></div></div>
            <div class="col-6 col-md-3"><div class="border rounded p-3 h-100"><strong>Clear price</strong><br><small>Shared before confirmation</small></div></div>
            <div class="col-6 col-md-3"><div class="border rounded p-3 h-100"><strong>Local driver</strong><br><small>Arrival assistance</small></div></div>
            <div class="col-6 col-md-3"><div class="border rounded p-3 h-100"><strong>Pay locally</strong><br><small>Directly to the driver</small></div></div>
        </div>

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

        <h3 class="mt-4">Frequently asked questions</h3>
        <div class="accordion mb-4" id="routeFaq">
            <div class="accordion-item">
                <h4 class="accordion-header" id="routeFaqPrice">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#routeFaqPriceAnswer">Is the price total or per person?</button>
                </h4>
                <div id="routeFaqPriceAnswer" class="accordion-collapse collapse" aria-labelledby="routeFaqPrice" data-bs-parent="#routeFaq">
                    <div class="accordion-body">The listed price is per person and remains unchanged up to {{ $route->increment_passengers }} passengers. Any additional charges are shown above.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h4 class="accordion-header" id="routeFaqAirport">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#routeFaqAirportAnswer">Where will I meet the driver?</button>
                </h4>
                <div id="routeFaqAirportAnswer" class="accordion-collapse collapse" aria-labelledby="routeFaqAirport" data-bs-parent="#routeFaq">
                    <div class="accordion-body">At the airport, the driver waits at the baggage claim exit with the Tranchida Transfer logo or the passenger’s name.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h4 class="accordion-header" id="routeFaqLuggage">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#routeFaqLuggageAnswer">How much luggage can I bring?</button>
                </h4>
                <div id="routeFaqLuggageAnswer" class="accordion-collapse collapse" aria-labelledby="routeFaqLuggage" data-bs-parent="#routeFaq">
                    <div class="accordion-body">One bag up to 25 kg per passenger is included. Mention extra luggage or bulky items when booking.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h4 class="accordion-header" id="routeFaqCancel">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#routeFaqCancelAnswer">What is the cancellation policy?</button>
                </h4>
                <div id="routeFaqCancelAnswer" class="accordion-collapse collapse" aria-labelledby="routeFaqCancel" data-bs-parent="#routeFaq">
                    <div class="accordion-body">Cancellations must be communicated at least 72 hours before the service. Review the full terms before confirming.</div>
                </div>
            </div>
        </div>

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
        <div class="d-flex flex-wrap gap-2 mt-3">
            <a class="btn bg-dark text-light" href="#bookingModal" data-bs-toggle="modal">{{ __('ui.getQuote') }}</a>
            @if ($ownerdata->phone2)
                <a class="btn btn-outline-dark" href="tel:{{ $ownerdata->phone2 }}">Call now</a>
            @endif
            @if ($ownerdata->whatsappLink)
                <a class="btn btn-outline-success" href="{{ $ownerdata->whatsappLink }}">Chat on WhatsApp</a>
            @endif
        </div>
    </div>
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => [
                ['@type' => 'Question', 'name' => 'Is the price total or per person?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'The listed price is per person and remains unchanged up to '.$route->increment_passengers.' passengers.']],
                ['@type' => 'Question', 'name' => 'Where will I meet the driver?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'At the airport, the driver waits at the baggage claim exit with the Tranchida Transfer logo or the passenger’s name.']],
                ['@type' => 'Question', 'name' => 'How much luggage can I bring?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'One bag up to 25 kg per passenger is included.']],
                ['@type' => 'Question', 'name' => 'What is the cancellation policy?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Cancellations must be communicated at least 72 hours before the service.']],
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
</x-layout>
