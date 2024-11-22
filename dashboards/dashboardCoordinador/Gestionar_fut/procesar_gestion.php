<?php
session_start();
include '../formulario_fut/php/db_conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nroFut = $_POST['nroFut'] ?? '';
    $anioFut = $_POST['anioFut'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $docente = $_POST['docente'] ?? '';
    $codCoordinador = $_POST['coordinador'] ?? '';
    $accion = $_POST['accion'] ?? '';
    $descripcion = $_POST['Coordescripcion'] ?? '';
    
    if ($accion === 'rechazar') {
        $sqlUpdate = "UPDATE fut SET estado = 'R', fecHoraCoordCierraFut = NOW(), fecHoraDocenteCierraFut = NOW(),  descCoorCierraFut = ? WHERE nroFut = ?";
        $stmtUpdate = $conexion->prepare($sqlUpdate);
        $stmtUpdate->bind_param("si", $descripcion, $nroFut);
        $stmtUpdate->execute();

        if ($stmtUpdate->affected_rows > 0) {
            echo "<script>alert('FUT Rechazado exitosamente'); window.location.href = '../../dashboardCoordinador/home.php';</script>";
            echo "<a href='../home.php'>Volver</a>";
        } else {
            echo "No se pudo actualizar el estado del FUT.";
            echo "<a href='../home.php'>Volver</a>";
        }
    } elseif ($accion === 'cerrar') {
        $sqlUpdate = "UPDATE fut SET estado = 'C', fecHoraCoordCierraFut = NOW(), fecHoraDocenteCierraFut = NOW(), descCoorCierraFut = ? WHERE nroFut = ?";
        $stmtUpdate = $conexion->prepare($sqlUpdate);
        $stmtUpdate->bind_param("si", $descripcion, $nroFut);
        $stmtUpdate->execute();

        if ($stmtUpdate->affected_rows > 0) {
            echo "<script>alert('FUT Cerrado exitosamente'); window.location.href = '../../dashboardCoordinador/home.php';</script>";
            echo "<a href='../home.php'>Volver</a>";
        } else {
            echo "No se pudo actualizar el estado del FUT.";
            echo "<a href='../home.php'>Volver</a>";
        }
    } else {
        echo "Error en la preparaci車n de la consulta: " . $conexion->error;
            echo "<a href='../home.php'>Volver</a>";
    }

} else {
    echo "Error: M谷todo de solicitud no v芍lido.";
            echo "<a href='../home.php'>Volver</a>";
}

$conexion->close();
?>