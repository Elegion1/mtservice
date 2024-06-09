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

    .color-a{
        color: #ff3e00 ;
    }

    .color-b{
        color: #9a9d98 ;
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
        border-top: 2px solid black;
        padding-top: 10px;
    }
</style>

<body>
    <div class="intestazione">
        <h2><strong><span class="color-a">TRANCHIDA</span></strong></h2>
        <p><small class="color-b">TRANSFER & RENT</small></p>

    </div>
    <div class="cliente">
        <h1>Dati cliente</h1>
        <p><strong>Nome:</strong> <span class="text-primary">{{ $booking['name'] }}</span></p>
        <p><strong>Cognome:</strong> <span class="text-primary">{{ $booking['surname'] }}</span></p>
        <p><strong>Email:</strong> <span class="text-primary">{{ $booking['email'] }}</span></p>
        <p><strong>Telefono:</strong> <span class="text-primary">{{ $booking['phone'] }}</span></p>
    </div>

    <div class="riepilogo">

        <h2>Riepilogo Prenotazione</h2>

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

</body>

</html>
