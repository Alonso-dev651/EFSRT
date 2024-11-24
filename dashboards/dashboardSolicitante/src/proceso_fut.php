<?php
session_start();
include 'php/db_conexion.php';

try {
    // Inicializamos variables
    $nuevoNroFut = $_POST['nroFut'] ?? null;
    $anioFut = date('Y');
    $codTT = $_POST['tipoTramite'];
    $solicito = $_POST['solicitud']; // Descripción de la solicitud
    $descripcion = $_POST['descripcion'];
    $codEsp = $_POST['codEsp'];
    $codSoli = $_SESSION['codLogin']; 
    // Código momentáneo para probar envíos

    // Fecha y hora actuales
    $fechaHoraActual = date('Y-m-d H:i:s');

    // CÓDIGO PARA OBTENER NOMBRES Y APELLIDOS DEL USUARIO PARA PODER IMPRIMIRSE EN EL PDF
    $sqlSolicitante = "SELECT nombres, apPaterno, apMaterno FROM solicitante WHERE codLogin = ?";
    $stmtSolicitante = $conexion->prepare($sqlSolicitante);
    $stmtSolicitante->bind_param("i", $codSoli);
    $stmtSolicitante->execute();
    $resultSolicitante = $stmtSolicitante->get_result();

    if ($resultSolicitante->num_rows > 0) {
        $rowSolicitante = $resultSolicitante->fetch_assoc();
        $nombres = $rowSolicitante['nombres'];
        $apPaterno = $rowSolicitante['apPaterno'];
        $apMaterno = $rowSolicitante['apMaterno'];
    } else {
        throw new Exception("No se encontraron datos para el codLogin proporcionado.");
    }
    $stmtSolicitante->close();

   // Manejo de archivo PDF
    $archivo_pdf = null;
    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {
        $nombreArchivo = $_FILES['archivo']['name'];
        $rutaTemporal = $_FILES['archivo']['tmp_name'];
        $rutaDestino = 'uploads/' . $nombreArchivo;
    
        // Mueve el archivo a la carpeta 'uploads'
        if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
            $archivo_pdf = $rutaDestino;
    
            // Inserta la información del archivo en la tabla 'archivos'
            $queryArchivo = "INSERT INTO archivos (codLogin, nombre_archivo, ruta_archivo) VALUES (?, ?, ?)";
            $stmtArchivo = $conexion->prepare($queryArchivo);
            $stmtArchivo->bind_param("iss", $codSoli, $nombreArchivo, $rutaDestino);
    
            if (!$stmtArchivo->execute()) {
                throw new Exception("Error al registrar el archivo en la base de datos: " . $stmtArchivo->error);
            }
    
            $stmtArchivo->close();
        } else {
            throw new Exception("Error al subir el archivo.");
        }
    }


    // Inserción en la base de datos
    $query = "INSERT INTO fut (anioFut, fecHorIng, codTT, solicito, codSoli, descripcion, fecHoraAsignaDocente, fecHoraNotificaSolicitante, fecHoraSubePrimerFormato, fecHoraSubeUltimoFormato, fecHoraDocenteCierraFut, descDocenteCierraFut, fecHoraCoordCierraFut, descCoorCierraFut, estado, CodDocente, codEsp, comentario, archivo_pdf) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conexion, $query);

    if ($stmt) {
        // Variables para los campos opcionales que pueden ser nulos
        $fecHoraAsignaDocente = null;
        $fecHoraNotificaSolicitante = null;
        $fecHoraSubePrimerFormato = null;
        $fecHoraSubeUltimoFormato = null;
        $fecHoraDocenteCierraFut = null;
        $descDocenteCierraFut = null;
        $fecHoraCoordCierraFut = null;
        $descCoorCierraFut = null;
        $CodDocente = null;
        $comentario = null;

        // Asignar los valores a la consulta
        $estado = 'H'; // Valor por defecto para estado
        mysqli_stmt_bind_param($stmt, 'issssssssssssssssss', 
            $anioFut, 
            $fechaHoraActual, 
            $codTT, 
            $solicito, // Asegúrate de que esta sea 's'
            $codSoli, 
            $descripcion,
            $fecHoraAsignaDocente, 
            $fecHoraNotificaSolicitante, 
            $fecHoraSubePrimerFormato, 
            $fecHoraSubeUltimoFormato, 
            $fecHoraDocenteCierraFut, 
            $descDocenteCierraFut, 
            $fecHoraCoordCierraFut, 
            $descCoorCierraFut, 
            $estado,
            $CodDocente,
            $codEsp,    
            $comentario,
            $archivo_pdf
        );

        if (mysqli_stmt_execute($stmt)) {
            echo '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Solicitud Enviada</title>
                <style>
                    body {
                        background-color: #FEFDE8;
                        margin: 0;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                        font-family: Arial, sans-serif;
                        color: #333;
                    }
        
                    .container {
                        text-align: center;
                        background: #fff;
                        border-radius: 10px;
                        padding: 30px;
                        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                        max-width: 400px;
                        width: 90%;
                    }
        
                    h1 {
                        font-size: 24px;
                        margin-bottom: 20px;
                        color: #444;
                    }
        
                    p {
                        margin-bottom: 20px;
                        color: #666;
                        line-height: 1.5;
                    }
        
                    form, .redirect {
                        margin-bottom: 15px;
                    }
        
                    button {
                        background-color: #4CAF50;
                        color: white;
                        padding: 10px 20px;
                        border: none;
                        border-radius: 5px;
                        cursor: pointer;
                        font-size: 16px;
                    }
        
                    button:hover {
                        background-color: #45a049;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <h1>Solicitud enviada correctamente</h1>
                    <p>Gracias por completar el formulario. Puedes generar tu reporte PDF a continuación:</p>
        
                    <!-- Formulario para generar reporte PDF -->
                    <form action="fpdf/PruebaV.php" method="POST" target="_blank">
                        <input type="hidden" name="codsoli" value="' . htmlspecialchars($codSoli) . '">
                        <input type="hidden" name="apPaterno" value="' . htmlspecialchars($apPaterno) . '">
                        <input type="hidden" name="apMaterno" value="' . htmlspecialchars($apMaterno) . '">
                        <input type="hidden" name="nombres" value="' . htmlspecialchars($nombres) . '">
                        <input type="hidden" name="aniofut" value="' . htmlspecialchars($anioFut) . '">
                        <input type="hidden" name="codtt" value="' . htmlspecialchars($codTT) . '">
                        <input type="hidden" name="codEsp" value="' . htmlspecialchars($codEsp) . '">
                        <input type="hidden" name="solicito" value="' . htmlspecialchars($solicito) . '">
                        <input type="hidden" name="descripcion" value="' . htmlspecialchars($descripcion) . '">
                        <button type="submit">Generar Reporte PDF</button>
                    </form>
        
                    <!-- Botón para redirigir al home -->
                    <div class="redirect">
                        <button type="button" onclick="window.location.href=\'../home.php\'">Redirigir al Home</button>
                    </div>
                </div>
            </body>
            </html>';
        }
        
            exit;
        } else {
            throw new Exception("Error al ingresar la solicitud: " . mysqli_error($conexion));
        }

        mysqli_stmt_close($stmt);
    } else {
        throw new Exception("Error en la preparación de la consulta: " . mysqli_error($conexion));
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
} finally {
    mysqli_close($conexion);
}
?>
