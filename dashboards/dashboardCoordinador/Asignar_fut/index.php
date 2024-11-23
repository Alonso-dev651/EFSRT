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
}

// Obtener datos de aea
$sqlCodSoli = "SELECT codSoli FROM fut WHERE nroFut = ?";
$stmtCodSoli = $conexion->prepare($sqlCodSoli);
$stmtCodSoli->bind_param("i", $nroFut);
$stmtCodSoli->execute();
$resultCodSoli = $stmtCodSoli->get_result();
$rowCodSoli = $resultCodSoli->fetch_assoc();

$codSoli = $rowCodSoli['codSoli'];


// Obtener datos de especialidad
$sqlEspecialidad = "SELECT codEsp FROM solicitante WHERE codLogin = ?";
$stmtEspecialidad = $conexion->prepare($sqlEspecialidad);
$stmtEspecialidad->bind_param("i", $codSoli);
$stmtEspecialidad->execute();
$resultEspecialidad = $stmtEspecialidad->get_result();
$rowEspecialidad = $resultEspecialidad->fetch_assoc();

$codEspecialidad = $rowEspecialidad['codEsp'];



$sqlNEspecialidad = "SELECT codEsp, nomEsp FROM especialidad WHERE codEsp = ?";
$stmtNEspecialidad = $conexion->prepare($sqlNEspecialidad);
$stmtNEspecialidad->bind_param("i", $codEspecialidad);
$stmtNEspecialidad->execute();
$resultNEspecialidad = $stmtNEspecialidad->get_result();
$rowNEspecialidad = $resultNEspecialidad->fetch_assoc();

$nomEspecialidad = $rowNEspecialidad['nomEsp'];

$sqlDocente = "SELECT codLogin, correoJP FROM personal WHERE tipoPer = 'DOCENTE' and codEsp = '$codEspecialidad'";
$resultDocente = $conexion->query($sqlDocente);

if (!$resultDocente) {
  die("Error en la consulta de docentes: " . $conexion->error);
}

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
      <div class="search-and-check">
        <form class="search-box">
          <input type="text" placeholder="Buscar..." />
          <i class="bx bx-search"></i>
        </form>
      </div>

      <div class="upcoming-events">
        <div class="form-solicitud">
          <h1>Asignar Docente al FUT</h1>

          <div class="fut-info">
            <p><strong>Número FUT:</strong> <?php echo htmlspecialchars($nroFut); ?></p>
            <p><strong>Año FUT:</strong> <?php echo htmlspecialchars($anioFut); ?></p>
            <p><strong>Especialidad:</strong> <?php echo htmlspecialchars($nomEspecialidad); ?></p>
            <p><strong>Fecha y Hora de Ingreso:</strong> <?php echo htmlspecialchars($fecHorIng); ?></p>
            <p><strong>Solicitud:</strong> <?php echo htmlspecialchars($solicito); ?></p>
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
            <?php echo htmlspecialchars($nombres . ' ' . $apPaterno . ' ' . $apMaterno); ?>
          </div>

          <form action="asignar_fut.php" method="post" class="input-row">
            <input type="hidden" name="nroFut" value="<?php echo $nroFut; ?>">
            <input type="hidden" name="anioFut" value="<?php echo $anioFut; ?>">


            <div class="form-group">
              <label for="descripcion">Descripción</label>
              <textarea name="descripcion" id="descripcion" rows="3" required></textarea>
            </div>

            <div class="form-group">
              <label for="docente">Seleccionar Docente</label>
              <select name="docente" id="docente" required>
                <option value="" disabled selected>Seleccione un docente</option>
                <?php
                if ($resultDocente->num_rows > 0) {
                  while ($rowDocente = $resultDocente->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($rowDocente['codLogin']) . "'>" . htmlspecialchars($rowDocente['correoJP']) . "</option>";
                  }
                } else {
                  echo "<option value='' disabled>No hay docentes disponibles</option>";
                }
                ?>
              </select>
            </div>

            <input type="number" name="coordinador" value="<?php echo $codCoordinador; ?>">




            <div class="buttons-row">
              <button type="submit" class="btn-submit">Asignar FUT al Docente</button>
              <button type="button" class="btn-cancel" onclick="window.location.href='../../dashboardCoordinador/home.php';">Cancelar</button>
            </div>
          </form>
        </div>

      </div>
    </div>
  </section>
</body>

</html>


<?php
$conexion->close();
?>