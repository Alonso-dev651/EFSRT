<?php
session_start();
include '../formulario_fut/php/db_conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nroFut = $_POST['nroFut'] ?? '';
    $anioFut = $_POST['anioFut'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $docente = $_POST['docente'] ?? '';
    $codCoordinador = $_POST['coordinador'] ?? '';

    if (empty($nroFut) || empty($anioFut) || empty($descripcion) || empty($docente) || empty($codCoordinador)) {
        echo "Error: Todos los campos son obligatorios.";
        exit();
    }

    $sql = "UPDATE fut 
            SET CodDocente = ?, Descripcion = ?, codCoordinador = ?, fecHoraAsignaDocente = NOW(), Estado = 'A' 
            WHERE nroFut = ? AND anioFut = ?";

    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("isiii", $docente, $descripcion, $codCoordinador, $nroFut, $anioFut);

        if ($stmt->execute()) {
            echo "<script>alert('FUT asignado exitosamente al docente'); window.location.href = '../../dashboardCoordinador/home.php';</script>";
        } else {
            echo "Error al asignar el FUT al docente: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error en la preparaci¨®n de la consulta: " . $conexion->error;
    }
} else {
    echo "Error: M¨¦todo de solicitud no v¨¢lido.";
}

$conexion->close();
?>
