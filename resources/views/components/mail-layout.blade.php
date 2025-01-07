<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; margin: 0; padding: 0;">
    <table width="100%" cellspacing="0" cellpadding="0"
        style="background-color: #ffffff; max-width: 600px; margin: auto; border: 1px solid #dddddd;">
        <tr>
            <td style="padding: 20px; text-align: left;">
                <!-- Contatti -->
                <div style="font-size: 14px; line-height: 1.5; color: #555555;">
                    <strong>{!! $ownerdata->siteName !!}</strong><br>
                    {{ $ownerdata->email }}<br>
                    {{ $ownerdata->phone }}
                </div>
            </td>
            <td style="padding: 20px; text-align: right;">
                <!-- Logo -->
                <img src="{{ Storage::url($ownerdata->images->first()->path) }}" alt="Logo" style="max-height: 50px;">
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding: 20px;">
                <!-- Contenuto -->
                <div style="font-size: 16px; line-height: 1.6; color: #333333;">
                    {{$slot}}
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding: 20px; background-color: #f2f2f2;">
                <!-- Footer -->
                <div style="font-size: 14px; line-height: 1.5; color: #555555; text-align: left;">
                    <strong>{{$ownerdata->companyName}}</strong><br>
                    {{$ownerdata->address}}<br>
                    Tel: {{$ownerdata->phone}}<br>
                    Email: {{$ownerdata->email}}
                </div>
            </td>
        </tr>
    </table>
</body>

</html>
