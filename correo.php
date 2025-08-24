<?php
// correo.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

$destinatario = "jafetconerod@gmail.com";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = isset($_POST['nombre']) ? strip_tags($_POST['nombre']) : '';
    $email = isset($_POST['email']) ? strip_tags($_POST['email']) : '';
    $telefono = isset($_POST['telefono']) ? strip_tags($_POST['telefono']) : '';
    $mensaje = isset($_POST['mensaje']) ? strip_tags($_POST['mensaje']) : '';

    // Validar datos básicos
    if (empty($nombre) || empty($email) || empty($mensaje) || empty($telefono)) {
        mostrarRespuesta("Error en los datos", "Todos los campos son obligatorios.", false);
    }

    $asunto = "Nuevo mensaje de contacto";
    $cuerpoHTML = "
        <h3>Nuevo mensaje desde el formulario</h3>
        <p><b>Nombre:</b> {$nombre}</p>
        <p><b>Email:</b> {$email}</p>
        <p><b>Teléfono:</b> {$telefono}</p>
        <p><b>Mensaje:</b><br>{$mensaje}</p>
    ";
    $cuerpoPlano = "Nombre: $nombre\nEmail: $email\nTeléfono: $telefono\nMensaje: $mensaje";

    $mail = new PHPMailer(true);

    try {
        // Configuración SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'jafetconerod@gmail.com';
        $mail->Password = 'ldae inwt bghs nysr';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Configurar remitente y destinatario
        $mail->setFrom($email, $nombre);
        $mail->addAddress($destinatario, 'Administrador');

        // Contenido
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body = $cuerpoHTML;
        $mail->AltBody = $cuerpoPlano;

        $mail->send();
        mostrarRespuesta("¡Mensaje enviado!", "Tu mensaje fue enviado correctamente. Serás redirigido en unos segundos.", true);

    } catch (Exception $e) {
        mostrarRespuesta("Error al enviar el correo", "Hubo un error al enviar el correo: {$mail->ErrorInfo}", false);
    }

} else {
    header("Location: index.php#contacto");
    exit();
}


// Función para mostrar mensaje
function mostrarRespuesta($titulo, $mensaje, $exito = true)
{
    $colorBorde = $exito ? "#0a0" : "#e00";
    $colorFondo = $exito ? "#f0fff0" : "#fff0f0";
    $colorTexto = $exito ? "#080" : "#a00";
    $colorBtn = $exito ? "#080" : "#e00";

    echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Respuesta</title>';
    echo '<meta http-equiv="refresh" content="3;url=index.php#contacto">';
    echo '</head><body>';
    echo "<div style='margin:50px auto;max-width:400px;padding:30px;border:2px solid {$colorBorde};background:{$colorFondo};color:{$colorTexto};text-align:center;font-family:sans-serif;'>";
    echo "<h2>{$titulo}</h2>";
    echo "<p>{$mensaje}</p>";
    echo "<button onclick=\"window.location.href='index.php#contacto'\" style='padding:10px 20px;background:{$colorBtn};color:#fff;border:none;border-radius:4px;cursor:pointer;'>Volver</button>";
    echo '</div>';
    echo '</body></html>';
    exit();
}

