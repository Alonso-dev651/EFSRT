<?php
include 'php/db_conexion.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nroFut = $_POST['nroFut'];
        $comentario = $_POST['comentario'];
        $estado = $_POST['estado'];

        // Ruta donde se guardan los archivos
        $uploadDir = '../pages/uploads/';

        // Verificar si se subi車 un nuevo archivo PDF
        if (isset($_FILES['nuevoDocumento']) && $_FILES['nuevoDocumento']['error'] === UPLOAD_ERR_OK) {
            $newFileName = $_FILES['nuevoDocumento']['name'];
            $newFileTmpPath = $_FILES['nuevoDocumento']['tmp_name'];
            $fileType = pathinfo($newFileName, PATHINFO_EXTENSION);

            // Consulta para obtener el nombre del archivo PDF existente
            $query = "SELECT archivo_pdf FROM fut WHERE nroFut = ?";
            $stmt = mysqli_prepare($conexion, $query);
            if (!$stmt) {
                throw new Exception("Error en la preparaci車n de la consulta: " . mysqli_error($conexion));
            }
            mysqli_stmt_bind_param($stmt, 'i', $nroFut);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $existingFile);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);

            // Si hay un archivo PDF existente, lo eliminamos
            if ($existingFile && file_exists($uploadDir . $existingFile)) {
                unlink($uploadDir . $existingFile);
            }

            // Generar un nuevo nombre de archivo 迆nico
            $newFileName = uniqid('fut_' . $nroFut . '_') . '.pdf';
            $newFilePath = $uploadDir . $newFileName;

            // Mover el nuevo archivo a la carpeta de destino
            if (!move_uploaded_file($newFileTmpPath, $newFilePath)) {
                throw new Exception("Error al mover el archivo a la carpeta de destino.");
            }

            // Actualizar la base de datos con el nuevo nombre de archivo
            $query = "UPDATE fut SET archivo_pdf = ?, comentario = ?, estado = ? WHERE nroFut = ?";
            $stmt = mysqli_prepare($conexion, $query);
            if (!$stmt) {
                throw new Exception("Error en la preparaci車n de la consulta de actualizaci車n: " . mysqli_error($conexion));
            }
            mysqli_stmt_bind_param($stmt, 'sssi', $newFileName, $comentario, $estado, $nroFut);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            echo "Registro actualizado correctamente.";
            echo '<a href="../home.php">Volver</a>';
        } else {
            // Si no se subi車 un nuevo archivo, solo actualizar comentario y estado
            $query = "UPDATE fut SET comentario = ?, estado = ? WHERE nroFut = ?";
            $stmt = mysqli_prepare($conexion, $query);
            if (!$stmt) {
                throw new Exception("Error en la preparaci車n de la consulta de actualizaci車n sin archivo: " . mysqli_error($conexion));
            }
            mysqli_stmt_bind_param($stmt, 'ssi', $comentario, $estado, $nroFut);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            echo "Formulario actualizado sin cambio de archivo.";
        }
    } else {
        throw new Exception("M谷todo de solicitud no permitido.");
    }
} catch (Exception $e) {
    echo "Se ha producido un error: " . $e->getMessage();
}
?>
