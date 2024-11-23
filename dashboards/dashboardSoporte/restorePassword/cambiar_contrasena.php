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
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Verificar que las contraseñas coincidan
    if ($newPassword !== $confirmPassword) {
        echo "Las contraseñas no coinciden.";
        exit;
    }

    // Verificar que el correo electrónico esté registrado
    $sql = "SELECT passLogin FROM login WHERE usuLogin = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        echo "El correo electrónico no está registrado.";
        exit;
    }

    // Si el correo está registrado, proceder a cambiar la contraseña
    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Llamar al procedimiento almacenado para cambiar la contraseña
    $sql = "CALL `liveraco_efsrtBD`.`CambiarPasswordNuevo`(?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $hashedNewPassword);

    if ($stmt->execute()) {
        echo "Contraseña cambiada exitosamente.";
        echo "<br><a href='/dashboards/dashboardSoporte/index.php'>Haz clic aquí para volver al inicio</a>";
        exit;
    } else {
        echo "Error al cambiar la contraseña: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
