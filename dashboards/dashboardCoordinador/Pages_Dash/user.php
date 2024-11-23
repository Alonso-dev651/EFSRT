<?php

session_start(); // Iniciar sesión
$codSoli = $_SESSION['codLogin'];
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

// Consulta para obtener los datos del ingresante
$sql = "SELECT nombres, apPaterno, apMaterno, tipoDocu, nroDocu, telf, celular, correoJP, correoPersonal, direccion, codDis, codEsp 
        FROM personal 
        WHERE codLogin = ?
        ORDER BY codLogin DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $codLogin);
$stmt->execute();
$resultado = $stmt->get_result();
// CÓDIGO PARA OBTENER NOMBRES Y APELLIDOS DEL USUARIO PARA PODER IMPRIMIRSE EN

$sqlSolicitante = "SELECT nombres, apPaterno, apMaterno FROM personal WHERE codLogin = ?";
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
  <link rel="stylesheet" href="user2.css">
  <link href="Logo.ico" rel="icon">
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
          src="./Logo.ico"
          alt="logo" />
      </div>

      <div class="user-info">
        <img src="https://cdn-icons-png.flaticon.com/512/7816/7816916.png" alt="user" />
        <p><?php echo $nombres . ' ' . $apPaterno . ' ' . $apMaterno; ?></p>
      </div>
      <ul>
        <li class="nav-item active">
          <a href="../Pages_Dash/user.php">
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
        <div class="interaction-control-mobile interactions">
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
      </div>

      <div class="left-content">
        <div class="user-profile">
          <h1>User profile</h1>
          <div class="user-container">
            <div class="profile-container">
              <img src="https://cdn-icons-png.flaticon.com/512/7816/7816916.png" alt="user" />
              <?php
              if ($resultado->num_rows > 0) {
                // Mostrar los datos de la tabla
                while ($fila = $resultado->fetch_assoc()) {
                  echo "<p><strong>Nombres:</strong> " . $fila['nombres'] . "</p>";
                  echo "<p><strong>Apellido Paterno:</strong> " . $fila['apPaterno'] . "</p>";
                  echo "<p><strong>Apellido Materno:</strong> " . $fila['apMaterno'] . "</p>";
                  echo "<p><strong>Tipo de Documento:</strong> " . $fila['tipoDocu'] . "</p>";
                  echo "<p><strong>Número de Documento:</strong> " . $fila['nroDocu'] . "</p>";
                  echo "<p><strong>Teléfono:</strong> " . $fila['telf'] . "</p>";
                  echo "<p><strong>Celular:</strong> " . $fila['celular'] . "</p>";
                  echo "<p><strong>Correo JP:</strong> " . $fila['correoJP'] . "</p>";
                  echo "<p><strong>Correo Personal:</strong> " . $fila['correoPersonal'] . "</p>";
                  echo "<p><strong>Dirección:</strong> " . $fila['direccion'] . "</p>";
                  echo "<p><strong>Código de Distrito:</strong> " . $fila['codDis'] . "</p>";
                  // Captura la especialidad mediante el codigo de especialidad y lo muestra
                  $codEsp = $fila['codEsp'];
                  $sqlEsp = "SELECT nomEsp FROM especialidad WHERE codEsp = ?";
                  $stmtEsp = $conn->prepare($sqlEsp);
                  $stmtEsp->bind_param("i", $codEsp);
                  $stmtEsp->execute();
                  $resultEsp = $stmtEsp->get_result();
                  $filaEsp = $resultEsp->fetch_assoc();
                  $nomEsp = $filaEsp['nomEsp'];

                  echo "<p><strong>Especialidad:</strong> " . $nomEsp . "</p>";
                }
              } else {
                echo "<p>No se encontraron datos para este usuario.</p>";
              }

              $stmt->close();
              $conn->close();
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</body>

</html>