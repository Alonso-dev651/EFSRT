<?php
session_start(); // Iniciar sesión
include '../src/php/db_conexion.php';

// Verificar si existe codLogin en la sesión
if (!isset($_SESSION['codLogin'])) {
    echo "No se encontró un código de usuario válido. Inicia sesión nuevamente.";
    exit;
}

// Conexión a la base de datos
$servername = "localhost";
$username = "liveraco_pruebabd";
$password = "JosePardo*2411";
$dbname = "liveraco_efsrtBD";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el codLogin de la sesión
$codLogin = $_SESSION['codLogin'];

// Recibir datos de home.php
$nroFut = $_POST['nroFut'] ?? '';
$anioFut = $_POST['anioFut'] ?? '';
$fecHorIng = $_POST['fecHorIng'] ?? '';
$solicito = $_POST['solicito'] ?? '';
$estado = $_POST['estado'] ?? '';

// CÓDIGO PARA OBTENER NOMBRES Y APELLIDOS DEL USUARIO PARA PODER IMPRIMIRSE EN

$sqlSolicitante = "SELECT nombres, apPaterno, apMaterno FROM solicitante WHERE codLogin = ?";
$stmtSolicitante = $conn->prepare($sqlSolicitante);
$stmtSolicitante->bind_param("i", $codLogin);
$stmtSolicitante->execute();
$resultSolicitante = $stmtSolicitante->get_result();

$rowSolicitante = $resultSolicitante->fetch_assoc();
$nombres = $rowSolicitante['nombres'];
$apPaterno = $rowSolicitante['apPaterno'];
$apMaterno = $rowSolicitante['apMaterno'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link rel="stylesheet" href="../styles/state.css">
    <link href="../../../src/images/Logo.ico" rel="icon">
    <script defer src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script defer src="../main.js"></script>
    <title>EFSRT Dashboard</title>
</head>

<body>
    <nav class="main-menu">
        <div>
            <div class="logo">
                <img
                    src="../../../src/images/Logo.ico"
                    alt="logo" />
            </div>

            <div class="user-info">
                <img src="https://cdn-icons-png.flaticon.com/512/7816/7816916.png" alt="user" />
                <p><?php echo $nombres . ' ' . $apPaterno . ' ' . $apMaterno; ?></p>
            </div>
            <ul>
                <li class="nav-item">
                    <a href="user.php">
                        <i class="fa fa-user nav-icon"></i>
                        <span class="nav-text">Cuenta</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="../home.php">
                        <i class="fa-solid fa-table nav-icon"></i>
                        <span class="nav-text">Tablero</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="formularioFUT.php">
                        <i class="fa fa-arrow-trend-up nav-icon"></i>
                        <span class="nav-text">Tramite</span>
                    </a>
                </li>

                <li class="nav-item active">
                    <a href="estado.php">
                        <i class="fa-solid fa-chart-simple nav-icon"></i>
                        <span class="nav-text">Estado de FUTs</span>
                    </a>
                </li>
                <br>

                <li class="nav-item">
                    <a href="#">
                        <i class="fa fa-circle-info nav-icon"></i>
                        <span class="nav-text">Ayuda</span>
                    </a>
                </li>
            </ul>
        </div>

        <ul>
            <li class="nav-item">
                <a href="https://proyecto.live-ra.com">
                    <i class="fa fa-right-from-bracket nav-icon"></i>
                    <span class="nav-text">Salir</span>
                </a>
            </li>
        </ul>
    </nav>

    <section class="content">
        <div class="left-content">
            <div class="search-and-check">
                <form class="search-box">
                    <input type="text" placeholder="Buscar..." />
                    <i class="bx bx-search"></i>
                </form>
            </div>

            <div class="card">
                <h1>Detalles del FUT</h1>
                <p><strong>N��mero FUT:</strong> <?php echo htmlspecialchars($nroFut); ?></p>
                <p><strong>A�0�9o FUT:</strong> <?php echo htmlspecialchars($anioFut); ?></p>
                <p><strong>Fecha y Hora de Ingreso:</strong> <?php echo htmlspecialchars($fecHorIng); ?></p>
                <p><strong>Solicitud:</strong> <?php echo htmlspecialchars($solicito); ?></p>
                <p><strong>Estado:</strong> <?php echo $estado == 'H' ? 'Habilitado' : 'Inhabilitado'; ?></p>

                <?php $conn->close(); ?>
            </div>
        </div>
    </section>
</body>

</html>