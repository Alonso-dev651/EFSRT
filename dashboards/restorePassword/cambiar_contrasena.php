<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "liveraco_pruebabd";
$password = "JosePardo*2411";
$dbname = "liveraco_efsrtBD";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($newPassword !== $confirmPassword) {
        echo "Las contraseñas no coinciden.";
        exit;
    }

    $sql = "SELECT passLogin FROM login WHERE usuLogin = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        echo "El correo electrónico no está registrado.";
        exit;
    }

    $stmt->bind_result($storedPassword);
    $stmt->fetch();

    if (!password_verify($currentPassword, $storedPassword)) {
        echo "La contraseña actual es incorrecta.";
        exit;
    }

    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $sql = "CALL `liveraco_efsrtBD`.`CambiarPasswordNuevo`(?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $hashedNewPassword);

   if ($stmt->execute()) {
    echo "Contraseña cambiada exitosamente.";
    echo "<br><a href='/index.html'>Haz clic aquí para volver al inicio</a>";
    exit;
} else {
    echo "Error al cambiar la contraseña: " . $stmt->error;
}

    $stmt->close();
}

$conn->close();
?>
