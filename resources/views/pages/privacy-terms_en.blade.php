<x-layout>
    <x-seo-data :seoTitle="$seoTitle" :seoDescription="$seoDescription" />
    <div class="container bg-white rounded p-3 mb-5">
        <h2 id="privacy">Privacy Policy</h2>
        <p>We would like to inform you that Law No. 675/1996 on the protection of personal data provides for the
            protection
            of individuals and other subjects with respect to the processing of personal data. According to the
            aforementioned
            law, this processing will be based on the principles of correctness, fairness, transparency, and the
            protection of
            your privacy and rights.</p>
        <p>TO INFORM YOU THAT UNDER ARTICLE 10 OF THE ABOVE LAW, THE FOLLOWING APPLIES:</p>
        <ul>
            <li>The data you provide will be processed only to send you all the documentation and clarifications you
                have
                requested.</li>
            <li>Legal, fiscal, accounting, and administrative obligations.</li>
            <li>Management of archives and correspondence.</li>
            <li>The data will not be communicated to other subjects, except for the purpose of our relationship.</li>
            <li>Providing the data is optional, but if it is denied, we will not be able to provide the services you
                requested.</li>
            <li>The data controller is the undersigned company: <strong>{{ $ownerdata->companyName }}</strong> of
                {{ $ownerdata->name }} {{ $ownerdata->surname }}, {{ $ownerdata->address }}, {{ $ownerdata->city }},
                Tax Code {{ $ownerdata->codFisc }}, VAT {{ $ownerdata->pIva }}.</li>
            <li>Internal processing and statistics.</li>
            <li>Sending offers to active and potential clients.</li>
            <li>To regulate all legal, fiscal, and administrative norms.</li>
        </ul>

        <h2 id="terms">Terms and Conditions</h2>
        <p>To provide you with a better service, we invite you to carefully observe the following advice and terms of
            use
            of our services:</p>
        <ul>
            <li>Upon arrival at the airport, you will find one of our drivers waiting for you at the baggage claim exit
                with a
                sign with our logo "<strong>{{ $ownerdata->companyName }}</strong>" or your name for identification;
                if you cannot locate them, call <a href="tel:{{ $ownerdata->phone2 }}">{{ $ownerdata->phone2 }}</a> <a
                    href="tel:{{ $ownerdata->phone3 }}">{{ $ownerdata->phone3 }}</a>.</li>
            <li>It is always important, as soon as you arrive at the airport and as soon as possible, to turn on your
                mobile
                phone to facilitate contact if needed, at the mobile number you provided at the time of booking.</li>
            <li>Communicate the arrival time shown on the ticket, origin, flight number, and arrival airport.</li>
            <li>Notify us promptly if there are any delays.</li>
            <li>After the first hour from the start of the service as scheduled at the time of booking and confirmed by
                phone or email, for example, due to flight delays or lost luggage, a surcharge of €10.00 for each extra
                hour
                will apply. The same €10.00 rate applies to stops at museums, restaurants, shopping centers, and any
                other
                cases where service continuity is required after a stop requested by the client. If the stop is less
                than an
                hour but more than 20 minutes, the minimum hourly rate of €10.00 applies.</li>
            <li>For FULL DAY transfer services beyond 24 hours away, the client will be responsible for the driver's
                meals,
                accommodation, and any parking fees.</li>
            <li>Nighttime: A 20% surcharge applies for all services between 10:00 PM and 6:00 AM in addition to the
                regular
                fare.</li>
            <li>Holidays: For holidays such as Christmas, New Year’s, Easter, and August 15, a 20% surcharge will always
                be
                applied in addition to the regular fare.</li>
            <li>Luggage transport: Each passenger is allowed to carry one piece of luggage weighing no more than 25 kg.
                For each extra piece of luggage, a surcharge of €5.00 will apply in addition to the regular service
                fare;
                a child seat request incurs an additional €10.00 per trip. Children under 3 years old travel for free,
                but
                the seat occupied by the child seat is considered as well.</li>
            <li>Animals: Small pets up to 10 kg are allowed if they are in appropriate carriers (€10.00 per trip). For
                pets
                weighing more, request rates and availability, only for exclusive transfer services.</li>
            <li>It is strictly prohibited to consume food, drinks, and smoke on the vehicles; customers are advised to
                monitor their children’s behavior; any damage caused may be subject to a claim for damages.</li>
            <li>SMS communications are not considered cancellations as they are not reliably delivered to the recipient.
            </li>
            <li>Calls from private numbers will not be considered.</li>
            <li>Upon booking, {{ $ownerdata->companyName }} usually sends a message via SMS or WhatsApp to the client
                one day before departure, both for departures and arrivals, providing instructions for airport reception
                or
                as a reminder for departure.</li>
            <li>Cancellation of the booking must be made at least 72 hours in advance; otherwise, the total cost of the
                requested service will be charged.</li>
            <li>Payment for the booked service will be made directly to our driver on-site.</li>
            <li>Please carefully check that the data submitted is correct before sending the booking request and when
                you
                receive a confirmation response.</li>
            <li>For any clarification, call <a href="tel:{{ $ownerdata->phone2 }}">{{ $ownerdata->phone2 }}</a> <a
                    href="tel:{{ $ownerdata->phone3 }}">{{ $ownerdata->phone3 }}</a> or - Email: <a
                    href="mailto:{{ $ownerdata->email }}">{{ $ownerdata->email }}</a></li>
            <li>We advise you to always carry our booking confirmation email. For bookings made 24/48 hours in advance,
                please call to ensure that the request has been received after sending the email, to avoid arriving at
                the
                airport and finding no one there.</li>
            <li>A deposit of 30% is required as a booking confirmation to be paid in advance via PayPal or bank
                transfer,
                with the remaining balance to be settled in cash with our drivers.</li>
            <li>{{ $ownerdata->companyName }} is not required to contact the passenger, except at its discretion. For
                round-trip services, a 30% deposit of the return service is required at the time of booking.</li>
            <li>{{ $ownerdata->companyName }} may use the services of trusted partner companies with all necessary
                legal,
                safety, and professional standards and authorizations for passenger transport.</li>
            <li>This situation may occur during periods of high workload or due to unforeseen circumstances such as
                vehicle breakdowns or accidents, or whenever we deem it necessary to offer a better, qualitative service
                without delays or interruptions.</li>
        </ul>
        <h3>Important and Summary:</h3>
        <ul>
            <li>Always communicate the presence of pets.</li>
            <li>Always communicate the exact number of people.</li>
            <li>Communicate if you have underwater equipment or hazardous materials.</li>
            <li>Promptly communicate delays in departure.</li>
            <li>Turn on your phones upon arrival at the airport to be contacted more quickly.</li>
            <li>At the airport exit, you will find the driver with a sign displaying our logo
                "{{ $ownerdata->companyName }}"
                or your name.</li>
            <li>Smoking, littering, and consuming food and drinks are strictly prohibited on the vehicles.</li>
        </ul>
        <p>This contract will be governed by Italian law; the parties are well aware of the regulatory norms; in case of
            disputes, the Court of Trapani will have jurisdiction.</p>
    </div>
</x-layout>
