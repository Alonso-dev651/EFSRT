<?php
include ("php/db_conexion.php");

// Usar el valor fijo 74
$codLoginFijo = 74;

// Consultar los datos del registro para el codLogin fijo
$sql = "SELECT * FROM personal WHERE codLogin = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $codLoginFijo);  
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $personal = $result->fetch_assoc();

    $apellidoPaterno = $personal['apPaterno'];
    $apellidoMaterno = $personal['apMaterno'];
    $nombresPersonal = $personal['nombres'];
} else {
    echo "No se encontraron datos para el codLogin proporcionado.";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link rel="stylesheet" href="style.css">
    <link href="Icons_Dash/Logo.ico" rel="icon">
    <script defer src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script defer src="main.js"></script>

    <!-- añadiendo fontawesome para icono editar -->
    <script src="https://kit.fontawesome.com/a683fc1d22.js" crossorigin="anonymous"></script>

    <title>EFSRT Dashboard</title>
</head>

<body>
    <nav class="main-menu">
        <div>
            <div class="logo">
                <img src="Icons_Dash/Logo.ico" alt="logo" />
            </div>

            <div class="user-info">
                <img src="https://cdn-icons-png.flaticon.com/512/7816/7816916.png" alt="user" />
                <p>Soporte</p>
            </div>
            <ul>
                <li class="nav-item active">
                    <a href="index.php">
                        <i class="fa-solid fa-table nav-icon"></i>
                        <span class="nav-text">Tablero</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#">
                        <i class="fa fa-arrow-trend-up nav-icon"></i>
                        <span class="nav-text">Tramite</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#">
                        <i class="fa-solid fa-chart-simple nav-icon"></i>
                        <span class="nav-text">Estado</span>
                    </a>
                </li>

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
            <!-- Inicio de la vista de los registros -->
            <div style="display: flex; justify-content: center; align-items: center;">
                <h1>REGISTROS DEL PERSONAL</h1>
            </div>            
            <hr>
                        <!-- Botón para registrar nuevo personal -->
            <div class="fut-navigation">
                <a href="registrar_personal.php" class="fut-nav-button">
                <i class="fa-solid fa-address-card"></i> Registrar Personal
                </a>
            </div>
            <div class="fut-navigation">
                <a href="restorePassword/fr_password.php" class="fut-nav-button">
                <i class="fa-solid fa-lock"></i> Cambiar Password
                </a>
            </div>
                <div class="fut-container">
    <form action="" class="card-container">
        <?php
        include "php/db_conexion.php";
        $sql = $conexion->query("SELECT * FROM personal");

        while ($datos = $sql->fetch_object()) {
        ?>
        <div class="fut-card"> 
            <div class="fut-details">
                <h3><?= htmlspecialchars($datos->nombres) ?> <?= htmlspecialchars($datos->apPaterno) ?> <?= htmlspecialchars($datos->apMaterno) ?></h3>
                <p><strong>Nro Doc:</strong> <?= htmlspecialchars($datos->nroDocu) ?></p>
                <p><strong>Celular:</strong> <?= htmlspecialchars($datos->celular) ?></p>
                <p><strong>Correo Institucional:</strong> <?= htmlspecialchars($datos->correoJP) ?></p>
                <p><strong>Correo Personal:</strong> <?= htmlspecialchars($datos->correoPersonal) ?></p>
                <p><strong>Estado:</strong> <?= htmlspecialchars($datos->estado) ?></p>
                <p><strong>Tipo Personal:</strong> <?= htmlspecialchars($datos->tipoPer) ?></p>
                <?php
// Captura la especialidad mediante el c��digo de especialidad y lo muestra
$codEsp = $datos->codEsp; 
$sqlEsp = "SELECT nomEsp FROM especialidad WHERE codEsp = ?";
$stmtEsp = $conexion->prepare($sqlEsp);
$stmtEsp->bind_param("i", $codEsp);
$stmtEsp->execute();
$resultEsp = $stmtEsp->get_result();

// Manejar el caso en que no haya resultados
if ($resultEsp->num_rows > 0) {
    $filaEsp = $resultEsp->fetch_assoc();
    $nomEsp = $filaEsp['nomEsp'];
} else {
    $nomEsp = "No asignado";
}
?>

                <p><strong>Especialidad:</strong> <?= htmlspecialchars($nomEsp) ?></p>
            </div>
            <div class="fut-form"> 
                <a href="modificar_personal.php?id=<?= urlencode($datos->codLogin) ?>" class="fut-button">
                    <i class="fa-solid fa-user-pen"></i> Editar
                </a>
            </div>
        </div>
        <?php
        }
        ?>
    </form>
</div>


        </div>
    </section>
</body>

</html>
