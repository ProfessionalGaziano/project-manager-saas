<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Nuovo Progetto Assegnato</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 8px;">
        
        <h2 style="color: #4F46E5;">📋 Ti è stato assegnato un nuovo progetto!</h2>
        
        <p style="color: #666666;">
            Ciao <strong>{{ $managerName }}</strong>,
        </p>

        <p style="color: #666666;">
            Ti è stato assegnato il progetto <strong>"{{ $projectName }}"</strong>. 
            Puoi iniziare a gestirlo accedendo al pannello.
        </p>

        <div style="background-color: #f8f9fa; border-left: 4px solid #4F46E5; padding: 15px; margin: 20px 0; border-radius: 4px;">
            <p style="margin: 0 0 8px 0; color: #666666;">
                📁 Progetto: <strong>{{ $projectName }}</strong>
            </p>
            @if($deadline)
            <p style="margin: 0; color: #666666;">
                📅 Scadenza: <strong>{{ $deadline }}</strong>
            </p>
            @endif
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url('/admin/project') }}" 
               style="background-color: #4F46E5; color: #ffffff; padding: 12px 24px; 
                      border-radius: 6px; text-decoration: none; font-weight: bold;">
                Vai ai progetti
            </a>
        </div>

        <hr style="border: none; border-top: 1px solid #eeeeee; margin: 20px 0;">
        
        <p style="color: #999999; font-size: 12px; text-align: center;">
            Project Manager SaaS
        </p>
    </div>
</body>
</html>