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
$name = $_POST['firstname'];
$lastname = $_POST['lastname'];

$sql = "SELECT email from tabla_de_usuarios";
$result = $conn->query($sql);

if (preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {

    if ($result) {
        if ($result->num_rows > 0) {
            $encontrado = false;

            while ($row = $result->fetch_assoc()) {
                $aux_email = $row['email'];
                if ($email == $aux_email) {
                    $encontrado = true;
                }
            }

            if ($encontrado == true) {
                echo 'Ya existe una cuenta con este correo.';
            } else {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                $sql = "INSERT INTO tabla_de_usuarios (email, username, password, name, lastname) VALUES ('$email', '$username', '$hashedPassword', '$name', '$lastname')";

                if ($conn->query($sql) === TRUE) {
                    header("Location: index.html");
                    exit;
                } else {
                    echo "Error al registrar: " . $conn->error;
                }
            }
        } else {
            echo "No hay resultados.";
        }
    } else {
        // Manejar el caso en que la consulta no sea exitosa
        echo "Error en la consulta: " . $conn->error;
    }
} else {
    echo "La contraseña no cumple con los requisitos. Debe tener al menos una mayúscula, una minúscula, un número, un carácter especial y tener al menos 8 caracteres.";
}


$conn->close();
