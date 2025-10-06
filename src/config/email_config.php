<?php
// Configuración de email para la Filá Mariscales

// Configuración del servidor SMTP (para uso con servidores de correo reales)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'edu300572@gmail.com');
define('SMTP_PASSWORD', 'granmaestre2024');
define('SMTP_ENCRYPTION', 'tls');

// Configuración de email de la filá
define('FILA_EMAIL', 'edu300572@gmail.com');
define('FILA_NAME', 'Filá Mariscales de Caballeros Templarios');
define('FILA_REPLY_TO', 'edu300572@gmail.com');

// Configuración para desarrollo local
define('DEVELOPMENT_MODE', false); // Activado para envío real

// Función para enviar email usando PHPMailer (si está disponible)
function enviarEmailConPHPMailer($destinatario, $asunto, $mensaje) {
    // Verificar si PHPMailer está disponible
    if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
        return false;
    }
    
    try {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        
        // Configuración del servidor
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_ENCRYPTION;
        $mail->Port = SMTP_PORT;
        $mail->CharSet = 'UTF-8';
        
        // Remitente
        $mail->setFrom(FILA_EMAIL, FILA_NAME);
        $mail->addReplyTo(FILA_REPLY_TO, FILA_NAME);
        
        // Destinatario
        $mail->addAddress($destinatario);
        
        // Contenido
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body = $mensaje;
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Error PHPMailer: " . $e->getMessage());
        return false;
    }
}

// Función para enviar email usando mail() nativo de PHP
function enviarEmailNativo($destinatario, $asunto, $mensaje) {
    $headers = [
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
        'From: ' . FILA_NAME . ' <' . FILA_EMAIL . '>',
        'Reply-To: ' . FILA_REPLY_TO,
        'X-Mailer: PHP/' . phpversion(),
        'X-Priority: 3'
    ];
    
    // Configurar parámetros adicionales para XAMPP
    $additional_parameters = '-f' . FILA_EMAIL;
    
    return mail($destinatario, $asunto, $mensaje, implode("\r\n", $headers), $additional_parameters);
}

// Función principal para enviar emails
function enviarEmail($destinatario, $asunto, $mensaje) {
    // En modo desarrollo, solo logear
    if (DEVELOPMENT_MODE) {
        error_log("Email simulado enviado a: $destinatario");
        error_log("Asunto: $asunto");
        return true;
    }
    
    // Intentar con PHPMailer primero
    if (enviarEmailConPHPMailer($destinatario, $asunto, $mensaje)) {
        return true;
    }
    
    // Fallback a mail() nativo
    return enviarEmailNativo($destinatario, $asunto, $mensaje);
}

// Función específica para enviar correo de bienvenida a usuarios creados por admin
function enviarCorreoBienvenidaUsuario($nombre, $apellidos, $email, $password, $rol) {
    $asunto = "¡Bienvenido a la Filá Mariscales de Caballeros Templarios!";
    
    // Determinar el tipo de usuario según el rol
    $tipoUsuario = '';
    $privilegios = '';
    switch($rol) {
        case 'admin':
            $tipoUsuario = 'Administrador';
            $privilegios = 'Acceso completo al panel de administración, gestión de usuarios, noticias y eventos.';
            break;
        case 'socio':
            $tipoUsuario = 'Socio';
            $privilegios = 'Acceso a contenido exclusivo y participación en eventos especiales.';
            break;
        default:
            $tipoUsuario = 'Miembro';
            $privilegios = 'Acceso a la información general y eventos de la filá.';
    }
    
    $mensaje = "
    <!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Bienvenido a la Filá Mariscales</title>
        <style>
            body { 
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
                line-height: 1.6; 
                color: #333; 
                margin: 0; 
                padding: 0; 
                background-color: #f4f4f4; 
            }
            .container { 
                max-width: 600px; 
                margin: 20px auto; 
                background: white; 
                border-radius: 10px; 
                overflow: hidden; 
                box-shadow: 0 4px 6px rgba(0,0,0,0.1); 
            }
            .header { 
                background: linear-gradient(135deg, #dc143c, #8b0000); 
                color: white; 
                padding: 40px 30px; 
                text-align: center; 
            }
            .logo { 
                font-size: 28px; 
                font-weight: bold; 
                margin-bottom: 10px; 
                text-shadow: 2px 2px 4px rgba(0,0,0,0.3); 
            }
            .title { 
                font-size: 24px; 
                margin: 0; 
                font-weight: 300; 
            }
            .subtitle { 
                font-size: 16px; 
                opacity: 0.9; 
                margin: 10px 0 0 0; 
            }
            .content { 
                padding: 40px 30px; 
            }
            .welcome-message { 
                background: #f8f9fa; 
                padding: 25px; 
                border-radius: 8px; 
                margin: 20px 0; 
                border-left: 4px solid #dc143c; 
            }
            .credentials { 
                background: #fff3cd; 
                border: 1px solid #ffeaa7; 
                padding: 20px; 
                border-radius: 8px; 
                margin: 20px 0; 
            }
            .credential-item { 
                margin: 10px 0; 
                font-family: 'Courier New', monospace; 
                background: white; 
                padding: 8px 12px; 
                border-radius: 4px; 
                border: 1px solid #ddd; 
            }
            .cta-button { 
                display: inline-block; 
                background: #dc143c; 
                color: white; 
                padding: 15px 30px; 
                text-decoration: none; 
                border-radius: 25px; 
                font-weight: bold; 
                margin: 20px 0; 
                transition: background 0.3s; 
            }
            .cta-button:hover { 
                background: #b71c1c; 
            }
            .footer { 
                text-align: center; 
                margin-top: 30px; 
                padding-top: 20px; 
                border-top: 1px solid #ddd; 
                color: #666; 
                font-size: 14px; 
            }
            .social-links { 
                margin: 20px 0; 
            }
            .social-links a { 
                display: inline-block; 
                margin: 0 10px; 
                color: #dc143c; 
                text-decoration: none; 
            }
            .warning { 
                background: #fff3cd; 
                border: 1px solid #ffeaa7; 
                color: #856404; 
                padding: 15px; 
                border-radius: 5px; 
                margin: 20px 0; 
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <div class='logo'>🛡️ FILÁ MARISCALES</div>
                <h1 class='title'>¡Bienvenido!</h1>
                <p class='subtitle'>Caballeros Templarios de Elche</p>
            </div>
            
            <div class='content'>
                <div class='welcome-message'>
                    <h2>¡Bienvenido a nuestra hermandad!</h2>
                    <p>Estimado/a <strong>$nombre $apellidos</strong>,</p>
                    <p>Nos complace informarte que has sido registrado como <strong>$tipoUsuario</strong> en la Filá Mariscales de Caballeros Templarios de Elche.</p>
                    <p><strong>Privilegios:</strong> $privilegios</p>
                </div>
                
                <div class='credentials'>
                    <h3>🔐 Tus datos de acceso:</h3>
                    <div class='credential-item'><strong>Email:</strong> $email</div>
                    <div class='credential-item'><strong>Contraseña:</strong> $password</div>
                    <div class='credential-item'><strong>Rol:</strong> $tipoUsuario</div>
                </div>
                
                <div class='warning'>
                    <strong>⚠️ Importante:</strong> Por seguridad, te recomendamos cambiar tu contraseña después del primer acceso.
                </div>
                
                <div style='text-align: center;'>
                    <a href='http://localhost/prueba-php/public/login' class='cta-button'>
                        Acceder a mi cuenta
                    </a>
                </div>
                
                <div class='social-links' style='text-align: center;'>
                    <p>Síguenos en nuestras redes sociales:</p>
                    <a href='#'>Facebook</a> |
                    <a href='#'>Instagram</a> |
                    <a href='#'>Twitter</a> |
                    <a href='#'>YouTube</a>
                </div>
                
                <div class='footer'>
                    <p>Este es un correo automático. Por favor, no respondas a este mensaje.</p>
                    <p>© 2024 Filá Mariscales de Caballeros Templarios de Elche</p>
                </div>
            </div>
        </div>
    </body>
    </html>";
    
    return enviarEmail($email, $asunto, $mensaje);
}
?>
