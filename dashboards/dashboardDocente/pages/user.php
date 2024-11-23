<?php
session_start(); // Iniciar sesión

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
$sql = "SELECT nombres, apPaterno, apMaterno, tipoDocu, nroDocu, telf, celular, correoJP, correoPersonal, direccion, codDis, codEsp, estable, codigoPlaza, estado 
        FROM personal WHERE codLogin = ? ORDER BY codLogin DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $codLogin);
$stmt->execute();
$resultado = $stmt->get_result();


// CÓDIGO PARA OBTENER NOMBRES Y APELLIDOS DEL DOCENTE PARA PODER IMPRIMIRSE EN SU DASHBOARD PERFIL DE USUARIO
$sqlDocente = "SELECT nombres, apPaterno, apMaterno FROM personal WHERE codLogin = ?";
$stmtDocente = $conn->prepare($sqlDocente);
$stmtDocente->bind_param("i", $codLogin);
$stmtDocente->execute();
$resultDocente = $stmtDocente->get_result();

$rowDocente = $resultDocente->fetch_assoc();
$nombres = $rowDocente['nombres'];
$apPaterno = $rowDocente['apPaterno'];
$apMaterno = $rowDocente['apMaterno'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
  <link rel="stylesheet" href="../styles/default_user.css">
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
        <li class="nav-item active">
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

        <li class="nav-item">
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
        <div class="user-profile">
          <h1>Perfil de Usuario</h1>
          <div class="user-container">
            <?php
            if ($resultado->num_rows > 0) {
              // Mostrar los datos de la tabla
              while ($fila = $resultado->fetch_assoc()) {
                // Captura la especialidad mediante el codigo de especialidad y lo muestra
                $codEsp = $fila['codEsp'];
                $sqlEsp = "SELECT nomEsp FROM especialidad WHERE codEsp = ?";
                $stmtEsp = $conn->prepare($sqlEsp);
                $stmtEsp->bind_param("i", $codEsp);
                $stmtEsp->execute();
                $resultEsp = $stmtEsp->get_result();
                $filaEsp = $resultEsp->fetch_assoc();
                $nomEsp = $filaEsp['nomEsp'];

                // Capturar el código del distrito
                $codDis = $fila['codDis'];

                // Consulta para obtener el nombre del departamento, provincia y distrito
                $sqlUbigeo = "SELECT nomDptoUbi, nomProvUbi, nomDisUbi FROM ubigeo WHERE codUbi = ?";
                $stmtUbigeo = $conn->prepare($sqlUbigeo);
                $stmtUbigeo->bind_param("s", $codDis);
                $stmtUbigeo->execute();
                $resultUbigeo = $stmtUbigeo->get_result();
                $filaUbigeo = $resultUbigeo->fetch_assoc();
                $nomDpto = $filaUbigeo['nomDptoUbi'];
                $nomProv = $filaUbigeo['nomProvUbi'];
                $nomDis = $filaUbigeo['nomDisUbi'];


            ?>
                <div class="profile-container">
                  <img src="https://cdn-icons-png.flaticon.com/512/7816/7816916.png" alt="user" />
                  <div>
                    <?php
                    echo "<p><strong>Nombres:</strong>" . $fila['nombres'] . "</p>";
                    echo "<p><strong>Apellido Paterno:</strong>" . $fila['apPaterno'] . "</p>";
                    echo "<p><strong>Apellido Materno:</strong>" . $fila['apMaterno'] . "</p>";
                    ?>
                  </div>
                </div>
                <div class="data-container">
                  <div class="data-column">
                    <?php
                    echo "<div><span><strong>Tipo de Documento:</strong></span><p>" . $fila['tipoDocu'] . "</p></div>";
                    echo "<div><span><strong>Teléfono:</strong></span><p> " . $fila['telf'] . "</p></div>";
                    echo "<div><span><strong>Celular:</strong></span><p> " . $fila['celular'] . "</p></div>";
                    echo "<div><span><strong>Especialidad:</strong></span><p> " . $nomEsp . "</p></div>";
                    ?>
                  </div>
                  <div class="data-column">
                    <?php
                    echo "<div><span><strong>Número de Documento:</strong></span><p>" . $fila['nroDocu'] . "</p></div>";
                    echo "<div><span><strong>Correo JP:</strong></span><p> " . $fila['correoJP'] . "</p></div>";
                    echo "<div><span><strong>Correo Personal:</strong></span><p> " . $fila['correoPersonal'] . "</p></div>";
                    echo "<div><span><strong>Dirección:</strong></span><p> " . $fila['direccion'] . "</p></div>";
                    ?>
                  </div>
                  <div class="data-column">
                    <?php
                    echo "<div><span><strong>Código de Distrito:</strong></span><p> " . $fila['codDis'] . "</p></div>";
                    echo "<div><span><strong>Departamento:</strong></span><p>" . $nomDpto . "</p></div>";
                    echo "<div><span><strong>Provincia:</strong></span><p>" . $nomProv . "</p></div>";
                    echo "<div><span><strong>Distrito:</strong></span><p>" . $nomDis . "</p></div>";
                    ?>
                  </div>
                </div>
            <?php
              }
            } else {
              echo "<p>No se encontraron datos para este usuario.</p>";
            }
            // Cerrar la conexión
            $stmt->close();
            $conn->close();
            ?>
          </div>
        </div>
      </div>
    </div>
  </section>
</body>

</html>