<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\OAuth;
use PHPMailer\PHPMailer\SMTP;
use League\OAuth2\Client\Provider\Google;

date_default_timezone_set('Etc/UTC');

require '../SecureSoftwareProyect/vendor/autoload.php';

$mail = new PHPMailer();
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->Port = 465;
$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
$mail->SMTPAuth = true;
$mail->AuthType = 'XOAUTH2';

$email = $correoEmisor;
$clientId = '515273024146-44keinf9r1iiq24ldti00diklm8hlko8.apps.googleusercontent.com';
$clientSecret = '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';
$refreshToken = '1//0f0fiws9HZdpoCgYIARAAGA8SNwF-L9Irdb4DAjdn5IPXwCHyEPDgEvvu8hJJ3S3oOGbYzbJvZTp73lYXdcnmnueXMTw6mxbCSX0';

$provider = new Google(
    [
        'clientId' => $clientId,
        'clientSecret' => $clientSecret,
    ]
);
$mail->setOAuth(
    new OAuth(
        [
            'provider' => $provider,
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'refreshToken' => $refreshToken,
            'userName' => $email,
        ]
    )
);

$mail->setFrom($email, $nombreEmisor);
$mail->addAddress($destinatario, $nombreDestinatario);
$mail->Subject = $asunto;
$mail->CharSet = PHPMailer::CHARSET_UTF8;
$mail->Body = $cuerpo;
$mail->AltBody = 'This is a plain-text message body';
// POR ALGUNA RAZON TIENE PROBLEMA CON MI CERTIFICADO SSL
$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);

if (!$mail->send()) {
    echo 'Error al enviar el correo: ' . $mail->ErrorInfo;
} else {
    echo 'Correo enviado con éxito.';
}
