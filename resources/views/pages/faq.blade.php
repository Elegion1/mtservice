<x-layout>
    <x-seo-data :seoTitle="$seoTitle" :seoDescription="$seoDescription" />
    <div class="container-fluid p-3 mt-5 mt-md-3">
        <h2 class="text-center">{{ __('ui.faqTitle') }}</h2>

        {{-- <div class="faq-section">
            <h2>Generale</h2>
            <div class="faq-item">
                <h3>Cos'è [Nome dell'Azienda]?</h3>
                <p>[Nome dell'Azienda] è una società specializzata in esperienze di viaggio uniche e personalizzate.
                    Offriamo una vasta gamma di tour ed escursioni in tutto il mondo, progettate per soddisfare le
                    esigenze di ogni viaggiatore.</p>
            </div>
            <div class="faq-item">
                <h3>Come posso prenotare un tour?</h3>
                <p>Puoi prenotare un tour direttamente dal nostro sito web. Basta selezionare il tour di tuo interesse,
                    scegliere le date disponibili e seguire le istruzioni per completare la prenotazione. In
                    alternativa, puoi contattarci telefonicamente o via email per assistenza.</p>
            </div>
            <div class="faq-item">
                <h3>Quali metodi di pagamento accettate?</h3>
                <p>Accettiamo tutti i principali metodi di pagamento, tra cui carte di credito (Visa, MasterCard,
                    American Express), PayPal e bonifici bancari.</p>
            </div>
            <div class="faq-item">
                <h3>È sicuro prenotare online?</h3>
                <p>Sì, la sicurezza dei tuoi dati è la nostra priorità. Utilizziamo tecnologie di crittografia avanzate
                    per proteggere le informazioni personali e di pagamento.</p>
            </div>
        </div>

        <div class="faq-section">
            <h2>Tour e Escursioni</h2>
            <div class="faq-item">
                <h3>I tour includono il trasporto?</h3>
                <p>Sì, la maggior parte dei nostri tour include il trasporto da e verso le destinazioni principali. I
                    dettagli specifici sono indicati nella descrizione di ogni tour.</p>
            </div>
            <div class="faq-item">
                <h3>Le escursioni sono adatte ai bambini?</h3>
                <p>Abbiamo una vasta gamma di tour adatti a famiglie con bambini. Ogni tour ha una sezione dedicata alle
                    informazioni sull'età minima consigliata e su eventuali restrizioni.</p>
            </div>
            <div class="faq-item">
                <h3>È possibile personalizzare un tour?</h3>
                <p>Assolutamente sì! Offriamo opzioni di tour personalizzati per soddisfare le tue esigenze specifiche.
                    Contattaci per discutere delle tue preferenze e organizzeremo un'esperienza su misura per te.</p>
            </div>
            <div class="faq-item">
                <h3>Cosa succede in caso di maltempo?</h3>
                <p>La sicurezza dei nostri clienti è la nostra priorità. In caso di condizioni meteorologiche avverse,
                    ci riserviamo il diritto di modificare o cancellare un tour. In tal caso, ti offriremo una data
                    alternativa o un rimborso completo.</p>
            </div>
        </div>

        <div class="faq-section">
            <h2>Prenotazioni e Cancellazioni</h2>
            <div class="faq-item">
                <h3>Posso modificare o cancellare la mia prenotazione?</h3>
                <p>Sì, puoi modificare o cancellare la tua prenotazione seguendo le nostre politiche di cancellazione.
                    Le condizioni specifiche sono indicate nella conferma della prenotazione. Generalmente, è possibile
                    cancellare senza penali fino a un certo numero di giorni prima della data del tour.</p>
            </div>
            <div class="faq-item">
                <h3>Cosa devo fare se devo cancellare all'ultimo minuto?</h3>
                <p>Contattaci immediatamente se devi cancellare la tua prenotazione all'ultimo minuto. Faremo del nostro
                    meglio per assisterti e offrirti soluzioni alternative.</p>
            </div>
            <div class="faq-item">
                <h3>Come posso ottenere un rimborso?</h3>
                <p>I rimborsi vengono elaborati secondo le nostre politiche di cancellazione. Dopo aver cancellato il
                    tour entro i termini previsti, il rimborso verrà effettuato sul metodo di pagamento originale entro
                    pochi giorni lavorativi.</p>
            </div>
        </div>

        <div class="faq-section">
            <h2>Contatti</h2>
            <div class="faq-item">
                <h3>Come posso contattare l'assistenza clienti?</h3>
                <p>Puoi contattare il nostro team di assistenza clienti tramite email all'indirizzo [email@example.com]
                    o telefonicamente al numero [numero di telefono]. Siamo disponibili dal lunedì al venerdì, dalle
                    9:00 alle 18:00.</p>
            </div>
            <div class="faq-item">
                <h3>Dove posso trovare ulteriori informazioni sui vostri tour?</h3>
                <p>Tutte le informazioni sui nostri tour sono disponibili sul nostro sito web. Visita la sezione "Tour"
                    per esplorare tutte le opzioni disponibili, leggere le descrizioni dettagliate e visualizzare le
                    immagini.</p>
            </div>
        </div> --}}


        <div class="container-fluid p-3 d-flex justify-content-center align-items-center flex-column">
            <x-show-content :pagine="$pagine" />
        </div>

        <x-contact-link />
        
        <div class="row">
            <div class="col-12 mt-5">
                <h2 class="text-center">{{ __('ui.title2') }}</h2>
                <x-services />
            </div>
            <div class="col-12 mt-5">
                <h2 class="text-center mb-3">{{ __('ui.title3') }}</h2>
                <x-excursions />
            </div>
        </div>
    </div>
</x-layout>
