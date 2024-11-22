<?php
session_start();
$codSoli = $_SESSION['codLogin'];
include 'formulario_fut/php/db_conexion.php';

// Obtener datos del solicitante y el codigo de especialidad del coordinador
$sqlSolicitante = "SELECT nombres, apPaterno, apMaterno, codEsp FROM personal WHERE codLogin = ?";
$stmtSolicitante = $conexion->prepare($sqlSolicitante);
$stmtSolicitante->bind_param("i", $codSoli);
$stmtSolicitante->execute();
$resultSolicitante = $stmtSolicitante->get_result();
$rowSolicitante = $resultSolicitante->fetch_assoc();
$nombres = $rowSolicitante['nombres'];
$apPaterno = $rowSolicitante['apPaterno'];
$apMaterno = $rowSolicitante['apMaterno'];
$codEspCoordinador = $codSoli;

// Recibir el termino de busqueda
$searchTerm = isset($_POST['search']) ? trim($_POST['search']) : '';
$estado = isset($_POST['estado']) ? $_POST['estado'] : ''; // Recibir el estado seleccionado

// Consulta para obtener los datos del FUT con o sin filtro de busqueda y aplicando los filtros adicionales
$sqlFut = "SELECT f.nroFut, f.anioFut, f.fecHorIng, f.solicito, f.estado, s.codEsp, f.codCoordinador
           FROM fut f
           JOIN solicitante s ON f.codSoli = s.codLogin
           JOIN personal p ON p.codLogin = ?
           WHERE s.codEsp = p.codEsp AND s.codEsp = p.codEsp";

// Si hay un termino de busqueda
if (!empty($searchTerm)) {
    $searchTerm = "%$searchTerm%";
    $sqlFut .= " AND f.nroFut LIKE ?";
}

// Si hay un estado seleccionado
if (!empty($estado)) {
    $sqlFut .= " AND f.estado = ?";
}

$stmtFut = $conexion->prepare($sqlFut);

// Vincular parametros a la consulta
if (!empty($searchTerm) && !empty($estado)) {
    $stmtFut->bind_param("iss", $codEspCoordinador, $searchTerm, $estado); //por busqueda y estado
} elseif (!empty($searchTerm)) {
    $stmtFut->bind_param("is", $codEspCoordinador, $searchTerm); //por busqueda
} elseif (!empty($estado)) {
    $stmtFut->bind_param("is", $codEspCoordinador, $estado); //por estado
} else {
    $stmtFut->bind_param("i", $codEspCoordinador); //sin filtros
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
  <link href="./Pages_Dash/Logo.ico" rel="icon">
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
        <img src="./Pages_Dash/Logo.ico" alt="logo" />
      </div>

      <div class="user-info">
        <img src="https://cdn-icons-png.flaticon.com/512/7816/7816916.png" alt="user" />
        <p><?php echo $nombres . ' ' . $apPaterno . ' ' . $apMaterno; ?></p>
      </div>

      <ul>
        <li class="nav-item">
          <a href="./Pages_Dash/user.php">
            <i class="fa fa-user nav-icon"></i>
            <span class="nav-text">Cuenta</span>
          </a>
        </li>

        <li class="nav-item active">
          <a href="../dashboardCoordinador/home.php">
            <i class="fa-solid fa-table nav-icon"></i>
            <span class="nav-text">Tablero</span>
          </a>
        </li>

        <li class="nav-item">
          <a href="./formulario_fut/formularioFUT.php">
            <i class="fa fa-arrow-trend-up nav-icon"></i>
            <span class="nav-text">Tramite</span>
          </a>
        </li>

        <li class="nav-item">
          <a href="./Estado_fut/estado.php">
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
        <form class="search-box" method="POST" action="">
          <input type="text" name="search" placeholder="Buscar por número de FUT..." />
          <button type="submit"><i class="bx bx-search"></i></button>
        </form>
      </div>

      <div class="upcoming-events">
        <h1>Tablero</h1>

        <h2>FUTs del Alumno</h2>
        <div class="input-row">
          <div class="especialidad">
            <div class="form-group">
        <button onclick="window.location.href='../../formularios/formulariodocs/formulariosdocs.php'" class="fut-button">Subir archivos</button>
        <form method="POST" action="">
            <label for="estado">Filtrar por estado:</label>
            <select id="estado" name="estado" onchange="this.form.submit()">
                <option value="" disabled selected>Seleccionar estado</option>
                <option value="H" <?php echo (isset($_POST['estado']) && $_POST['estado'] == 'H') ? 'selected' : ''; ?>>Habilitado</option>
                <option value="A" <?php echo (isset($_POST['estado']) && $_POST['estado'] == 'A') ? 'selected' : ''; ?>>Aprobado</option>
                <option value="D" <?php echo (isset($_POST['estado']) && $_POST['estado'] == 'D') ? 'selected' : ''; ?>>Desaprobado</option>
                <option value="R" <?php echo (isset($_POST['estado']) && $_POST['estado'] == 'R') ? 'selected' : ''; ?>>Rechazado</option>
                <option value="C" <?php echo (isset($_POST['estado']) && $_POST['estado'] == 'C') ? 'selected' : ''; ?>>Cerrado</option>
            </select>
        </form>
        <form method="POST" action="">
            <button type="submit" name="clear_filter" class="fut-button">Quitar Filtro</button>
        </form>
    </div>
<?php
// Verificar si se hizo clic en el botón para quitar el filtro
if (isset($_POST['clear_filter'])) {
    // Eliminar el valor del filtro de estado
    unset($_POST['estado']);
    header("Location: " . $_SERVER['PHP_SELF']); // Recargar la página
    exit();
} ?>
          </div>
        </div>

        <div class="fut-container">
          <?php while ($rowFut = $resultFut->fetch_assoc()) { ?>
            <div class="fut-card">
              <div class="fut-details">
                <p><strong>Número FUT:</strong> <?php echo $rowFut['nroFut']; ?></p>
                <p><strong>Año FUT:</strong> <?php echo $rowFut['anioFut']; ?></p>
                <p><strong>Fecha y Hora de Ingreso:</strong> <?php echo $rowFut['fecHorIng']; ?></p>
                <p><strong>Solicitud:</strong> <?php echo $rowFut['solicito']; ?></p>
                <p><strong>Estado:</strong>
                  <?php
                    switch ($rowFut['estado']) {
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
              </div>
                <form action="Asignar_fut/index.php" method="post" class="fut-form">
                  <input type="hidden" name="nroFut" value="<?php echo $rowFut['nroFut']; ?>">
                  <input type="hidden" name="anioFut" value="<?php echo $rowFut['anioFut']; ?>">
                  <input type="hidden" name="fecHorIng" value="<?php echo $rowFut['fecHorIng']; ?>">
                  <input type="hidden" name="solicito" value="<?php echo $rowFut['solicito']; ?>">
                  <input type="hidden" name="estado" value="<?php echo $rowFut['estado']; ?>">
                  <input type="hidden" name="codCoordinador" value="<?php echo $codSoli; ?>">
                
                  <?php if ($rowFut['codCoordinador'] == null): ?>
                    <button type="submit" class="fut-button">Asignar Docente</button>
                  <?php else: ?>
                    <button type="button" class="fut-button"> Docente ya asignado</button>
                    <button type="submit" formaction="Gestionar_fut/gestionar.php" class="fut-button">Gestionar FUT</button>
                  <?php endif; ?>
                </form>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </section>
</body>

</html>
