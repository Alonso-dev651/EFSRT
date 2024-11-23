<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
  <link rel="stylesheet" href="../style2.css">
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
          <p><strong>Estado:</strong> <?php echo htmlspecialchars($_POST['estado']); ?></p>
          <p><strong>Coordinador:</strong> <?php echo htmlspecialchars($_POST['codCoordinador']); ?></p>
        </div>

        <form action="procesar_gestion.php" method="post">
          <input type="hidden" name="nroFut" value="<?php echo htmlspecialchars($_POST['nroFut']); ?>">
          <input type="hidden" name="anioFut" value="<?php echo htmlspecialchars($_POST['anioFut']); ?>">
          <input type="hidden" name="fecHorIng" value="<?php echo htmlspecialchars($_POST['fecHorIng']); ?>">
          <input type="hidden" name="solicito" value="<?php echo htmlspecialchars($_POST['solicito']); ?>">
          <input type="hidden" name="estado" value="<?php echo htmlspecialchars($_POST['estado']); ?>">
          <input type="hidden" name="codCoordinador" value="<?php echo htmlspecialchars($_POST['codCoordinador']); ?>">


          <div class="button-row">
            <div class="form-group">
              <button type="button" class="fut-button">Cerrar FUT</button>
              <button type="button" class="fut-button">Rechazar FUT</button>
              <button type="button" class="btn-cancel" onclick="window.history.back();">Cancelar</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
</body>

</html>