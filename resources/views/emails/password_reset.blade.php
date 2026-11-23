<!DOCTYPE html>
<html>
<head>
    <title>Recuperación de Contraseña</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f4f4; padding: 40px 20px; margin: 0;">
    <div style="background-color: #ffffff; padding: 40px; border-radius: 12px; max-width: 500px; margin: 0 auto; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-top: 5px solid #1e3a8a;">
        <h2 style="color: #1e3a8a; margin-top: 0; font-size: 24px;">UniSpace</h2>
        <h3 style="color: #334155; font-size: 20px; font-weight: normal; margin-bottom: 20px;">Recuperación de Contraseña</h3>
        
        <p style="color: #475569; font-size: 16px; line-height: 1.5; margin-bottom: 30px;">
            Hemos recibido una solicitud para restablecer la contraseña de tu cuenta en la plataforma de reservas UniSpace. Haz clic en el siguiente botón para elegir una nueva contraseña:
        </p>
        
        <a href="{{ route('password.reset', $token) }}?email={{ urlencode($email) }}" style="display: inline-block; padding: 14px 28px; background-color: #3b82f6; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 16px;">
            Restablecer Contraseña
        </a>
        
        <p style="color: #94a3b8; font-size: 14px; margin-top: 40px; line-height: 1.4;">
            Si no solicitaste este cambio, puedes ignorar este correo de forma segura. Tu contraseña no cambiará hasta que accedas al enlace de arriba y crees una nueva.
        </p>
        
        <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 30px 0;">
        <p style="color: #cbd5e1; font-size: 12px; margin: 0;">
            &copy; {{ date('Y') }} Universidad Marítima del Caribe - UniSpace.
        </p>
    </div>
</body>
</html>
