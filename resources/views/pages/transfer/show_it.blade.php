<x-layout>
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

        <h2 class="mt-4">Perché scegliere il nostro servizio di transfer</h2>
        <ul>
            <li>Veicoli moderni, puliti e confortevoli</li>
            <li>Autisti professionisti e locali</li>
            <li>Tariffe trasparenti e competitive</li>
            <li>Disponibilità 24/7 per aeroporti e stazioni</li>
        </ul>

        <h2 class="mt-4">Prezzi e gestione passeggeri</h2>
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

        <h3 class="mt-4">Servizi aggiuntivi disponibili</h3>
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
    </div>
</x-layout>
