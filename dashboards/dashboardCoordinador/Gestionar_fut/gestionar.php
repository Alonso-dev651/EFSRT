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


// Obtener datos del coordinador
$sqlSolicitante = "SELECT nombres, apPaterno, apMaterno FROM personal WHERE codLogin = ?";
$stmtSolicitante = $conexion->prepare($sqlSolicitante);
$stmtSolicitante->bind_param("i", $codCoordinador);
$stmtSolicitante->execute();
$resultSolicitante = $stmtSolicitante->get_result();
$rowSolicitante = $resultSolicitante->fetch_assoc();

$nombres = $rowSolicitante['nombres'];
$apPaterno = $rowSolicitante['apPaterno'];
$apMaterno = $rowSolicitante['apMaterno'];

// Consulta para obtener los datos del solicitante y el tipo de trámite basado en nroFut
$query = "SELECT s.apPaterno, s.apMaterno, s.nombres, s.tipoDocu, s.nroDocu, s.codModular, s.telf, s.celular, s.correoJP, s.correoPersonal, s.direccion, s.anioIngreso, s.anioEgreso, e.nomEsp, f.codTT, f.solicito, f.descripcion, f.fecHoraAsignaDocente, f.estado, f.descCoorCierraFUT, f.archivo_pdf, f.codDocente, tt.descTT
          FROM solicitante s 
          INNER JOIN fut f ON s.codLogin = f.codSoli
          INNER JOIN especialidad e ON s.codEsp = e.codEsp
          LEFT JOIN tipoTramite tt ON f.codTT = tt.codTT
          WHERE f.nroFut = ?;";

$stmt = mysqli_prepare($conexion, $query);
mysqli_stmt_bind_param($stmt, 'i', $nroFut);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $apPaternoSoli, $apMaternoSoli, $nombresSoli, $tipoDocu, $nroDocu, $codModular, $telf, $celular, $correoJP, $correoPersonal, $direccion, $anioIngreso, $anioEgreso, $nomEsp, $codTTSeleccionado, $solicito, $descripcion, $fecHoraAsignaDocente,$estado, $desCoorCierraFUT, $archivo_pdf, $codDocente, $descTT);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);


//Obtener datos de Docente
$sqlDocente = "SELECT nombres, apPaterno, apMaterno FROM personal WHERE codLogin = ?";
$stmtDocente = $conexion->prepare($sqlDocente);
$stmtDocente->bind_param("i", $codDocente);
$stmtDocente->execute();
$resultDocente = $stmtDocente->get_result();
$rowDocente = $resultDocente->fetch_assoc();

$nombresDocente = $rowDocente['nombres'];
$apPaternoDocente = $rowDocente['apPaterno'];
$apMaternoDocente = $rowDocente['apMaterno'];
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
          <p><strong>Fecha y Hora de Ingreso:</strong> <?php echo htmlspecialchars($_POST['fecHorIng']); ?></p>
          <p><strong>Año FUT:</strong> <?php echo htmlspecialchars($_POST['anioFut']); ?></p>
          <p><strong>Solicitante:</strong> <?php echo $nombresSoli . ' ' . $apPaternoSoli . ' ' . $apMaternoSoli; ?></p>
          <p><strong>Area Académica:</strong> <?php echo $nomEsp; ?></p>
          <p><strong>Tipo de trámite:</strong>

                        <?php if (!empty($descTT)): ?>
                                <?php echo $descTT; ?>
                        <?php else: ?>
                            No hay tipo tramite asignado, debe resolverse en la base de datos.
                        <?php endif; ?></p>
          <p><strong>Solicitud:</strong> <?php echo htmlspecialchars($_POST['solicito']); ?></p>
          <p><strong>Detalle:</strong> <?php echo $descripcion; ?></p>
          <?php if(empty($fecHoraAsignaDocente)){ ?><p><strong>Docente asignado:</strong> Sin asignar</p><?php }else{?> <p><strong>Docente asignado:</strong> <?php echo $nombresDocente . ' ' . $apPaternoDocente . ' ' . $apMaternoDocente; ?></p> <?php } ?>
          <p><strong>Estado:</strong>
                  <?php
                    switch ($estado) {
                      case 'H':
                        echo 'Habilitado';
                        break;
                      case 'A':
                        echo 'Aprobado';
                        break;
                    //   case 'D':
                    //     echo 'Desaprobado';
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
          <p><strong>Comentario:</strong> <?php echo $comentario; ?></p>
                        <p><strong>Documento: </strong>

                        <?php if (!empty($archivo_pdf)): ?>
                                <a href="../../dashboardDocente/pages/uploads/<?php echo $archivo_pdf; ?>" target="_blank">Abrir archivo</a>
                        <?php else: ?>
                            No hay documento subido
                        <?php endif; ?></p>
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
                <textarea id="Coordescripcion" name="Coordescripcion" rows="4" cols="50" placeholder="Ingrese una descripcion"><?php echo htmlspecialchars($desCoorCierraFUT ?? '')?></textarea>
            </div>
          
            <div class="button-row">
                <div class="form-group">
                    <button type="submit" class="fut-button" name="accion" value="cerrar" id="cerrarBtn" disabled>Cerrar FUT</button>
                    <button type="submit" class="fut-button" name="accion" value="rechazar" id="rechazarBtn" disabled>Rechazar FUT</button>
                    <button type="button" class="btn-cancel" onclick="window.location.href='../../dashboardCoordinador/home.php';">Cancelar</button>
                </div>
            </div>
        </form>
      </div>
    </div>
    
    <!--SE BORRO LA SECCION ESA DEL CIRCULO, A PEDIDO DEL TICHER-->
  </section>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const textarea = document.getElementById("Coordescripcion");
            const cerrarBtn = document.getElementById("cerrarBtn");
            const rechazarBtn = document.getElementById("rechazarBtn");
            function checkTextarea() {
                const hasText = textarea.value.trim().length > 0;
                cerrarBtn.disabled = !hasText;
                rechazarBtn.disabled = !hasText;
            }
            checkTextarea();
            textarea.addEventListener("input", checkTextarea);
        });
    </script>

</body>
</html>
