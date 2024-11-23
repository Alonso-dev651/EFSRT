<?php
session_start();
$codSoli = $_SESSION['codLogin'];
include 'src/php/db_conexion.php';

// Para jalar los datos e imprimirse
$sqlSolicitante = "SELECT nombres, apPaterno, apMaterno FROM personal WHERE codLogin = ?";
$stmtSolicitante = $conexion->prepare($sqlSolicitante);
$stmtSolicitante->bind_param("i", $codSoli);
$stmtSolicitante->execute();
$resultSolicitante = $stmtSolicitante->get_result();
$rowSolicitante = $resultSolicitante->fetch_assoc();
$nombres = $rowSolicitante['nombres'];
$apPaterno = $rowSolicitante['apPaterno'];
$apMaterno = $rowSolicitante['apMaterno'];

// Inicializar variable para mostrar mensaje
$mensaje = '';
$futEncontrados = [];

// Contar FUTs asignados
$sqlAceptados = "SELECT COUNT(*) AS totalAceptados FROM fut WHERE estado = 'A'";
$resultAceptados = $conexion->query($sqlAceptados);
$totalAceptados = $resultAceptados->fetch_assoc()['totalAceptados'];

// Contar FUTs pendientes
$sqlRechazados = "SELECT COUNT(*) AS totalRechazados FROM fut WHERE estado = 'D'";
$resultRechazados = $conexion->query($sqlRechazados);
$totalRechazados = $resultRechazados->fetch_assoc()['totalRechazados'];


// Manejar la búsqueda de FUT
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nroFut'])) {
  $nroFutBuscado = $_POST['nroFut'];

  // Consultar FUT por número
  $sqlFut = "SELECT nroFut, anioFut, fecHorIng, solicito, estado, codCoordinador FROM fut WHERE CodDocente = ? AND nroFut = ?";
  $stmtFut = $conexion->prepare($sqlFut);
  $stmtFut->bind_param("ii", $codSoli, $nroFutBuscado);
  $stmtFut->execute();
  $resultFut = $stmtFut->get_result();

  // Comprobar si se encontraron resultados
  if ($resultFut->num_rows > 0) {
    while ($rowFut = $resultFut->fetch_assoc()) {
      $futEncontrados[] = $rowFut;
    }
  } else {
    $mensaje = "Usted no tiene FUTs asignados.";
  }
} else {
  // Para jalar todos los datos de FUTs y mostrarlos si no hay búsqueda
  $sqlFut = "SELECT nroFut, anioFut, fecHorIng, solicito, estado,codCoordinador FROM fut WHERE CodDocente = ?";
  $stmtFut = $conexion->prepare($sqlFut);
  $stmtFut->bind_param("i", $codSoli);
  $stmtFut->execute();
  $resultFut = $stmtFut->get_result();
  // Cargar los FUTs si hay
  while ($rowFut = $resultFut->fetch_assoc()) {
    $futEncontrados[] = $rowFut;
  }
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
        <img src="../../src/images/Logo.ico" alt="logo" />
      </div>

      <div class="user-info">
        <img src="https://cdn-icons-png.flaticon.com/512/7816/7816916.png" alt="user" />
        <p><?php echo $nombres . ' ' . $apPaterno . ' ' . $apMaterno; ?></p>
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
            <span class="nav-text">Tramites</span>
          </a>
        </li>

        <li class="nav-item">
          <a href="pages/estado.php">
            <i class="fa-solid fa-chart-simple nav-icon"></i>
            <span class="nav-text">Estado</span>
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
        <form class="search-box" method="POST" action="">
          <input type="text" name="nroFut" placeholder="Buscar número de FUT..." required />
          <button type="submit" aria-label="Buscar"><i class="bx bx-search"></i></button>
        </form>
      </div>

      <div class="upcoming-events">
        <h1>FUTs Asignados</h1>
        <?php if (!empty($mensaje)): ?>
          <p><?php echo $mensaje; ?></p>
        <?php endif; ?>

        <div class="fut-container">
          <?php if (!empty($futEncontrados)): ?>
            <?php foreach ($futEncontrados as $rowFut): ?>
              <div class="card fut-card">
                <p><strong>Número FUT:</strong> <?php echo $rowFut['nroFut']; ?></p>
                <p><strong>Año FUT:</strong> <?php echo $rowFut['anioFut']; ?></p>
                <p><strong>Fecha y Hora de Ingreso:</strong> <?php echo $rowFut['fecHorIng']; ?></p>
                <p><strong>Solicitud:</strong> <?php echo $rowFut['solicito']; ?></p>
                <p><strong>Estado:</strong>
                  <?php
                  if ($rowFut['estado'] == 'A') {
                    echo 'Aprobado';
                  } elseif ($rowFut['estado'] == 'D') {
                    echo 'Desaprobado';
                  } elseif ($rowFut['estado'] == 'H') {
                    echo 'Habilitado';
                  }
                  ?>
                </p>
                <p><strong>código de coordinador:</strong> <?php echo $rowFut['codCoordinador']; ?></p>

                <!-- Botón para enviar datos de este FUT -->
                <form action="pages/formularioFUT.php" method="post">
                  <input type="hidden" name="nroFut" value="<?php echo $rowFut['nroFut']; ?>">
                  <input type="hidden" name="anioFut" value="<?php echo $rowFut['anioFut']; ?>">
                  <input type="hidden" name="fecHorIng" value="<?php echo $rowFut['fecHorIng']; ?>">
                  <input type="hidden" name="solicito" value="<?php echo $rowFut['solicito']; ?>">
                  <input type="hidden" name="estado" value="<?php echo $rowFut['estado']; ?>">
                  <button type="submit" class="fut-button">Revisar FUT</button>
                </form>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p>No hay FUTs asignados.</p>
          <?php endif; ?>
          <br>
        </div>
      </div>
    </div>
  </section>
</body>

</html>