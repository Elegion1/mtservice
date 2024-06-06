<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Summary</title>
</head>
<body>
    <h1>Dati cliente</h1>
    <p><strong>Nome:</strong> {{ $booking['name'] }}</p>
    <p><strong>Cognome:</strong> {{ $booking['surname'] }}</p>
    <p><strong>Email:</strong> {{ $booking['email'] }}</p>
    <p><strong>Telefono:</strong> {{ $booking['phone'] }}</p>
    <p><strong>Note:</strong> {{ $booking['body'] }}</p>

    @if ($booking['bookingData']['type'] == 'transfer')
                <h1>Riepilogo Prenotazione</h1>

                <p>Tipologia:
                    <span class="text-primary"> {{ ucfirst($booking['bookingData']['type']) }} </span>
                </p>

                <p>
                    Da:
                    <span class="text-primary"> {{ $booking['bookingData']['departure_name'] ?? 'N/A' }} </span>
                    A: <span class="text-primary"> {{ $booking['bookingData']['arrival_name'] ?? 'N/A' }} </span>
                </p>

                <p>
                    Data:
                    <span class="text-primary"> {{ $booking['bookingData']['date_departure'] ?? 'N/A' }} </span>
                    ore: <span class="text-primary"> {{ $booking['bookingData']['time_departure'] ?? 'N/A' }} </span>
                </p>
                <p>
                    Durata:
                    <span class="text-primary"> {{ $booking['bookingData']['duration'] ?? 'N/A' }} </span> Minuti circa
                </p>

                @if (!empty($booking['bookingData']['date_ret']))
                    <p>
                        Ritorno:
                        <span class="text-primary"> {{ $booking['bookingData']['date_return'] }} </span>
                        ore <span class="text-primary"> {{ $booking['bookingData']['time_return'] }} </span>
                    </p>
                @endif
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
                <h1>Riepilogo Prenotazione</h1>

                <p>Tipologia:
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

                <p>Passeggeri:
                    <span class="text-primary"> {{ $booking['bookingData']['passengers'] ?? 'N/A' }} </span>
                </p>
                <p>
                    Prezzo Totale:
                    <span class="text-primary"> {{ $booking['bookingData']['price'] ?? 'N/A' }} €</span>
                </p>
            @endif
            @if ($booking['bookingData']['type'] == 'noleggio')
                <h1>Riepilogo Prenotazione</h1>

                <p>Tipologia:
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
</body>
</html>
