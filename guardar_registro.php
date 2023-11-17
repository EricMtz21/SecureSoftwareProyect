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
$username = $_POST['username'];
$password = $_POST['password'];

if (preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO tabla_de_usuarios (email, username, password) VALUES ('$email', '$username', '$hashedPassword')";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.html");
        exit;
    } else {
        echo "Error al registrar: " . $conn->error;
    }
} else {
    echo "La contraseña no cumple con los requisitos. Debe tener al menos una mayúscula, una minúscula, un número, un carácter especial y tener al menos 8 caracteres.";
}

$conn->close();
