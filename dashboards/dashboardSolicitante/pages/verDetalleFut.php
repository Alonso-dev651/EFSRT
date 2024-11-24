<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
$codSoli = $_SESSION['codLogin'];
include '../src/php/db_conexion.php';
$nroFut = isset($_POST['nroFut']) ? $_POST['nroFut'] : null;
$anioFut = isset($_POST['anioFut']) ? $_POST['anioFut'] : null;

if ($nroFut) {

    // Consulta para obtener los datos del solicitante y el tipo de trámite basado en nroFut
        $query = "SELECT s.apPaterno, s.apMaterno, s.nombres, s.tipoDocu, s.nroDocu, s.codModular, s.telf, s.celular, s.correoJP, s.correoPersonal, s.direccion, s.anioIngreso, s.anioEgreso, e.nomEsp, f.codTT, f.solicito, f.descripcion, f.fecHoraAsignaDocente,f.estado, f.archivo_pdf, f.codDocente, tt.descTT
                  FROM solicitante s 
                  INNER JOIN fut f ON s.codLogin = f.codSoli
                  INNER JOIN especialidad e ON s.codEsp = e.codEsp
                  LEFT JOIN tipoTramite tt ON f.codTT = tt.codTT
                  WHERE f.nroFut = ?;";

        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, 'i', $nroFut);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $apPaternoSoli, $apMaternoSoli, $nombresSoli, $tipoDocu, $nroDocu, $codModular, $telf, $celular, $correoJP, $correoPersonal, $direccion, $anioIngreso, $anioEgreso, $nomEsp, $codTTSeleccionado, $solicito, $descripcion, $fecHoraAsignaDocente,$estado, $archivo_pdf, $codDocente, $descTT);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
} else {
    $futDetails = "<p>Faltan datos necesarios para mostrar los detalles.</p>";
}

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
  <link rel="stylesheet" href="../styles.css">
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
          <a href="user.php">
            <i class="fa fa-user nav-icon"></i>
            <span class="nav-text">Cuenta</span>
          </a>
        </li>

        <li class="nav-item active">
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

        <h2>FUTs del Alumno</h2>
        <div class="fut-info">
          <?php if (isset($apPaternoSoli)) { ?>
            <div class="card fut-card">
              <h2>Detalles del FUT</h2>
              <p><strong>Numero FUT:</strong> <?php echo $nroFut; ?></p>
              <p><strong>Año FUT:</strong> <?php echo $anioFut; ?></p>
              <p><strong>Fecha y Hora de Ingreso:</strong> <?php echo $fecHoraAsignaDocente; ?></p>
              <p><strong>Solicitante:</strong> <?php echo $nombresSoli . ' ' . $apPaternoSoli . ' ' . $apMaternoSoli; ?></p>
              <p><strong>Tipo de Documento:</strong> <?php echo $tipoDocu; ?></p>
              <p><strong>Numero de Documento:</strong> <?php echo $nroDocu; ?></p>
              <p><strong>Codigo Modular:</strong> <?php echo $codModular; ?></p>
              <p><strong>Telefono:</strong> <?php echo $telf; ?></p>
              <p><strong>Celular:</strong> <?php echo $celular; ?></p>
              <p><strong>Correo Institucional:</strong> <?php echo $correoJP; ?></p>
              <p><strong>Correo Personal:</strong> <?php echo $correoPersonal; ?></p>
              <p><strong>Direccion:</strong> <?php echo $direccion; ?></p>
              <p><strong>Año de Ingreso:</strong> <?php echo $anioIngreso; ?></p>
              <p><strong>Año de Egreso:</strong> <?php echo $anioEgreso; ?></p>
              <p><strong>Especialidad:</strong> <?php echo $nomEsp; ?></p>
              <p><strong>Tipo de Tramite:</strong> <?php echo $descTT; ?></p>
              <p><strong>Descripcion:</strong> <?php echo $descripcion; ?></p>
              <p><strong>Estado:</strong> 
                <?php 
                  switch ($estado) {
                    case 'H': 
                      echo 'Habilitado'; break;
                    case 'A':
                      echo 'Asignado'; break;
                    case 'R': 
                      echo 'Rechazado'; break;
                    case 'C': 
                      echo 'Cerrado'; break;
                    default: 
                      echo 'Estado desconocido';
                  }
                ?>
              </p>
              <p><strong>Archivo PDF:</strong> 
                <?php if ($archivo_pdf) { ?>
                  <a href="../src/<?php echo $archivo_pdf; ?>" target="_blank">Ver Documento</a>
                <?php } else { ?>
                  No disponible
                <?php } ?>
              </p>
            </div>
            <button onclick="history.back()">VOLVER</button>
          <?php } else { ?>
            <p>No se encontraron detalles para el FUT especificado.</p>
          <?php } ?>
        </div>
      </div>
    </div>
  </section>
</body>

</html>