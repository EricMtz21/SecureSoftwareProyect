<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "secure_software";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La conexión a la base de datos falló: " . $conn->connect_error);
}

$code = $_POST['code'];

session_start();

if (isset($_SESSION['usuario_id'])) {
    $usuario_id = $_SESSION['usuario_id'];

    $sql = "SELECT * FROM tabla_de_usuarios WHERE id = '$usuario_id'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $usuario = $result->fetch_assoc();

        $token = $usuario['token'];
        $caducidad_token = $usuario['caducidad'];

        date_default_timezone_set('America/Mexico_City');
        $fechaActual = date('Y-m-d H:i:s');

        if ($code == $token && $fechaActual < $caducidad_token) {
            header("Location: inicio.html");
        } else {
            echo 'Codigo incorrecto';
        }


        
        date_default_timezone_set('UTC');
    } else {
        echo "Usuario no encontrado en la base de datos.";
    }
} else {

    echo "El usuario no ha iniciado sesión.";
}
