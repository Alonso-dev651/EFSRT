<?php
// Mostrar todos los errores de PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conexión a la base de datos
$servidor = "localhost";
$usuario = "liveraco_pruebabd";
$contraseña = "JosePardo*2411";
$basedatos = "liveraco_efsrtBD";

$conn = new mysqli($servidor, $usuario, $contraseña, $basedatos);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se envió el formulario
if (isset($_POST['submit'])) {
    // Escapar la entrada del usuario para evitar inyección de SQL
    $descripcion = $conn->real_escape_string($_POST['descripcion']);

    // Verificar si el archivo fue subido correctamente
    if (isset($_FILES['archivo_pdf']) && $_FILES['archivo_pdf']['error'] == 0) {
        $nombreArchivo = $_FILES['archivo_pdf']['name'];
        $rutaTemporal = $_FILES['archivo_pdf']['tmp_name'];
        $rutaDestino = "uploads/" . $nombreArchivo;

        // Obtener la extensión del archivo y convertirla a minúsculas
        $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));

        // Verificar si el archivo es un PDF
        if ($extension === 'pdf') {
            // Crear la carpeta 'uploads' si no existe
            if (!is_dir('uploads')) {
                if (!mkdir('uploads', 0777, true)) {
                    die("Error al crear la carpeta 'uploads'. Verifica los permisos.");
                }
            }

            // Mover el archivo a la carpeta de destino
            if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
                // Insertar la información en la base de datos
                $sql = "INSERT INTO documento (descripcion, archivo_pdf) VALUES ('$descripcion', '$rutaDestino')";

                if ($conn->query($sql) === TRUE) {
                    echo "El documento se subió correctamente.";
                } else {
                    echo "Error al subir el documento: " . $conn->error;
                }
            } else {
                echo "Error al mover el archivo. Verifica los permisos de la carpeta 'uploads'.";
            }
        } else {
            echo "Solo se permiten archivos PDF.";
        }
    } else {
        echo "Error al subir el archivo. Código de error: " . $_FILES['archivo_pdf']['error'];
    }
}

// Cerrar la conexión a la base de datos
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Documento PDF</title>
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f4f4f4;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 3px;
            box-sizing: border-box;
        }

        button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0069d9;
        }

        .fut-button {
            background-color: #dc3545;
            margin-left: 10px;
        }

        .fut-button:hover {
            background-color: #c82333;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Subir Documento PDF</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="descripcion">Descripción:</label>
            <input type="text" id="descripcion" name="descripcion" required><br><br>

            <label for="archivo_pdf">Seleccionar archivo PDF:</label>
            <input type="file" id="archivo_pdf" name="archivo_pdf" accept=".pdf" required><br><br>

            <button type="submit" name="submit">Subir Documento</button>
            <button onclick="window.location.href='../../dashboards/dashboardCoordinador/home.php'" class="fut-button">Cancelar</button>
        </form>
    </div>
</body>

</html>