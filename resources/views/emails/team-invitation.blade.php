<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Invito al team</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 8px;">
        
        <h2 style="color: #333333;">Sei stato invitato! 🎉</h2>
        
        <p style="color: #666666;">
            Sei stato invitato a unirti al team <strong>{{ $teamName }}</strong> 
            come <strong>{{ $role }}</strong>.
        </p>

        <p style="color: #666666;">
            Clicca sul pulsante qui sotto per accettare l'invito e creare il tuo account.
            Il link scade tra <strong>7 giorni</strong>.
        </p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $link }}" 
               style="background-color: #4F46E5; color: #ffffff; padding: 12px 24px; 
                      border-radius: 6px; text-decoration: none; font-weight: bold;">
                Accetta l'invito
            </a>
        </div>

        <p style="color: #999999; font-size: 12px;">
            Se non riesci a cliccare il pulsante, copia e incolla questo link nel browser:
            <br>
            <a href="{{ $link }}" style="color: #4F46E5;">{{ $link }}</a>
        </p>

        <hr style="border: none; border-top: 1px solid #eeeeee; margin: 20px 0;">
        
        <p style="color: #999999; font-size: 12px; text-align: center;">
            Project Manager SaaS — Se non hai richiesto questo invito puoi ignorare questa email.
        </p>
    </div>
</body>
</html>