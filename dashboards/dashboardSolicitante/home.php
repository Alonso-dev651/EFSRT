<?php
session_start();
$codSoli = $_SESSION['codLogin'];
include 'src/php/db_conexion.php';

// Recibir el término de búsqueda
$searchTerm = isset($_POST['search']) ? trim($_POST['search']) : '';

// Consulta para obtener los datos del solicitante
$sqlSolicitante = "SELECT nombres, apPaterno, apMaterno, codEsp FROM solicitante WHERE codLogin = ?";
$stmtSolicitante = $conexion->prepare($sqlSolicitante);
$stmtSolicitante->bind_param("i", $codSoli);
$stmtSolicitante->execute();
$resultSolicitante = $stmtSolicitante->get_result();
$rowSolicitante = $resultSolicitante->fetch_assoc();
$nombres = $rowSolicitante['nombres'];
$apPaterno = $rowSolicitante['apPaterno'];
$apMaterno = $rowSolicitante['apMaterno'];
$codEsp = $rowSolicitante['codEsp'];

// Consulta para obtener la especialidad
$sqlEsp = "SELECT codEsp, nomEsp FROM especialidad WHERE codEsp = ?";
$stmtEsp = $conexion->prepare($sqlEsp);
$stmtEsp->bind_param("i", $codEsp);
$stmtEsp->execute();
$resultEsp = $stmtEsp->get_result();
$rowEsp = $resultEsp->fetch_assoc();
$nomEsp = $rowEsp['nomEsp'];

// Consulta para obtener el FUT del solicitante con o sin filtro de búsqueda
if (!empty($searchTerm)) {
  $sqlFut = "SELECT nroFut, anioFut, fecHorIng, solicito, estado, archivo_pdf FROM fut WHERE codSoli = ? AND nroFut LIKE ?";
  $searchTerm = "%$searchTerm%";
  $stmtFut = $conexion->prepare($sqlFut);
  $stmtFut->bind_param("is", $codSoli, $searchTerm);
} else {
  $sqlFut = "SELECT nroFut, anioFut, fecHorIng, solicito, estado, archivo_pdf FROM fut WHERE codSoli = ?";
  $stmtFut = $conexion->prepare($sqlFut);
  $stmtFut->bind_param("i", $codSoli);
}

$stmtFut->execute();
$resultFut = $stmtFut->get_result();
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
  <link href="../../../src/images/Logo.ico" rel="icon">
  <script defer src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
  <script defer src="main.js"></script>
  <title>EFSRT Dashboard</title>
</head>

<body>
  <nav class="main-menu">
    <div>
      <div class="logo">
        <img src="../../../src/images/Logo.ico" alt="logo" />
      </div>

      <div class="user-info">
        <img src="https://cdn-icons-png.flaticon.com/512/7816/7816916.png" alt="user" />
        <p><?php echo  $apPaterno . ' ' . $apMaterno . ' ' . $nombres; ?></p>
      </div>

      <ul>
        <li class="nav-item">
          <a href="pages/user.php">
            <i class="fa fa-user nav-icon"></i>
            <span class="nav-text">Cuenta</span>
          </a>
        </li>

        <li class="nav-item active">
          <a href="home.php">
            <i class="fa-solid fa-table nav-icon"></i>
            <span class="nav-text">Tablero</span>
          </a>
        </li>

        <li class="nav-item">
          <a href="pages/formularioFUT.php">
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
      <div class="search-and-check">
        <!-- Search Box -->
        <form class="search-box" method="POST" action="">
          <input type="text" name="search" placeholder="Buscar..." />
          <button type="submit"><i class="bx bx-search"></i></button>
        </form>
      </div>

      <div class="upcoming-events">
        <h1>Tablero</h1>

        <!-- Para mostrar el fut en el dashboard -->
        <h2>FUTs del Alumno</h2>
        <div class="fut-container">
          <?php if ($resultFut->num_rows > 0): ?>
            <?php while ($rowFut = $resultFut->fetch_assoc()) { ?>
              <div class="card fut-card">
                <form class="form-solicitud" action="src/actualizar.php" method="POST" id="miFormulario" enctype="multipart/form-data">
                  <input type="hidden" name="nroFut" value="<?php echo $rowFut['nroFut']; ?>">

                  <p><strong>Número FUT:</strong> <?php echo $rowFut['nroFut']; ?></p>
                  <p><strong>Año FUT:</strong> <?php echo $rowFut['anioFut']; ?></p>
                  <p><strong>Fecha y Hora de Ingreso:</strong> <?php echo $rowFut['fecHorIng']; ?></p>
                  <p><strong>Solicitud:</strong> <?php echo $rowFut['solicito']; ?></p>
                  <p><strong>Especialidad:</strong> <?php echo $nomEsp; ?></p>
                  <p><strong>Estado:</strong>   <!--ESTADOS DE LOS FUTS-->
                  <?php 
                  switch ($rowFut['estado']) {
                      case 'H': 
                      echo 'Habilitado';break;
                      case 'A':
                      echo 'Asignado';
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
                  
                </form>
              </div>
            <?php } ?>
          <?php else: ?>
            <p>No existe ningún FUT con el número especificado.</p>
          <?php endif; ?>
        </div>
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
