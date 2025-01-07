<x-mail-layout>
    
        <p style="Margin:0;mso-line-height-rule:exactly;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:32px !important;letter-spacing:0;color:#333333;font-size:16px"
            class="es-text-mobile-size-16">
            Un nuovo messaggio è disponibile nella dashboard
        </p>
        <p style="Margin:0;mso-line-height-rule:exactly;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:32px !important;letter-spacing:0;color:#333333;font-size:16px"
            class="es-text-mobile-size-16">
            Anteprima messaggio:
        </p>
        <p style="Margin:0;mso-line-height-rule:exactly;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:32px !important;letter-spacing:0;color:#333333;font-size:16px"
            class="es-text-mobile-size-16">
            Nome cliente: {{ $contatto->nome }} {{ $contatto->cognome }}
        </p>
        <p style="Margin:0;mso-line-height-rule:exactly;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:32px !important;letter-spacing:0;color:#333333;font-size:16px"
            class="es-text-mobile-size-16">
            Tipo di servizio: {{ $contatto->servizio }}
        </p>
        <p style="Margin:0;mso-line-height-rule:exactly;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:32px !important;letter-spacing:0;color:#333333;font-size:16px"
            class="es-text-mobile-size-16">
            Messaggio: {{ $contatto->messaggio }}
        </p>
        <p style="Margin:0;mso-line-height-rule:exactly;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:32px !important;letter-spacing:0;color:#333333;font-size:16px"
            class="es-text-mobile-size-16">
            Contatti del cliente: <br>
            <a href="tel:{{ $contatto->telefono }}" style="color:#1d68b7;">{{ $contatto->telefono }}</a>
            <br>
            <a href="mailto:{{ $contatto->email }}" style="color:#1d68b7;">{{ $contatto->email }}</a>
        </p>
    
</x-mail-layout>
