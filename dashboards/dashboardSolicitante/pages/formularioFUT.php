<?php
session_start();
include '../src/php/nuevoFut.php';
$codLogin = $_SESSION['codLogin'];
include '../src/php/db_conexion.php';

// Consulta para obtener los datos del solicitante
$query = "SELECT apPaterno, apMaterno, nombres, tipoDocu, nroDocu, codModular, telf, celular, correoJP, correoPersonal, direccion, anioIngreso, anioEgreso, codEsp FROM solicitante WHERE codLogin = ?";
$stmt = mysqli_prepare($conexion, $query);
mysqli_stmt_bind_param($stmt, 'i', $codLogin);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $apPaterno, $apMaterno, $nombres, $tipoDocu, $nroDocu, $codModular, $telf, $celular, $correoJP, $correoPersonal, $direccion, $anioIngreso, $anioEgreso, $codEsp);
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
    <link rel="stylesheet" href="../styles/processedz.css">
    <link href="../../../src/images/Logo.ico" rel="icon">
    <script defer src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script defer src="../main.js"></script>
    <script defer src="../src/js/fechaHora.js"></script>
    <title>Completa tu Solicitud</title>
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

                <li class="nav-item">
                    <a href="../home.php">
                        <i class="fa-solid fa-table nav-icon"></i>
                        <span class="nav-text">Tablero</span>
                    </a>
                </li>

                <li class="nav-item active">
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
            <h2 class="title_formfut">FORMULARIO FUT</h2>
            <form class="form-solicitud" method="POST" action="../src/proceso_fut.php" enctype="multipart/form-data">
                <div class="input-row" style="margin-bottom: 10px">
                    <div>
                        <p>Fecha: <span id="current-date"></span></p>
                        <p>Hora: <span id="current-time"></span></p>
                        
                    </div>
                              <p>Nro de FUT: <?php echo 'ASD'.$nroFut; ?></p>
                </div>

                <div class="input-row">
                    <div class="form-group">
                        <label for="apPaterno">Apellido Patddderno</label>
                        <input class="input_form" type="text" id="apPaterno" name="apPaterno" value="<?php echo $apPaterno; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="apMaterno">Apellido Materno</label>
                        <input class="input_form" type="text" id="apMaterno" name="apMaterno" value="<?php echo $apMaterno; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="nombres">Nombres</label>
                        <input class="input_form" type="text" id="nombres" name="nombres" value="<?php echo $nombres; ?>" required>
                    </div>
                </div>

                <div class="input-row">
                    <div class="form-group">
                        <label for="tipoDocu">Tipo de documento</label>
                        <select class="select_form" id="tipoDocu" name="tipoDocu" required>
                            <option value="DNI" <?php echo ($tipoDocu == 'DNI') ? 'selected' : ''; ?>>DNI</option>
                            <option value="CEX" <?php echo ($tipoDocu == 'CEX') ? 'selected' : ''; ?>>CEX</option>
                            <option value="PAS" <?php echo ($tipoDocu == 'PAS') ? 'selected' : ''; ?>>PAS</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="nroDocu">Nro Documento</label>
                        <input class="input_form" type="text" id="nroDocu" name="nroDocu" value="<?php echo $nroDocu; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="codModular">Código Modular</label>
                        <input class="input_form" type="text" id="codModular" name="codModular" value="<?php echo $codModular; ?>">
                    </div>
                </div>

                <div class="input-row">
                    <div class="form-group">
                        <label for="telf">Teléfono</label>
                        <input class="input_form" type="text" id="telf" name="telf" value="<?php echo $telf; ?>">
                    </div>

                    <div class="form-group">
                        <label for="celular">Celular</label>
                        <input class="input_form" type="text" id="celular" name="celular" value="<?php echo $celular; ?>">
                    </div>
                </div>

                <div class="input-row">
                    <div class="form-group">
                        <label for="correoJP">Correo Institucional:</label>
                        <input class="input_form" type="email" id="correoJP" name="correoJP" value="<?php echo $correoJP; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="correoPersonal">Correo Personal</label>
                        <input class="input_form" type="email" id="correoPersonal" name="correoPersonal" value="<?php echo $correoPersonal; ?>" required>
                    </div>
                </div>

                <div class="input-row">
                    <div class="form-group">
                        <label for="direccion">Dirección:</label>
                        <input class="input_form" type="text" id="direccion" name="direccion" value="<?php echo $direccion; ?>">
                    </div>

                    <div class="form-group">
                        <label for="direccion">Código de Especialidad:</label>
                        <input class="input_form" type="text" id="codEsp" name="codEsp" value="<?php echo $codEsp; ?>">
                    </div>
                </div>

                <div class="input-row">
                    <div class="form-group">
                        <label for="anioIngreso">Año Ingreso</label>
                        <input class="input_form" type="number" id="anioIngreso" name="anioIngreso" value="<?php echo $anioIngreso; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="anioEgreso">Año Egreso (Opcional)</label>
                        <input class="input_form" type="number" id="anioEgreso" name="anioEgreso" value="<?php echo $anioEgreso; ?>">
                    </div>
                </div>
                    <div class="input-row">
                        <div class="form-group">
                            <label for="tipoTramite">Tipo de Tramite</label>
                            <select id="tipoTramite" name="tipoTramite" required>
                                <option value="" disabled selected>Tramites</option>
                                <?php include '../src/php/mostrar_tipoTramites.php'; ?>
                            </select>
                        </div>
                    </div>
                <!-- Campo para subir archivo -->
                <div class="form-group">
                    <label for="archivo">Subir Archivo</label>
                    <input type="file" name="archivo" id="archivo" class="input_form">
                </div>

                <!-- Solicitud -->
                <div class="form-group">
                    <label for="solicitud">Solicitud</label>
                    <textarea class="solicitud" name="solicitud" id="solicitud" required></textarea>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción de Solicitud</label>
                    <textarea class="descripcion" name="descripcion" id="descripcion" required></textarea>
                </div>

                <button type="submit">Enviar Solicitud</button>
            </form>
        </div>
    </section>

    <script src="js/cargarDatos.js"></script>
    <script src="js/fechaHora.js"></script>

</body>

</html>
