<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Summary</title>
</head>
<style>
    :root {
        --color-a: #0088ff;
        --color-b: #fcfafa;
        --color-c: #c8d3d5;
        --color-d: #00ac0e;
    }

    @page {
        margin: 0;
        /* Rimuove i margini della pagina */
    }

    .color-a {
        color: #0088ff;
    }

    .color-b {
        color: #fcfafa;
    }

    .text_col {
        color: blue;
    }

    body {
        font-family: Arial, Helvetica, sans-serif;
        margin: 5mm 5mm;
    }

    .cliente,
    .riepilogo {
        width: 48%;
        /* Imposta la larghezza al 48% per includere margini o padding */
        float: left;
        /* Usa float per posizionare i div uno accanto all'altro */
        box-sizing: border-box;
        /* Include padding e bordi nella larghezza totale */
        margin-right: 1%;
        margin-left: 1%
            /* Aggiungi margine tra i div */
    }

    .riepilogo {
        margin-right: 0;
        /* Rimuovi il margine destro dall'ultimo div */
    }

    .intestazione {
        padding: 10px;
        background-color: rgb(193, 191, 191);
        height: 150px;
        /* overflow: hidden; */
    }

    .logo-img {
        width: 20%;
        float: left;
        margin: 20px
    }


    .booking_number {
        text-align: center;
        float: left;
        width: 20%;
        padding-top: 20px;

    }

    .contact-info {
        float: right;
        text-align: center;
        width: 50%;
        padding-top: 20px;
    }

    .contact-info span {
        display: block;
    }

    /* .contact-info a {
        display: block;
    } */

    a {
        padding: 20px 10px;
    }

    .container {
        width: 100%;
    }

    .azienda {
        /* margin-top: 100px; */
        padding-top: 10px;
        /* border-top: 2px solid black; */
    }

    .page-break {
        page-break-before: always;
        /* margin-top: 20px; */
    }

    .faq {
        margin: 0px;
        padding: 0px;
    }

    .condizioni-transfer {
        font-size: 9px;
        padding: 0px;
        margin: 0px;
    }

    .clearfix::after {
        content: "";
        display: table;
        clear: both;
    }
</style>

<body>
    <div class="intestazione ">
        {{-- <img class="logo-img" src="{{ Storage::url($ownerdata->images->first()->path) }}" alt=""> --}}
        <img class="logo-img"
            src="https://tranchidatransfer.it/storage/images/cXXYvbUyhobu6FZQ8X6MX7NEfNkOoZNfMxPk27R4.png" alt="LOGO">
        <div class="booking_number">
            <span>Prenotazione numero: <span class="text_col">{{ $booking['id'] }}</span></span>
        </div>
        <div class="contact-info">
            <span>Chiamaci per info</span>
            <a href="tel:{{ $ownerdata->phone2 }}">{{ $ownerdata->phone2Name }}</a>
            <a href="tel:{{ $ownerdata->phone3 }}">{{ $ownerdata->phone3Name }}</a>
        </div>

    </div>
    <div class="clearfix"></div>
    <div class="container">
        <div class="cliente">
            <h3>Dati cliente</h3>
            <p><strong>Nome:</strong> <span class="text-primary">{{ $booking['name'] }}</span></p>
            <p><strong>Cognome:</strong> <span class="text-primary">{{ $booking['surname'] }}</span></p>
            <p><strong>Email:</strong> <span class="text-primary">{{ $booking['email'] }}</span></p>
            <p><strong>Telefono:</strong> <span class="text-primary"><a
                        href="tel:{{ $booking['phone'] }}">{{ $booking['phone'] }}</a></span></p>
        </div>

        <div class="riepilogo">

            <h3>Riepilogo Prenotazione</h3>

            <p><strong>Note:</strong> <span class="text-primary">{{ $booking['body'] }}</span></p>

            @if ($booking['bookingData']['type'] == 'transfer')
                <p>Tipo di servizio: <span class="text_col">{{ ucfirst($booking['bookingData']['type']) }}</span></p>
                <p>Transfer da: <span class="text_col">{{ $booking['bookingData']['departure_name'] ?? 'N/A' }}</span>
                    a: <span class="text_col">{{ $booking['bookingData']['arrival_name'] ?? 'N/A' }}</span></p>
                <p>Data: <span class="text_col">{{ $booking['bookingData']['date_departure'] ?? 'N/A' }}</span>
                    ore: <span class="text_col">{{ $booking['bookingData']['time_departure'] ?? 'N/A' }}</span></p>
                <p>durata: <span class="text_col">{{ $booking['bookingData']['duration'] ?? 'N/A' }}</span>
                    minuti circa</p>
                @if (!empty($booking['bookingData']['date_ret']))
                    <p>Ritorno il: <span class="text_col">{{ $booking['bookingData']['date_return'] }}</span>
                        ore <span class="text_col">{{ $booking['bookingData']['time_return'] }}</span>
                    </p>
                @endif
                <p>Passeggeri: <span class="text_col">{{ $booking['bookingData']['passengers'] ?? 'N/A' }}</span>
                </p>
            @elseif ($booking['bookingData']['type'] == 'escursione')
                <p>Tipo di servizio: <span class="text_col">{{ ucfirst($booking['bookingData']['type']) }}</span> a
                    <span class="text_col">{{ $booking['bookingData']['departure_name'] ?? 'N/A' }}</span>
                </p>
                <p>Data: <span class="text_col">{{ $booking['bookingData']['date_departure'] ?? 'N/A' }}</span>
                </p>
                <p>ore: <span class="text_col">{{ $booking['bookingData']['time_departure'] ?? 'N/A' }}</span>
                </p>
                <p>Durata: <span class="text_col">{{ $booking['bookingData']['duration'] ?? 'N/A' }}</span>
                    ore circa</p>
                <p>Passeggeri: <span class="text_col">{{ $booking['bookingData']['passengers'] ?? 'N/A' }}</span>
                </p>
            @elseif ($booking['bookingData']['type'] == 'noleggio')
                <p>Tipo di servizio: <span class="text_col">{{ ucfirst($booking['bookingData']['type']) }}</span> <span
                        class="text_col">{{ $booking['bookingData']['car_name'] ?? 'N/A' }}
                        {{ $booking['bookingData']['car_description'] ?? 'N/A' }}</span></p>
                <p>Data di ritiro: <span class="text_col">{{ $booking['bookingData']['date_start'] ?? 'N/A' }}</span>
                </p>
                <p>Data di consegna: <span class="text_col">{{ $booking['bookingData']['date_end'] ?? 'N/A' }}</span>
                </p>
            @endif

            <p>
                @if ($booking['bookingData']['price'] == $booking['bookingData']['original_price'])
                    <span>Prezzo</span>
                @else
                    <p>Prezzo originale: € {{ $booking['bookingData']['original_price'] ?? 'N/A' }}</p>
                    <span>Prezzo scontato</span>
                @endif
                : <span class="text_col">€ {{ $booking['bookingData']['price'] ?? 'N/A' }}</span>
            </p>

            <p>È richiesto un acconto del 30% dopo la conferma della prenotazione da parte del nostro staff</p>
        </div>
    </div>

    <div class="clearfix"></div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <div class="azienda">
        <span>{{ $ownerdata->companyName }}</span><br>
        <span>di {{ $ownerdata->name }} {{ $ownerdata->surname }}</span><br>
        <span>{{ $ownerdata->address }}</span><br>
        <span>{{ $ownerdata->city }}</span><br>
        <span>P.IVA: {{ $ownerdata->pIva }}</span><br>
        <span>C.F.: {{ $ownerdata->codFisc }}</span><br>
    </div>
    <div class="page-break"></div> <!-- Forza l'interruzione di pagina -->

    <div class="faq">
        <div class="condizioni-transfer">
            <p>Condizioni transfer</p>
            <p>Per offrirvi un servizio sempre migliore, vi invitiamo ad osservare scrupolosamente i seguenti consigli e
                condizioni di utilizzo dei nostri servizi:</p>
            <p>All’arrivo in aeroporto troverete un nostro autista ad aspettarvi all’uscita dal ritiro bagagli con un
                cartello con stampato il nostro logo "{{ $ownerdata->companyName }}" o il Vostro nominativo per farsi
                riconoscere; nel
                caso in cui non riuscite a localizzare chiamate al numero <span
                    class="segnaposto">{{ $ownerdata->phone2 }} {{ $ownerdata->phone3 }}</span>.</p>
            <p>È sempre importante, non appena arrivati in aeroporto e non appena possibile, accendere il cellulare per
                facilitare i contatti, nel caso ce ne fosse bisogno, al numero di cellulare che avete fornito al momento
                della prenotazione.</p>
            <p>Comunicare l’orario di arrivo riportato sul biglietto, provenienza, numero di volo e aeroporto di arrivo.
                Comunicare tempestivamente qualora ci fossero ritardi.</p>
            <p>Oltre la prima ora da quella di inizio servizio prevista all’atto della prenotazione e confermata da
                telefonata o e-mail, per esempio per ritardo aereo o smarrimento bagagli, è previsto un supplemento alla
                tariffa pari a € 10,00 per ogni ora extra. La medesima tariffa pari a € 10,00 è prevista nei casi di
                soste in musei, ristoranti, centri commerciali e comunque in tutti i casi in cui sia prevista una
                continuità del servizio dopo una sosta richiesta dal cliente. Qualora tale sosta risultasse inferiore ad
                un’ora, ma superiore a 20 minuti, vige la tariffa oraria minima di € 10,00.</p>
            <p>Per i servizi di trasferimento FULL DAY oltre le 24 ore fuori sede, saranno a carico del cliente, vitto,
                alloggio dell’autista ed eventuali parcheggi.</p>
            <p>Notturno: si applica per tutti i servizi compresi tra le 22,00 e le 06,00 un supplemento del 20% oltre la
                normale tariffa.</p>
            <p>Festivi: quali, Natale, Capodanno, Pasqua e Ferragosto, verrà applicato sempre un supplemento del 20%
                oltre la normale tariffa.</p>
            <p>Trasporto bagagli: è previsto il trasporto al seguito di ogni passeggero di un bagaglio di peso non
                superiore a Kg. 25. Per ogni bagaglio extra è previsto un supplemento di € 5,00 oltre la normale tariffa
                del servizio; prevista anche la richiesta di un seggiolino per trasporto bambini con un supplemento di €
                10,00 a tratta. I bambini sotto 3 anni non pagano, ma il posto occupato si intende anche quello occupato
                dal seggiolino. Animali: sono ammessi al trasporto sui mezzi animali di piccola taglia non superiori a
                Kg. 10, purché sistemati in appositi trasportini (€ 10,00 a tratta). Per il trasporto di animali di peso
                superiore richiedere tariffe e disponibilità, solo per il servizio transfer esclusivo.</p>
            <p>È assolutamente vietato sui mezzi consumare alimenti, bevande e fumare, invitando i signori clienti a
                fare attenzione alle biricchinate dei propri bambini; eventuali danni causati potranno essere oggetto di
                richiesta di risarcimento.</p>
            <p>Non sono da considerarsi disdette le comunicazioni via sms in quanto non sufficientemente sicure ad
                arrivare con puntualità al destinatario. Non verranno prese in considerazione chiamate con numero
                privato.</p>
            <p>A prenotazione avvenuta la {{ $ownerdata->companyName }}, un giorno prima di norma invia al cliente sia
                in partenza che in
                arrivo, un sms o un messaggio con whatsapp dando istruzioni per l’accoglienza in aeroporto in caso di
                arrivo o nel caso di partenza come promemoria.</p>
            <p>Vi ricordiamo che la disdetta della prenotazione va fatta almeno 72 ore prima; in caso contrario
                comporterà l’addebito totale del servizio richiesto. Il pagamento del servizio prenotato avverrà
                direttamente al nostro autista in loco.</p>
            <p>Vogliate controllare attentamente, prima dell’invio della prenotazione, che i dati inviati siano
                corretti, così come quando riceverete una risposta di conferma. Per qualsiasi chiarimento chiamare i
                numeri <span class="segnaposto">{{ $ownerdata->phone1 }} {{ $ownerdata->phone2 }}</span> o - E-mail:
                <span class="segnaposto">{{ $ownerdata->email }}</span>. Vi consigliamo di portare sempre dietro la
                nostra e-mail di
                conferma del servizio. Per le richieste di prenotazione fatte 24/48 ore prima, si prega di telefonare
                per accertarvi che la richiesta sia stata presa in carico dopo aver mandato e-mail, onde evitare che
                arriviate in aeroporto e non trovate nessuno.
            </p>
            <p>Per i servizi richiesti è previsto un acconto del 30% come conferma prenotazione da pagare
                anticipatamente con payPal o bonifico bancario, successivamente saldare ai nostri autisti in contanti.
                La {{ $ownerdata->companyName }} non è tenuta a contattare il passeggero, se non a discrezione. Per i
                servizi richiesti di
                andata e ritorno, si richiede all’atto un anticipo del 30% del servizio di ritorno.</p>
            <p>La {{ $ownerdata->companyName }} può avvalersi della collaborazione in partnership di società
                strettamente di fiducia con
                tutti i requisiti come per legge, standard di sicurezza e professionali con tutte le autorizzazioni
                necessarie regolamentate nel trasporto di persone:</p>
            <p>Tale situazione si potrebbe verificare in un periodo di intenso traffico di lavoro o dovuto a cause non
                previste come avarie, incidenti dei mezzi o qualunque volta lo ritenessimo necessario per offrire un
                servizio migliore, qualitativo e soprattutto senza ritardi ed interruzioni.</p>
            <p>Importanti e riepilogo:</p>
            <ol>
                <li>Comunicare sempre la presenza di animali domestici</li>
                <li>Comunicare sempre il numero esatto di persone</li>
                <li>Comunicare l’eventuale possesso di attrezzature subacquee o materiale pericoloso</li>
                <li>Comunicare tempestivamente ritardi alla partenza</li>
                <li>Accendere i telefonini all’arrivo in aeroporto per essere rintracciati più velocemente</li>
                <li>All’uscita dall’aeroporto troverete l’autista con un cartello con scritto il nostro logo
                    "{{ $ownerdata->companyName }}" o il Vostro nome.</li>
                <li>Sui mezzi è severamente vietato fumare, sporcare e consumare cibi e bevande</li>
            </ol>
            <p>Condizioni di Noleggio Auto</p>
            <p>Per garantire la migliore esperienza con i nostri servizi, vi chiediamo di osservare attentamente le
                seguenti condizioni di noleggio:</p>

            <p>Assicuratevi di portare con voi la patente di guida valida, una carta di credito e una copia della
                conferma della prenotazione. Il mancato possesso di questi documenti potrebbe comportare la
                cancellazione della prenotazione.</p>

            <p>Il periodo di noleggio inizia all'orario concordato durante la prenotazione. Qualsiasi ritardo nella
                restituzione del veicolo comporterà addebiti aggiuntivi. La tariffa oraria per i ritardi è di €15,00.
                Per ritardi superiori a tre ore, verrà addebitata la tariffa completa di un giorno di noleggio.</p>

            <p>Il veicolo deve essere restituito con lo stesso livello di carburante presente al momento del ritiro. Se
                il livello di carburante è inferiore, verrà applicata una tassa di rifornimento di €20,00 più il costo
                del carburante.</p>

            <p>Tutte le multe per infrazioni stradali, i biglietti per il parcheggio e i pedaggi sostenuti durante il
                periodo di noleggio sono a carico del locatario. In caso di danni al veicolo, potrebbe essere applicata
                una franchigia fino a €500,00, a seconda della copertura assicurativa selezionata durante la
                prenotazione.</p>

            <p>Per i noleggi superiori a 7 giorni, viene fornito un check-up di manutenzione del veicolo gratuito. Si
                prega di contattarci per programmare il check-up almeno 24 ore prima.</p>

            <p>Attrezzature aggiuntive come dispositivi GPS, seggiolini per bambini o portapacchi possono essere
                noleggiati a un costo aggiuntivo. Si prega di richiedere questi articoli al momento della prenotazione.
            </p>

            <p>È severamente vietato fumare e consumare cibi o bevande all'interno del veicolo. Una tassa di pulizia di
                €100,00 verrà addebitata per qualsiasi violazione di questa regola.</p>
            <p>Il presente contratto sarà disciplinato dalla legge italiana; le parti sono ben consapevoli delle norme
                regolatrici; in caso di controversie sarà competente il Foro di Trapani.</p>
            <p><strong>INFORMATIVA SULLA PRIVACY:</strong></p>
            <p>Desideriamo informarLa che la legge n.675/1996 in materia di protezione dei dati personali, prevede la
                tutela delle persone e di altri soggetti rispetto al trattamento dei dati personali, secondo la legge
                indicata, tale trattamento sarà improntato ai principi di correttezza, lealtà, trasparenza e della
                tutela della Sua riservatezza e dei Suoi diritti.</p>
            <p>PER INFORMARLA CHE AI SENSI DELL’ARTICOLO 10 DELLA PREDETTA LEGGE QUANTO SEGUE:</p>
            <ol>
                <li>I dati da Lei forniti verranno trattati solamente per poter InviarLe tutte le documentazioni e tutti
                    i chiarimenti da Lei richiesti</li>
                <li>Adempimenti di legge fiscali, contabili e amministrativi</li>
                <li>Gestioni archivi e corrispondenza</li>
                <li>I dati non saranno comunicati ad altri soggetti, ma al solo scopo solamente per finalità del nostro
                    rapporto</li>
                <li>Il conferimento dei dati è facoltativo, ma se dovesse esserci negato dallo stesso soggetto, non ci
                    consentirà di fornirle i servizi da Lei richiesti.</li>
                <li>Il titolare del trattamento dati è la scrivente ditta: <span
                        class="segnaposto">{{ $ownerdata->companyName }}</span> <span
                        class="segnaposto">{{ $ownerdata->address }} {{ $ownerdata->city }} {{ $ownerdata->codFisc }}
                        {{ $ownerdata->pIva }}</span></li>
                <li>Elaborazioni e statistiche interne</li>
                <li>Invio offerte clienti attivi e potenziali</li>
                <li>Per regolamentare tutte le norme previste per legge in materia civilistica, fiscale e
                    amministrative.</li>
            </ol>
        </div>
    </div>

</body>

</html>
