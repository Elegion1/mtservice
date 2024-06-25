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
        margin-top:100px; 
        padding-top: 10px;
        border-top: 2px solid black; 
    }
</style>

<body>
    <div class="intestazione ">
        <img class="logo-img" src="{{ Storage::url($ownerdata->images->first()->path) }}" alt="">
        <div class="links">
            <p>Chiamaci per info</p>
            <a href="tel:{{ $ownerdata->phone2 }}">{{ $ownerdata->phone2 }}</a>
            <a href="tel:{{ $ownerdata->phone3 }}">{{ $ownerdata->phone3 }}</a>
        </div>
    </div>

    <div class="container">
        <div class="cliente">
            <h1>Dati cliente</h1>
            <p><strong>Nome:</strong> <span class="text-primary">{{ $booking['name'] }}</span></p>
            <p><strong>Cognome:</strong> <span class="text-primary">{{ $booking['surname'] }}</span></p>
            <p><strong>Email:</strong> <span class="text-primary">{{ $booking['email'] }}</span></p>
            <p><strong>Telefono:</strong> <span class="text-primary"><a href="tel:{{ $booking['phone'] }}">{{ $booking['phone'] }}</a></span></p>
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
