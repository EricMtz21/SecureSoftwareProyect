<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "secure_software";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La conexión a la base de datos falló: " . $conn->connect_error);
}

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM tabla_de_usuarios WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) {
        session_start();
        $_SESSION['usuario_id'] = $row['id'];
        $token = bin2hex(random_bytes(6));

        date_default_timezone_set('America/Mexico_City');
        $fechaActual = date('Y-m-d H:i:s');
        $fecha_caducidad = date('Y-m-d H:i:s', strtotime($fechaActual . ' + 15 minutes'));
        date_default_timezone_set('UTC');

        $sql = "UPDATE tabla_de_usuarios SET token = '$token', caducidad = '$fecha_caducidad' WHERE email = '$email'";
        $result = $conn->query($sql);

        $correoEmisor = "a20310032@ceti.mx";
        $nombreEmisor = "SecureSoftware";
        $destinatario = $email;

        $sql = "SELECT username FROM tabla_de_usuarios WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $nombreDestinatario = $row['username'];
        } else {
            $nombreDestinatario = null;
        }

        $asunto = "Código de autenticación";
        $cuerpo = '
        <html>
            <head>
            <title>Autenticación de 2 pasos</title>
            </head>
            <body>
                <h1> Bienvenid@ a SecureSoftware, ' . $nombreDestinatario . '</h1>
                <p>
                    <b>Este es un correo automático, no hace falta que respondas al mismo.</b>
                    Este es tu código de autenticación, recuerda que expira en 15 minutos.
                    ' . $token . '
                </p>
            </body>
        </html>
        ';
        $aviso = '<div class="mensaje">Estimad@ ' . $nombreDestinatario . ', gracias por tu preferencia.';

        include('../SecureSoftwareProyect/_gmail.php');

        header("Location: tfa.html");
        exit;
    } else {
        echo "Contraseña incorrecta";
    }
} else {
    echo "Usuario no encontrado";
}

$conn->close();
