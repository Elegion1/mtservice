<x-layout :seo-title="$seoTitle" :seo-description="$seoDescription">
    <div class="container mb-5">
        <h1 class="text-uppercase mb-3">
            Transfer da
            <span class="text-primary">
                {{ $route->departure->name }}
            </span>
            a
            <span class="text-primary">
                {{ $route->arrival->name }}
            </span>
        </h1>

        <p>
            Prenota il tuo transfer sicuro e veloce da <strong>{{ $route->departure->name }}</strong> a
            <strong>{{ $route->arrival->name }}</strong>. Offriamo servizi di taxi e transfer privati a Trapani e in
            tutta la Sicilia, ideali per chi vuole viaggiare comodamente senza stress e senza perdere tempo.
        </p>

        <div class="row g-2 my-4" aria-label="Vantaggi del servizio">
            <div class="col-6 col-md-3"><div class="border rounded p-3 h-100"><strong>H24</strong><br><small>Disponibilità su richiesta</small></div></div>
            <div class="col-6 col-md-3"><div class="border rounded p-3 h-100"><strong>Prezzo chiaro</strong><br><small>Comunicata prima della conferma</small></div></div>
            <div class="col-6 col-md-3"><div class="border rounded p-3 h-100"><strong>Autista locale</strong><br><small>Assistenza all’arrivo</small></div></div>
            <div class="col-6 col-md-3"><div class="border rounded p-3 h-100"><strong>Pagamento in loco</strong><br><small>Direttamente all’autista</small></div></div>
        </div>

        <h3 class="mt-4">Perché scegliere il nostro servizio di transfer</h3>
        <ul>
            <li>Veicoli moderni, puliti e confortevoli</li>
            <li>Autisti professionisti e locali</li>
            <li>Tariffe trasparenti e competitive</li>
            <li>Disponibilità 24/7 per aeroporti e stazioni</li>
        </ul>

        <h3 class="mt-4">Prezzi e gestione passeggeri</h3>
        <p>
            Il prezzo standard per questo transfer parte da <strong>{{ $route->price }} €</strong> per persona.
            Il costo rimane invariato fino a <strong>{{ $route->increment_passengers }} passeggeri</strong>.
            Per ogni passeggero aggiuntivo fino a un massimo di 8 persone, si applica un incremento di
            <strong>{{ $route->price_increment }} €</strong> per persona.
        </p>
        <p>
            Se il numero di passeggeri supera gli 8, utilizziamo un altro van per garantire comfort e sicurezza a
            tutti i passeggeri.
        </p>

        <h3 class="mt-4">Domande frequenti</h3>
        <div class="accordion mb-4" id="routeFaq">
            <div class="accordion-item">
                <h4 class="accordion-header" id="routeFaqPrice">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#routeFaqPriceAnswer">Il prezzo è totale o per persona?</button>
                </h4>
                <div id="routeFaqPriceAnswer" class="accordion-collapse collapse" aria-labelledby="routeFaqPrice" data-bs-parent="#routeFaq">
                    <div class="accordion-body">Il prezzo indicato è per persona e resta invariato fino a {{ $route->increment_passengers }} passeggeri. Gli eventuali incrementi sono indicati sopra.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h4 class="accordion-header" id="routeFaqAirport">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#routeFaqAirportAnswer">Dove incontro l’autista?</button>
                </h4>
                <div id="routeFaqAirportAnswer" class="accordion-collapse collapse" aria-labelledby="routeFaqAirport" data-bs-parent="#routeFaq">
                    <div class="accordion-body">Per gli arrivi in aeroporto l’autista attende all’uscita dal ritiro bagagli con il logo Tranchida Transfer o il nominativo del passeggero.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h4 class="accordion-header" id="routeFaqLuggage">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#routeFaqLuggageAnswer">Quanti bagagli posso portare?</button>
                </h4>
                <div id="routeFaqLuggageAnswer" class="accordion-collapse collapse" aria-labelledby="routeFaqLuggage" data-bs-parent="#routeFaq">
                    <div class="accordion-body">È incluso un bagaglio fino a 25 kg per passeggero. Comunica eventuali bagagli extra o oggetti voluminosi durante la prenotazione.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h4 class="accordion-header" id="routeFaqCancel">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#routeFaqCancelAnswer">Qual è la politica di cancellazione?</button>
                </h4>
                <div id="routeFaqCancelAnswer" class="accordion-collapse collapse" aria-labelledby="routeFaqCancel" data-bs-parent="#routeFaq">
                    <div class="accordion-body">La cancellazione va comunicata almeno 72 ore prima del servizio. Consulta i termini completi prima di confermare.</div>
                </div>
            </div>
        </div>

        <h4 class="mt-4">Servizi aggiuntivi disponibili</h4>
        <x-services />

        <p>
            Scopri anche i nostri <a href="{{ route('escursioni') }}">tour ed escursioni in Sicilia</a> e combina il tuo
            transfer con esperienze indimenticabili.
        </p>

        <p class="mt-4">
            Prenota subito il tuo transfer da <strong>{{ $route->departure->name }}</strong> a
            <strong>{{ $route->arrival->name }}</strong>
            e goditi un viaggio comodo, sicuro e su misura in Sicilia.
        </p>
        <div class="d-flex flex-wrap gap-2 mt-3">
            <a class="btn bg-dark text-light" href="#bookingModal" data-bs-toggle="modal">{{ __('ui.getQuote') }}</a>
            @if ($ownerdata->phone2)
                <a class="btn btn-outline-dark" href="tel:{{ $ownerdata->phone2 }}">Chiama ora</a>
            @endif
            @if ($ownerdata->whatsappLink)
                <a class="btn btn-outline-success" href="{{ $ownerdata->whatsappLink }}">Chatta su WhatsApp</a>
            @endif
        </div>
    </div>
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => [
                ['@type' => 'Question', 'name' => 'Il prezzo è totale o per persona?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Il prezzo indicato è per persona e resta invariato fino a '.$route->increment_passengers.' passeggeri.']],
                ['@type' => 'Question', 'name' => 'Dove incontro l’autista?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Per gli arrivi in aeroporto l’autista attende all’uscita dal ritiro bagagli con il logo Tranchida Transfer o il nominativo del passeggero.']],
                ['@type' => 'Question', 'name' => 'Quanti bagagli posso portare?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'È incluso un bagaglio fino a 25 kg per passeggero.']],
                ['@type' => 'Question', 'name' => 'Qual è la politica di cancellazione?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'La cancellazione va comunicata almeno 72 ore prima del servizio.']],
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
</x-layout>
