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
            <span>Booking Number: <span class="text_col">{{ $booking['code'] }}</span></span>
        </div>
        <div class="contact-info">
            <span>Call us for info</span>
            <a href="tel:{{ $ownerdata->phone2 }}">{{ $ownerdata->phone2Name }}</a>
            <a href="tel:{{ $ownerdata->phone3 }}">{{ $ownerdata->phone3Name }}</a>
        </div>

    </div>
    <div class="clearfix"></div>
    <div class="container">


        <div class="cliente">
            <h3>Customer</h3>
            <p><strong>Name:</strong> <span class="text-primary">{{ $booking['name'] }}</span></p>
            <p><strong>Surname:</strong> <span class="text-primary">{{ $booking['surname'] }}</span></p>
            <p><strong>Email:</strong> <span class="text-primary">{{ $booking['email'] }}</span></p>
            <p><strong>Phone:</strong> <span class="text-primary"><a
                        href="tel:{{ $booking['phone'] }}">{{ $booking['phone'] }}</a></span></p>
            <p>
                <a
                    href="{{ route('booking.status', ['locale' => 'it', 'code' => $booking['code'] ?? 'default_code', 'email' => $booking['email'] ?? 'default_email']) }}">
                    Verify the status of your booking here
                </a>
            </p>
        </div>

        <div class="riepilogo">

            <h3>Booking summary</h3>

            <p><strong>Notes:</strong> <span class="text-primary">{{ $booking['body'] }}</span></p>

            @if ($booking['bookingData']['type'] == 'transfer')
                <p>Type of service: <span class="text_col">{{ ucfirst($booking['bookingData']['type']) }}</span></p>
                <p>Transfer from: <span
                        class="text_col">{{ $booking['bookingData']['departure_name'] ?? 'N/A' }}</span>
                    to: <span class="text_col">{{ $booking['bookingData']['arrival_name'] ?? 'N/A' }}</span></p>
                <p>Date: <span
                        class="text_col">{{ \Carbon\Carbon::parse($booking['bookingData']['date_dep'])->translatedFormat('d/m/Y H:i') ?? 'N/A' }}</span>
                </p>
                <p>Duration: <span class="text_col">{{ $booking['bookingData']['duration'] ?? 'N/A' }}</span>
                    min approx</p>
                @if (!empty($booking['bookingData']['date_ret']))
                    <p>Return date: <span
                            class="text_col">{{ \Carbon\Carbon::parse($booking['bookingData']['date_ret'])->translatedFormat('d/m/Y H:i') }}</span>

                    </p>
                @endif
                <p>{{ __('ui.passengers') }}: <span
                        class="text_col">{{ $booking['bookingData']['passengers'] ?? 'N/A' }}</span>
                </p>
            @elseif ($booking['bookingData']['type'] == 'escursione')
                <p>Type of service: <span class="text_col">{{ ucfirst($booking['bookingData']['type']) }}</span> a
                    <span class="text_col">{{ $booking['bookingData']['departure_name'] ?? 'N/A' }}</span>
                </p>
                <p>Date: <span
                        class="text_col">{{ \Carbon\Carbon::parse($booking['bookingData']['date_dep'])->translatedFormat('d/m/Y H:i') ?? 'N/A' }}</span>
                </p>
                <p>Duration: <span class="text_col">{{ $booking['bookingData']['duration'] ?? 'N/A' }}</span>
                    hours approx</p>
                <p>Passengers: <span class="text_col">{{ $booking['bookingData']['passengers'] ?? 'N/A' }}</span>
                </p>
            @elseif ($booking['bookingData']['type'] == 'noleggio')
                <p>Type of service: <span class="text_col">{{ ucfirst($booking['bookingData']['type']) }}</span> <span
                        class="text_col">{{ $booking['bookingData']['car_name'] ?? 'N/A' }}
                        {{ $booking['bookingData']['car_description'] ?? 'N/A' }}</span></p>
                <p>Collection date: <span
                        class="text_col">{{ \Carbon\Carbon::parse($booking['bookingData']['date_start'])->translatedFormat('d/m/Y H:i') ?? 'N/A' }}</span>
                </p>
                <p>Return date: <span
                        class="text_col">{{ \Carbon\Carbon::parse($booking['bookingData']['date_end'])->translatedFormat('d/m/Y H:i') ?? 'N/A' }}</span>
                </p>
            @endif

            <p>
                @if ($booking['bookingData']['price'] == $booking['bookingData']['original_price'])
                    <span>Price</span>
                @else
                    <p>Original price: € {{ $booking['bookingData']['original_price'] ?? 'N/A' }}</p>
                    <span>Discounted price</span>
                @endif
                : <span class="text_col">€ {{ $booking['bookingData']['price'] ?? 'N/A' }}</span>
            </p>

            <p>A 30% deposit is required after confirmation of the booking by our staff</p>
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
            <p>Transfer Conditions</p>
            <p>To offer you an ever better service, we invite you to strictly observe the following tips and conditions
                for using our services:</p>
            <p>Upon arrival at the airport, you will find our driver waiting for you at the baggage claim exit with a
                sign displaying either our logo "{{ $ownerdata->companyName }}" or your name to be recognized; if you
                cannot locate the driver, please call the number <span class="segnaposto">{{ $ownerdata->phone2 }}
                    {{ $ownerdata->phone3 }}</span>.</p>
            <p>It is always important, as soon as you arrive at the airport and as soon as possible, to turn on your
                mobile phone to facilitate contact, if needed, at the mobile number you provided at the time of booking.
            </p>
            <p>Please communicate the arrival time indicated on the ticket, origin, flight number, and arrival airport.
                Notify us promptly in case of delays.</p>
            <p>Beyond the first hour from the start of the service as scheduled at the time of booking and confirmed by
                phone or email, for example, due to a flight delay or lost luggage, a supplement of €10.00 will be
                applied for each extra hour. The same fee of €10.00 is applied in cases of stops at museums,
                restaurants, shopping centers, and in all cases where service continuity is required after a stop
                requested by the client. If the stop is less than an hour but more than 20 minutes, the minimum hourly
                rate of €10.00 applies.</p>
            <p>For FULL DAY transfer services over 24 hours away from the base, the client will be responsible for the
                driver’s meals, accommodation, and any parking fees.</p>
            <p>Night service: A 20% surcharge will be applied to all services between 22:00 and 06:00, in addition to
                the regular rate.</p>
            <p>Holidays: On Christmas, New Year's, Easter, and Ferragosto, a 20% surcharge will always be applied in
                addition to the regular rate.</p>
            <p>Luggage transport: Each passenger is allowed to carry one piece of luggage weighing no more than 25 kg.
                For each additional piece of luggage, a supplement of €5.00 will be applied in addition to the regular
                service rate; a request for a child seat also incurs a supplement of €10.00 per trip. Children under 3
                years old do not pay, but the seat occupied also includes the seat occupied by the child seat. Animals:
                Small animals weighing no more than 10 kg are allowed on board as long as they are placed in appropriate
                carriers (€10.00 per trip). For the transport of animals weighing more than 10 kg, please inquire about
                rates and availability, only for exclusive transfer services.</p>
            <p>It is strictly forbidden to consume food, beverages, and smoke on the vehicles, and we kindly ask clients
                to ensure their children do not cause any damage; any damage caused may be subject to compensation
                claims.</p>
            <p>Cancellation notifications via SMS are not considered valid as they are not reliably delivered to the
                recipient on time. Calls with a private number will not be accepted.</p>
            <p>Once your booking is confirmed, {{ $ownerdata->companyName }} will typically send the client a message
                via SMS or WhatsApp the day before, providing instructions for airport pickup or as a reminder for
                departure.</p>
            <p>Please note that cancellations must be made at least 72 hours in advance; otherwise, the full service fee
                will be charged. Payment for the booked service will be made directly to our driver on-site.</p>
            <p>Please check carefully before sending the booking that the details provided are correct, as well as when
                you receive a confirmation response. For any clarifications, call the numbers <span
                    class="segnaposto">{{ $ownerdata->phone1 }} {{ $ownerdata->phone2 }}</span> or email <span
                    class="segnaposto">{{ $ownerdata->email }}</span>. We recommend always carrying our service
                confirmation email with you. For booking requests made 24/48 hours in advance, please call to ensure
                that your request has been received after sending the email, to avoid arriving at the airport and
                finding no one there.</p>
            <p>For requested services, a 30% deposit is required to confirm the booking, to be paid in advance via
                PayPal or bank transfer, with the balance to be paid in cash to our drivers.
                {{ $ownerdata->companyName }} is not obligated to contact the passenger unless at its discretion. For
                round-trip services, a 30% advance payment is required at the time of booking for the return service.
            </p>
            <p>{{ $ownerdata->companyName }} may collaborate in partnership with highly trusted companies that meet all
                legal requirements, safety standards, and professional regulations for the transportation of people.</p>
            <p>This situation may occur during periods of high traffic or due to unforeseen events such as breakdowns,
                vehicle accidents, or whenever we deem it necessary to offer a better, quality service without delays or
                interruptions.</p>
            <p>Important Reminders:</p>
            <ol>
                <li>Always inform us if you are traveling with pets.</li>
                <li>Always communicate the exact number of people.</li>
                <li>Inform us if you have scuba diving equipment or hazardous materials.</li>
                <li>Promptly notify us of any departure delays.</li>
                <li>Turn on your mobile phones upon arrival at the airport to be contacted more quickly.</li>
                <li>At the airport exit, you will find the driver with a sign displaying our logo
                    "{{ $ownerdata->companyName }}" or your name.</li>
                <li>Smoking, littering, and consuming food and drinks are strictly prohibited on board.</li>
            </ol>
            <p>Car Rental Terms and Conditions</p>
            <p>To ensure the best experience with our services, we ask that you carefully observe the following rental
                terms and conditions:</p>

            <p>Please ensure you bring your valid driver’s license, credit card, and a copy of your booking
                confirmation. Failure to provide these documents may result in the cancellation of your booking.</p>

            <p>The rental period starts at the time agreed upon during booking. Any delay in returning the vehicle will
                result in additional charges. The hourly rate for late returns is €15.00. For delays exceeding three
                hours, a full day’s rental rate will be charged.</p>

            <p>The vehicle must be returned with the same fuel level as when picked up. If the fuel level is lower, a
                refueling charge of €20.00 plus the cost of fuel will be applied.</p>

            <p>All traffic fines, parking tickets, and toll charges incurred during the rental period are the
                responsibility of the renter. In the event of any damage to the vehicle, an excess fee of up to €500.00
                may apply, depending on the insurance coverage selected during booking.</p>

            <p>For rentals exceeding 7 days, a free vehicle maintenance check-up is provided. Please contact us to
                schedule the check-up at least 24 hours in advance.</p>

            <p>Additional equipment such as GPS devices, child seats, or roof racks can be rented for an additional fee.
                Please request these items at the time of booking.</p>

            <p>Smoking and the consumption of food or beverages are strictly prohibited inside the vehicle. A cleaning
                fee of €100.00 will be charged for any violations of this rule.</p>

            <p>This contract will be governed by Italian law; both parties are well aware of the governing rules; in the
                event of disputes, the court of jurisdiction will be in Trapani.</p>
            <p><strong>PRIVACY NOTICE:</strong></p>
            <p>We wish to inform you that Law No. 675/1996 on the protection of personal data provides for the
                protection of individuals and other subjects regarding the processing of personal data. According to the
                law, such processing will be based on principles of fairness, legality, transparency, and the protection
                of your privacy and rights.</p>
            <p>WE INFORM YOU, ACCORDING TO ARTICLE 10 OF THE ABOVE-MENTIONED LAW, AS FOLLOWS:</p>
            <ol>
                <li>The data you provide will be processed solely to send you all the documentation and clarifications
                    you request.</li>
                <li>Compliance with legal, fiscal, accounting, and administrative obligations.</li>
                <li>Archive and correspondence management.</li>
                <li>The data will not be disclosed to other parties but only used for the purposes of our relationship.
                </li>
                <li>Providing data is optional, but if refused, it will not allow us to provide you with the requested
                    services.</li>
                <li>The data controller is the company: <span class="segnaposto">{{ $ownerdata->companyName }}</span>
                    <span class="segnaposto">{{ $ownerdata->address }} {{ $ownerdata->city }}
                        {{ $ownerdata->codFisc }} {{ $ownerdata->pIva }}</span>
                </li>
                <li>Internal processing and statistics.</li>
                <li>Sending offers to active and potential clients.</li>
                <li>To regulate all legal provisions in civil, fiscal, and administrative matters.</li>
            </ol>

        </div>
    </div>

</body>

</html>
