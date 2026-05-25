<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Progetto Completato</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 8px;">
        
        <h2 style="color: #28a745;">🎉 Il tuo progetto è stato completato!</h2>
        
        <p style="color: #666666;">
            Ciao <strong>{{ $project->client->name ?? 'Cliente' }}</strong>,
        </p>

        <p style="color: #666666;">
            Siamo lieti di informarti che il progetto <strong>"{{ $projectName }}"</strong> 
            è stato completato con successo dal nostro team!
        </p>

        <div style="background-color: #f0fff4; border-left: 4px solid #28a745; padding: 15px; margin: 20px 0; border-radius: 4px;">
            <p style="margin: 0; color: #666666;">
                ✅ Progetto: <strong>{{ $projectName }}</strong><br>
                📅 Completato il: <strong>{{ now()->format('d/m/Y') }}</strong>
            </p>
        </div>

        <p style="color: #666666;">
            Puoi visualizzare il progetto accedendo al pannello.
        </p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url('/admin/dashboard') }}" 
               style="background-color: #28a745; color: #ffffff; padding: 12px 24px; 
                      border-radius: 6px; text-decoration: none; font-weight: bold;">
                Vai al pannello
            </a>
        </div>

        <hr style="border: none; border-top: 1px solid #eeeeee; margin: 20px 0;">
        
        <p style="color: #999999; font-size: 12px; text-align: center;">
            Project Manager SaaS — Grazie per aver scelto i nostri servizi.
        </p>
    </div>
</body>
</html>