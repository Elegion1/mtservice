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
        --color-a: #ff3e00;
        --color-b: #9a9d98;
        --color-c: #e7e8ee;
    }

    .color-a {
        color: #ff3e00;
    }

    .color-b {
        color: #9a9d98;
    }

    .text-primary {
        color: blue
    }

    body {
        font-family: Arial, Helvetica, sans-serif
    }

    .cliente {
        margin-bottom: 50px;
    }

    .riepilogo {
        border-left: 2px solid black;
        padding-left: 30px;
    }

    .logo-img {
        width: 20vw;
        max-width: 200px;
    }

    .intestazione {
        padding: 10px;
        background-color: rgb(193, 191, 191);
        display: flex;
        justify-content: space-around;
    }

    a {
        padding: 20px 10px;
    }

    .links {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .container {
        display: flex;
        justify-content: space-around;

    }

    .azienda {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        margin-top: 100px;
        padding-top: 10px;
        border-top: 2px solid black;
    }

    .faq-section {
        padding: 20px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .faq-item {
        margin-bottom: 15px;
    }

    .faq-item h3 {
        margin-bottom: 5px;
        font-size: 18px;
        color: var(--color-a);
    }
</style>

<body>
    <div class="intestazione ">
        <img class="logo-img" src="{{ Storage::url($ownerdata->images->first()->path) }}" alt="">
        <div>
            <p>Prenotazione numero: <span class="text-primary">{{$booking['id']}}</span></p>
        </div>
        <div class="links">
            <p>Chiamaci per info</p>
            <a href="tel:{{ $ownerdata->phone2 }}">Giuseppe</a>
            <a href="tel:{{ $ownerdata->phone3 }}">Maurizio</a>
        </div>
    </div>

    <div class="container">
        <div class="cliente">
            <h1>Dati cliente</h1>
            <p><strong>Nome:</strong> <span class="text-primary">{{ $booking['name'] }}</span></p>
            <p><strong>Cognome:</strong> <span class="text-primary">{{ $booking['surname'] }}</span></p>
            <p><strong>Email:</strong> <span class="text-primary">{{ $booking['email'] }}</span></p>
            <p><strong>Telefono:</strong> <span class="text-primary"><a
                        href="tel:{{ $booking['phone'] }}">{{ $booking['phone'] }}</a></span></p>
        </div>
        <div class="riepilogo">

            <h1>Riepilogo Prenotazione</h1>

            <p><strong>Note:</strong> <span class="text-primary">{{ $booking['body'] }}</span></p>

            @if ($booking['bookingData']['type'] == 'transfer')

                <p>Tipologia:
                    <span class="text-primary"> {{ ucfirst($booking['bookingData']['type']) }} </span>
                </p>

                <p>
                    Da:
                    <span class="text-primary"> {{ $booking['bookingData']['departure_name'] ?? 'N/A' }} </span>
                    A: <span class="text-primary"> {{ $booking['bookingData']['arrival_name'] ?? 'N/A' }} </span>
                </p>

                <p>
                    Andata:
                    <span class="text-primary"> {{ $booking['bookingData']['date_departure'] ?? 'N/A' }} </span>
                    ore: <span class="text-primary"> {{ $booking['bookingData']['time_departure'] ?? 'N/A' }} </span>
                </p>

                @if (!empty($booking['bookingData']['date_ret']))
                    <p>
                        Ritorno:
                        <span class="text-primary"> {{ $booking['bookingData']['date_return'] }} </span>
                        ore <span class="text-primary"> {{ $booking['bookingData']['time_return'] }} </span>
                    </p>
                @endif

                <p>
                    Durata:
                    <span class="text-primary"> {{ $booking['bookingData']['duration'] ?? 'N/A' }} </span>
                    Minuti circa
                </p>

                <p>
                    Passeggeri:
                    <span class="text-primary"> {{ $booking['bookingData']['passengers'] ?? 'N/A' }} </span>
                </p>
                <p>
                    Prezzo Totale:
                    <span class="text-primary"> {{ $booking['bookingData']['price'] ?? 'N/A' }} </span> €
                </p>


            @endif

            @if ($booking['bookingData']['type'] == 'escursione')
                <p>
                    Tipologia:
                    <span class="text-primary"> {{ ucfirst($booking['bookingData']['type']) }} </span>
                    a
                    <span class="text-primary"> {{ $booking['bookingData']['departure_name'] ?? 'N/A' }} </span>
                </p>

                <p>
                    Data:
                    <span class="text-primary"> {{ $booking['bookingData']['date_departure'] ?? 'N/A' }} </span>
                    ore:
                    <span class="text-primary"> {{ $booking['bookingData']['time_departure'] ?? 'N/A' }} </span>
                </p>

                <p>
                    Passeggeri:
                    <span class="text-primary"> {{ $booking['bookingData']['passengers'] ?? 'N/A' }} </span>
                </p>

                <p>
                    Prezzo Totale:
                    <span class="text-primary"> {{ $booking['bookingData']['price'] ?? 'N/A' }} €</span>
                </p>
            @endif

            @if ($booking['bookingData']['type'] == 'noleggio')
                <p>
                    Tipologia:
                    <span class="text-primary"> {{ ucfirst($booking['bookingData']['type']) }} </span>

                    <span class="text-primary"> {{ $booking['bookingData']['car_name'] ?? 'N/A' }}
                        {{ $booking['bookingData']['car_description'] ?? 'N/A' }} </span>
                </p>

                <p>
                    Data di ritiro:
                    <span class="text-primary"> {{ $booking['bookingData']['date_start'] ?? 'N/A' }} </span>
                    data di consegna:
                    <span class="text-primary"> {{ $booking['bookingData']['date_end'] ?? 'N/A' }} </span>
                </p>

                <p>
                    Quantità:
                    <span class="text-primary"> {{ $booking['bookingData']['quantity'] ?? 'N/A' }} </span>
                    Prezzo Totale:
                    <span class="text-primary"> {{ $booking['bookingData']['price'] ?? 'N/A' }} €</span>
                </p>
            @endif
        </div>
    </div>

    <div>
        <h1>Domande Frequenti (FAQ)</h1>

        <div class="faq-section">
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
        </div>
    </div>

    <div class="azienda">
        <span>{{ $ownerdata->companyName }}</span>
        <span>di {{ $ownerdata->name }} {{ $ownerdata->surname }}</span>
        <span>{{ $ownerdata->address }}</span>
        <span>{{ $ownerdata->city }}</span>
        <span>P.IVA: {{ $ownerdata->pIva }}</span>
        <span>C.F.: {{ $ownerdata->codFisc }}</span>
    </div>



</body>

</html>
