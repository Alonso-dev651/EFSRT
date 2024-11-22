<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
$codCoordinador = $_SESSION['codLogin'];
include '../formulario_fut/php/db_conexion.php';

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nroFut = $_POST['nroFut'];
    $anioFut = $_POST['anioFut'];
    $fecHorIng = $_POST['fecHorIng'];
    $solicito = $_POST['solicito'];
    $estado = $_POST['estado'];
    $codCoordinador = $_POST['codCoordinador'];
}

// Obtener el comentario del fut.
$sqlfut = "SELECT comentario FROM fut WHERE nroFut = ?";
$stmtfut = $conexion->prepare($sqlfut);
$stmtfut->bind_param("i", $nroFut);
$stmtfut->execute();
$resultfut = $stmtfut->get_result();
$rowfut = $resultfut->fetch_assoc();

// Verifica si el comentario es NULL
$comentario = !empty($rowfut['comentario']) ? $rowfut['comentario'] : 'No hay comentario disponible';


// Obtener datos del solicitante
$sqlSolicitante = "SELECT nombres, apPaterno, apMaterno FROM personal WHERE codLogin = ?";
$stmtSolicitante = $conexion->prepare($sqlSolicitante);
$stmtSolicitante->bind_param("i", $codCoordinador);
$stmtSolicitante->execute();
$resultSolicitante = $stmtSolicitante->get_result();
$rowSolicitante = $resultSolicitante->fetch_assoc();

$nombres = $rowSolicitante['nombres'];
$apPaterno = $rowSolicitante['apPaterno'];
$apMaterno = $rowSolicitante['apMaterno'];

$query = "SELECT apPaterno, apMaterno, nombres, tipoDocu, nroDocu, codModular, telf, celular, correoJP, correoPersonal, direccion, anioIngreso, anioEgreso FROM solicitante WHERE codLogin = ?";
$stmt = mysqli_prepare($conexion, $query);
mysqli_stmt_bind_param($stmt, 'i', $codLogin);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $apPaterno, $apMaterno, $nombres, $tipoDocu, $nroDocu, $codModular, $telf, $celular, $correoJP, $correoPersonal, $direccion, $anioIngreso, $anioEgreso);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
  <link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="../formulario_fut/style/styleFut.css">
  <link href="../Pages_Dash/Logo.ico" rel="icon">
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
        <img src="../Pages_Dash/Logo.ico" alt="logoPardo" />
      </div>

      <div class="user-info">
        <img src="https://cdn-icons-png.flaticon.com/512/7816/7816916.png" alt="user" />
        <p><?php echo $nombres . ' ' . $apPaterno . ' ' . $apMaterno; ?></p>
      </div>

      <ul>
        <li class="nav-item">
          <a href="../Pages_Dash/user.php">
            <i class="fa fa-user nav-icon"></i>
            <span class="nav-text">Cuenta</span>
          </a>
        </li>

        <li class="nav-item active">
          <a href="../../dashboardCoordinador/home.php">
            <i class="fa-solid fa-table nav-icon"></i>
            <span class="nav-text">Tablero</span>
          </a>
        </li>

        <li class="nav-item">
          <a href="../formulario_fut/formularioFUT.php">
            <i class="fa fa-arrow-trend-up nav-icon"></i>
            <span class="nav-text">Tramite</span>
          </a>
        </li>

        <li class="nav-item">
          <a href="../Estado_fut/estado.php">
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
      <div class="form-solicitud">
        <h1>Detalles del FUT</h1>

        <div class="fut-info">
          <p><strong>Número FUT:</strong> <?php echo htmlspecialchars($_POST['nroFut']); ?></p>
          <p><strong>Año FUT:</strong> <?php echo htmlspecialchars($_POST['anioFut']); ?></p>
          <p><strong>Fecha y Hora de Ingreso:</strong> <?php echo htmlspecialchars($_POST['fecHorIng']); ?></p>
          <p><strong>Solicitud:</strong> <?php echo htmlspecialchars($_POST['solicito']); ?></p>
          <p><strong>Estado:</strong>
                  <?php
                    switch ($estado) {
                      case 'H':
                        echo 'Habilitado';
                        break;
                      case 'A':
                        echo 'Aprobado';
                        break;
                      case 'D':
                        echo 'Desaprobado';
                        break;
                      case 'R':
                        echo 'Rechazado';
                        break;
                      case 'C':
                        echo 'Cerrado';
                        break;
                      default:
                        echo 'Estado desconocido';
                    }
                  ?>
                </p>
          <p><strong>Coordinador:</strong> <?php echo $nombres . ' ' . $apPaterno; ?></p>
          <p><strong>Comentario:</strong> <?php echo htmlspecialchars($comentario); ?></p>
        </div>

        <form action="procesar_gestion.php" method="post">
          <input type="hidden" name="nroFut" value="<?php echo htmlspecialchars($_POST['nroFut']); ?>">
          <input type="hidden" name="anioFut" value="<?php echo htmlspecialchars($_POST['anioFut']); ?>">
          <input type="hidden" name="fecHorIng" value="<?php echo htmlspecialchars($_POST['fecHorIng']); ?>">
          <input type="hidden" name="solicito" value="<?php echo htmlspecialchars($_POST['solicito']); ?>">
          <input type="hidden" name="estado" value="<?php echo htmlspecialchars($_POST['estado']); ?>">
          <input type="hidden" name="codCoordinador" value="<?php echo htmlspecialchars($_POST['codCoordinador']); ?>">
          <input type="hidden" name="comentario" value="<?php echo htmlspecialchars($comentario); ?>">
          
          <br>
        <div class="form-group">
            <label for="Coordescripcion"><strong>Descripcion</strong></label>
            <textarea id="Coordescripcion" name="Coordescripcion" rows="4" cols="50" placeholder="Ingrese una descripcion"></textarea>
        </div>
          
          <div class="button-row">
            <div class="form-group">
                <button type="submit" class="fut-button" name="accion" value="cerrar">Cerrar FUT</button>
                <button type="submit" class="fut-button" name="accion" value="rechazar">Rechazar FUT</button>
                <button type="button" class="btn-cancel" onclick="window.location.href='../../dashboardCoordinador/home.php';">Cancelar</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    
    <div class="right-content">
      <div class="interaction-control interactions">
        <i class="fa-regular fa-envelope notified"></i>
        <i class="fa-regular fa-bell notified"></i>
        <div class="toggle" onclick="switchTheme()">
          <div class="mode-icon moon">
            <i class="bx bxs-moon"></i>
          </div>
          <div class="mode-icon sun hidden">
            <i class="bx bxs-sun"></i>
          </div>
        </div>
      </div>

      <div class="analytics">
        <h1>Analisis</h1>
        <div class="analytics-container">
          <div class="total-events">
            <div class="event-number card">
              <h2>Aprobados</h2>
              <p>1</p>
              <i class="bx bx-check-circle"></i>
            </div>
            <div class="event-number card">
              <h2>Pendientes</h2>
              <p>2</p>
              <i class="bx bx-timer"></i>
            </div>
          </div>

          <div class="chart" id="doughnut-chart">
            <h2>Porcentaje del Tramite</h2>
            <canvas id="doughnut"></canvas>
            <ul></ul>
          </div>
        </div>
      </div>

      <div class="contacts">
        <h1>Contactos</h1>
        <div class="contacts-container">
          <div class="contact-status">
            <div class="contact-activity">
              <img
                src="https://cdn-icons-png.flaticon.com/512/7816/7816916.png"
                alt="User Icon" />
              <p>Usuario <span><a target="_blank"
                    href="https://github.com/Alonso-dev651/EFSRT">Developer</a></span></p>
            </div>
            <small>1 hour ago</small>
          </div>
        </div>
      </div>
    </div>
  </section>
</body>

</html>