<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Aggiornamento Richiesta</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 8px;">
        
        <h2 style="color: #dc3545;">❌ Aggiornamento sulla tua richiesta</h2>
        
        <p style="color: #666666;">
            Ciao <strong>{{ $clientName }}</strong>,
        </p>

        <p style="color: #666666;">
            Purtroppo la tua richiesta <strong>"{{ $projectName }}"</strong> 
            non può essere accettata al momento.
        </p>

        <div style="background-color: #fff5f5; border-left: 4px solid #dc3545; padding: 15px; margin: 20px 0; border-radius: 4px;">
            <p style="margin: 0; color: #666666;">
                💬 Motivazione: <strong>{{ $rejectionReason }}</strong>
            </p>
        </div>

        <p style="color: #666666;">
            Puoi inviare una nuova richiesta in qualsiasi momento accedendo al pannello.
        </p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url('/admin/dashboard') }}" 
               style="background-color: #4F46E5; color: #ffffff; padding: 12px 24px; 
                      border-radius: 6px; text-decoration: none; font-weight: bold;">
                Vai al pannello
            </a>
        </div>

        <hr style="border: none; border-top: 1px solid #eeeeee; margin: 20px 0;">
        
        <p style="color: #999999; font-size: 12px; text-align: center;">
            Project Manager SaaS — Siamo a tua disposizione per qualsiasi domanda.
        </p>
    </div>
</body>
</html>