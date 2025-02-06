<x-layout>
    <div class="container bg-white rounded p-3 mb-5">
        <h2 id="privacy">Informativa sulla Privacy</h2>
        <p>Desideriamo informarLa che la legge n.675/1996 in materia di protezione dei dati personali, prevede la tutela
            delle persone e di altri soggetti rispetto al trattamento dei dati personali. Secondo la legge indicata,
            tale
            trattamento sarà improntato ai principi di correttezza, lealtà, trasparenza e della tutela della Sua
            riservatezza e dei Suoi diritti.</p>
        <p>PER INFORMARLA CHE AI SENSI DELL’ARTICOLO 10 DELLA PREDETTA LEGGE QUANTO SEGUE:</p>
        <ul>
            <li>I dati da Lei forniti verranno trattati solamente per poter inviarLe tutte le documentazioni e tutti i
                chiarimenti da Lei richiesti.</li>
            <li>Adempimenti di legge fiscali, contabili e amministrativi.</li>
            <li>Gestioni archivi e corrispondenza.</li>
            <li>I dati non saranno comunicati ad altri soggetti, ma al solo scopo di finalità del nostro rapporto.</li>
            <li>Il conferimento dei dati è facoltativo, ma se dovesse esserci negato dallo stesso soggetto, non ci
                consentirà di fornirle i servizi da Lei richiesti.</li>
            <li>Il titolare del trattamento dati è la scrivente ditta: <strong>{{ $ownerdata->companyName }}</strong> di
                {{ $ownerdata->name }} {{ $ownerdata->surname }}, {{ $ownerdata->address }}, {{ $ownerdata->city }},
                C.F.
                {{ $ownerdata->codFisc }}, P.IVA {{ $ownerdata->pIva }}.</li>
            <li>Elaborazioni e statistiche interne.</li>
            <li>Invio offerte clienti attivi e potenziali.</li>
            <li>Per regolamentare tutte le norme previste per legge in materia civilistica, fiscale e amministrative.
            </li>
        </ul>

        <h2 id="terms">Termini e Condizioni</h2>
        <p>Per offrirvi un servizio sempre migliore, vi invitiamo ad osservare scrupolosamente i seguenti consigli e
            condizioni di utilizzo dei nostri servizi:</p>
        <ul>
            <li>All’arrivo in aeroporto troverete un nostro autista ad aspettarvi all’uscita dal ritiro bagagli con un
                cartello con stampato il nostro logo "<strong>{{ $ownerdata->companyName }}</strong>" o il Vostro
                nominativo per farsi
                riconoscere; nel caso in cui non riuscite a localizzare chiamate al numero <a
                    href="tel:{{ $ownerdata->phone2 }}">{{ $ownerdata->phone2 }}</a> <a
                    href="tel:{{ $ownerdata->phone3 }}">{{ $ownerdata->phone3 }}</a>.</li>
            <li>È sempre importante, non appena arrivati in aeroporto e non appena possibile, accendere il cellulare per
                facilitare i contatti, nel caso ce ne fosse bisogno, al numero di cellulare che avete fornito al momento
                della prenotazione.</li>
            <li>Comunicare l’orario di arrivo riportato sul biglietto, provenienza, numero di volo e aeroporto di
                arrivo.</li>
            <li>Comunicare tempestivamente qualora ci fossero ritardi.</li>
            <li>Oltre la prima ora da quella di inizio servizio prevista all’atto della prenotazione e confermata da
                telefonata o e-mail, per esempio per ritardo aereo o smarrimento bagagli, è previsto un supplemento alla
                tariffa pari a € 10,00 per ogni ora extra. La medesima tariffa pari a € 10,00 è prevista nei casi di
                soste in musei, ristoranti, centri commerciali e comunque in tutti i casi in cui sia prevista una
                continuità del servizio dopo una sosta richiesta dal cliente. Qualora tale sosta risultasse inferiore ad
                un’ora, ma superiore a 20 minuti, vige la tariffa oraria minima di € 10,00.</li>
            <li>Per i servizi di trasferimento FULL DAY oltre le 24 ore fuori sede, saranno a carico del cliente, vitto,
                alloggio dell’autista ed eventuali parcheggi.</li>
            <li>Notturno: si applica per tutti i servizi compresi tra le 22:00 e le 06:00 un supplemento del 20% oltre
                la normale tariffa.</li>
            <li>Festivi: quali, Natale, Capodanno, Pasqua e Ferragosto, verrà applicato sempre un supplemento del 20%
                oltre la normale tariffa.</li>
            <li>Trasporto bagagli: è previsto il trasporto al seguito di ogni passeggero di un bagaglio di peso non
                superiore a Kg. 25. Per ogni bagaglio extra è previsto un supplemento di € 5,00 oltre la normale tariffa
                del servizio; prevista anche la richiesta di un seggiolino per trasporto bambini con un supplemento di €
                10,00 a tratta. I bambini sotto 3 anni non pagano, ma il posto occupato si intende anche quello occupato
                dal seggiolino.</li>
            <li>Animali: sono ammessi al trasporto sui mezzi animali di piccola taglia non superiori a Kg. 10, purché
                sistemati in appositi trasportini (€ 10,00 a tratta). Per il trasporto di animali di peso superiore
                richiedere tariffe e disponibilità, solo per il servizio transfer esclusivo.</li>
            <li>È assolutamente vietato sui mezzi consumare alimenti, bevande e fumare, invitando i signori clienti a
                fare attenzione alle biricchinate dei propri bambini; eventuali danni causati potranno essere oggetto di
                richiesta di risarcimento.</li>
            <li>Non sono da considerarsi disdette le comunicazioni via sms in quanto non sufficientemente sicure ad
                arrivare con puntualità al destinatario.</li>
            <li>Non verranno prese in considerazione chiamate con numero privato.</li>
            <li>A prenotazione avvenuta la {{ $ownerdata->companyName }}, un giorno prima di norma invia al cliente sia
                in partenza che in arrivo, un sms o un messaggio con WhatsApp dando istruzioni per l’accoglienza in
                aeroporto in caso di arrivo o nel caso di partenza come promemoria.</li>
            <li>Vi ricordiamo che la disdetta della prenotazione va fatta almeno 72 ore prima; in caso contrario
                comporterà l’addebito totale del servizio richiesto.</li>
            <li>Il pagamento del servizio prenotato avverrà direttamente al nostro autista in loco.</li>
            <li>Vogliate controllare attentamente, prima dell’invio della prenotazione, che i dati inviati siano
                corretti, così come quando riceverete una risposta di conferma.</li>
            <li>Per qualsiasi chiarimento chiamare i numeri <a
                    href="tel:{{ $ownerdata->phone2 }}">{{ $ownerdata->phone2 }}</a> <a
                    href="tel:{{ $ownerdata->phone3 }}">{{ $ownerdata->phone3 }}</a> o -
                E-mail: <a href="mailto:{{ $ownerdata->email }}">{{ $ownerdata->email }}</a></li>
            <li>Vi consigliamo di portare sempre dietro la nostra e-mail di conferma del servizio. Per le richieste di
                prenotazione fatte 24/48 ore prima, si prega di telefonare per accertarvi che la richiesta sia stata
                presa in carico dopo aver mandato e-mail, onde evitare che arriviate in aeroporto e non trovate nessuno.
            </li>
            <li>Per i servizi richiesti è previsto un acconto del 30% come conferma prenotazione da pagare
                anticipatamente con PayPal o bonifico bancario, successivamente saldare ai nostri autisti in contanti.
            </li>
            <li>La {{ $ownerdata->companyName }} non è tenuta a contattare il passeggero, se non a discrezione. Per i
                servizi richiesti di andata e ritorno, si richiede all’atto un anticipo del 30% del servizio di ritorno.
            </li>
            <li>La {{ $ownerdata->companyName }} può avvalersi della collaborazione in partnership di società
                strettamente di fiducia con tutti i requisiti come per legge, standard di sicurezza e professionali con
                tutte le autorizzazioni necessarie regolamentate nel trasporto di persone:</li>
            <li>Tale situazione si potrebbe verificare in un periodo di intenso traffico di lavoro o dovuto a cause non
                previste come avarie, incidenti dei mezzi o qualunque volta lo ritenessimo necessario per offrire un
                servizio migliore, qualitativo e soprattutto senza ritardi ed interruzioni.</li>
        </ul>
        <h3>Importanti e riepilogo:</h3>
        <ul>
            <li>Comunicare sempre la presenza di animali domestici.</li>
            <li>Comunicare sempre il numero esatto di persone.</li>
            <li>Comunicare l’eventuale possesso di attrezzature subacquee o materiale pericoloso.</li>
            <li>Comunicare tempestivamente ritardi alla partenza.</li>
            <li>Accendere i telefonini all’arrivo in aeroporto per essere rintracciati più velocemente.</li>
            <li>All’uscita dall’aeroporto troverete l’autista con un cartello con scritto il nostro logo
                "{{ $ownerdata->companyName }}" o il Vostro nome.</li>
            <li>Sui mezzi è severamente vietato fumare, sporcare e consumare cibi e bevande.</li>
        </ul>
        <p>Il presente contratto sarà disciplinato dalla legge italiana; le parti sono ben consapevoli delle norme
            regolatrici; in caso di controversie sarà competente il Foro di Trapani.</p>

    </div>


</x-layout>
