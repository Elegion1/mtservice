<x-mail-layout>

    <tr>
        <td colspan="2" style="padding: 20px;">
            <!-- Saluto -->
            <p
                style="Margin:0; font-family: helvetica, 'helvetica neue', arial, verdana, sans-serif; 
                      line-height: 1.6; letter-spacing: 0; color: #333333; font-size: 16px;">
                {{ __('ui.dearCustomer') }},
            </p>

            <!-- Messaggio -->
            <p
                style="Margin: 10px 0; font-family: helvetica, 'helvetica neue', arial, verdana, sans-serif; 
                      line-height: 1.6; letter-spacing: 0; color: #333333; font-size: 16px;">
                {{ __('ui.messageSent') }}
            </p>
            <p
                style="Margin: 10px 0; font-family: helvetica, 'helvetica neue', arial, verdana, sans-serif; 
                      line-height: 1.6; letter-spacing: 0; color: #333333; font-size: 16px;">
                {{ __('ui.contactSoon') }}.
            </p>

            <!-- Ringraziamenti -->
            <p
                style="Margin: 10px 0; font-family: helvetica, 'helvetica neue', arial, verdana, sans-serif; 
                      line-height: 1.6; letter-spacing: 0; color: #333333; font-size: 16px;">
                {{ __('ui.thxForChoosing') }}
            </p>
        </td>
    </tr>

</x-mail-layout>
