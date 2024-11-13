<?php
session_start();
$codSoli = $_SESSION['codLogin'];
include 'formulario_fut/php/db_conexion.php';
include 'formulario_fut/php/obtenerDatosGrafico.php';

$datosGraficos = obtenerDatosGrafico();

// Para jalar los datos y imprimirse
$sqlSolicitante = "SELECT nombres, apPaterno, apMaterno,correoJP,correoPersonal,tipoDocu, nroDocu, direccion,codEsp, codLogin, telf, celular, anioIngreso, anioEgreso FROM solicitante";
$stmtSolicitante = $conexion->query($sqlSolicitante);

// Verificar si se encontraron resultados
if ($stmtSolicitante->num_rows > 0) {
    // Almacenar todos los resultados en un array
    $solicitantes = [];
    while ($fila = $stmtSolicitante->fetch_assoc()) {
        $solicitantes[] = $fila; // Agrega cada fila al array
    }
} else {
    echo "No se encontraron resultados";
}

// Para jalar los datos y mostrar del fut
$sqlFut = "SELECT nroFut, anioFut, fecHorIng, solicito,estado, codTT, codSoli FROM fut";
$stmtFut = $conexion->prepare($sqlFut);
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
    <link href="Icons_Dash/Logo.ico" rel="icon">
    <script defer src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script defer src="test.js"></script>
    <title>EFSRT Dashboard</title>
</head>

<body>
    <nav class="main-menu">
        <div>
            <div class="logo">
                <img src="Icons_Dash/Logo.ico" alt="logo" />
            </div>

            <div class="user-info">
                <img src="https://cdn-icons-png.flaticon.com/512/7816/7816916.png" alt="user" />
                <p><?php echo $nombres . ' ' . $apPaterno . ' ' . $apMaterno; ?></p>
            </div>

            <ul>
                <li class="nav-item">
                    <a href="Pages_Dash/user.php">
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
                    <!-- <a href="formulario_fut/formularioFUT.php"> -->
                    <a href="#">
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


    <?php
    // Captura la especialidad mediante el codigo de especialidad y lo muestra

    $codEsp = $fila['codEsp'];
    $sqlEsp = "SELECT nomEsp FROM especialidad WHERE codEsp = ?";
    $stmtEsp = $conexion->prepare($sqlEsp);
    $stmtEsp->bind_param("i", $codEsp);
    $stmtEsp->execute();
    $resultEsp = $stmtEsp->get_result();
    $filaEsp = $resultEsp->fetch_assoc();
    $nomEsp = $filaEsp['nomEsp'];



    $codMod = $fila['codTT'];
    $sqlMod = "select descTT from tipoTramite where codTT = ?";
    $stmtMod = $conexion->prepare($sqlMod);
    $stmtMod->bind_param("i", $cod);
    $stmtMod->execute();
    $resultMod = $stmtMod->get_result();
    $filaMod = $resultMod->fetch_assoc();
    $tramite = $filaMod['descTT'];
    ?>

    <section class="content">
        <div class="left-content">
            <div class="search-and-check">
                <form class="search-box">
                    <input type="text" placeholder="Buscar..." />
                    <i class="bx bx-search"></i>
                </form>
            </div>

            <div class="upcoming-events">
                <!-- Para mostrar el fut en el dashboard -->
                <div style="display: flex; justify-content: center; align-items: center;">
                    <h1>FUTs DEL ALUMNO</h1>
                </div>
                <div class="fut-container">
                    <?php
                    // Función para buscar solicitante por codSoli
                    function buscarSolicitantePorCodSoli($solicitantes, $codSoliS)
                    {
                        foreach ($solicitantes as $solicitante) {
                            if ($solicitante['codLogin'] == $codSoliS) {
                                return $solicitante;
                            }
                        }
                        return null; // Si no se encuentra ningún solicitante
                    }

                    while ($rowFut = $resultFut->fetch_assoc()) {
                        // Buscar el solicitante correspondiente al codSoli del FUT
                        $solicitante = buscarSolicitantePorCodSoli($solicitantes, $rowFut['codSoli']);
                        $codEsp = $solicitante['codEsp'];
                        // Consulta para obtener el nombre de la especialidad
                        $sqlEsp = "SELECT nomEsp FROM especialidad WHERE codEsp = ?";
                        $stmtEsp = $conexion->prepare($sqlEsp);
                        $stmtEsp->bind_param("i", $codEsp);
                        $stmtEsp->execute();
                        $resultEsp = $stmtEsp->get_result();
                        $filaEsp = $resultEsp->fetch_assoc();
                        $nomEsp = $filaEsp['nomEsp'] ?? "No asignada";
                    ?>
                        <div class="card fut-card">
                            <div class="fut-details">
                                <p><strong>Número FUT:</strong> <?php echo $rowFut['nroFut']; ?></p>
                                <p><strong>Solicitante:</strong>
                                    <?php echo $solicitante['nombres'] . " " . $solicitante['apPaterno'] . " " . $solicitante['apMaterno']; ?>
                                </p>
                                <p><strong>Asunto del FUT:</strong> <?php echo $rowFut['solicito']; ?></p>
                                <p><strong>Especialidad:</strong> <?php echo $nomEsp; ?></p>
                                <p><strong>Módulo:</strong> <?php echo $rowFut['codTT']; ?></p>
                                <p><strong>Estado:</strong> <?php echo $rowFut['estado'] == 'H' ? 'Habilitado' : 'Inhabilitado'; ?></p>
                                <p><strong>Fecha y Hora de Ingreso:</strong> <?php echo $rowFut['fecHorIng']; ?></p>
                                <p><strong>Año FUT:</strong> <?php echo $rowFut['anioFut']; ?></p>
                                <p><strong>Código Solicitante:</strong> <?php echo $rowFut['codSoli']; ?></p>
                            </div>

                            <div class="fut-form">
                                <button class="fut-button" onclick="toggleDetalles(this)">Ver detalles</button>
                            </div>

                            <div class="detalles" style="display:none;">
                                <div class="detalle-content">
                                    <?php if ($solicitante) { // Si se encontró un solicitante 
                                    ?>
                                        <p><strong>Tipo de Documento:</strong>
                                            <?php echo $solicitante['tipoDocu']; ?>
                                        </p>
                                        <p><strong>N.º de Documento:</strong>
                                            <?php echo $solicitante['nroDocu']; ?>
                                        </p>
                                        <p><strong>Correo Institucional:</strong>
                                            <?php echo $solicitante['correoJP']; ?>
                                        </p>
                                        <p><strong>Correo Personal:</strong>
                                            <?php echo $solicitante['correoPersonal']; ?>
                                        </p>
                                        <p><strong>N.º de Teléfono:</strong>
                                            <?php echo $solicitante['telf']; ?>
                                        </p>
                                        <p><strong>N.º de Celular:</strong>
                                            <?php echo $solicitante['celular']; ?>
                                        </p>
                                        <p><strong>Dirección:</strong>
                                            <?php echo $solicitante['direccion']; ?>
                                        </p>
                                        <p><strong>Año de Ingreso:</strong>
                                            <?php echo $solicitante['anioIngreso']; ?>
                                        </p>
                                        <p><strong>Año de Egreso:</strong>
                                            <?php echo $solicitante['anioEgreso']; ?>
                                        </p>
                                    <?php } else { ?>
                                        <p>No se encontró el solicitante correspondiente.</p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <script>
                    function toggleDetalles(button) {
                        // Encuentra el div de detalles (está en el mismo nivel que el botón)
                        var detallesDiv = button.closest('.fut-card').querySelector('.detalles');

                        // Si los detalles están ocultos, los mostramos
                        if (detallesDiv.style.display === "none") {
                            detallesDiv.style.display = "block";
                            button.textContent = "Ver menos"; // Cambiar texto a "Ver menos"
                        } else {
                            // Si ya están visibles, los ocultamos
                            detallesDiv.style.display = "none";
                            button.textContent = "Ver detalles"; // Cambiar texto a "Ver detalles"
                        }
                    }
                </script>


            </div>
        </div>

        <div class="right-content">
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
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartData = {
            <?php
            $etiquetas = $datosGraficos['etiquetas'];
            $valores = $datosGraficos['valores'];

            $mesNumero = $etiquetas;

            if (is_numeric($mesNumero)) {
                $mesNumero = intval($mesNumero); // Convertir a entero si es necesario

                $meses = [
                    1 => "enero",
                    2 => "febrero",
                    3 => "marzo",
                    4 => "abril",
                    5 => "mayo",
                    6 => "junio",
                    7 => "julio",
                    8 => "agosto",
                    9 => "septiembre",
                    10 => "octubre",
                    11 => "noviembre",
                    12 => "diciembre"
                ];

                if (isset($meses[$mesNumero])) {
                    $mes = $meses[$mesNumero];
                } else {
                    $mes = "Mes inválido";
                }

            } else {
                echo "Error: El valor de \$etiquetas no es un número válido.";
            }

            ?>
            labels: <?php echo json_encode($mes); ?>,
            data: <?php echo json_encode($valores); ?>,
        };

        const chart = document.getElementById("doughnut");
        const eventList = document.querySelector(".chart ul");

        var doughnut = new Chart(chart, {
            type: "doughnut",
            data: {
                labels: <?php echo json_encode($mes); ?>,
                datasets: [{
                    label: "Cantidad de FUTs",
                    data: <?php echo json_encode($valores); ?>,
                    backgroundColor: ["#582bac", "#b31a4d", "#e48e2c", "#4a920f"],
                    offset: 10,
                    hoverOffset: 8,
                    hoverBorderColor: "#9a999b",
                    borderWidth: 1,
                }, ],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: "#8b8a96",
                            font: {
                                size: 12,
                                weight: 600,
                            },
                        },
                    },
                },
                layout: {
                    padding: {
                        bottom: 10,
                    },
                },
            },
        });

        function population() {
            chartData.labels.forEach((label, i) => {
                let eachEvent = document.createElement("li");
                // Convertir el valor a número antes de calcular el porcentaje
                let porcentaje = (Number(chartData.data[i]) * 100).toFixed(2);
                eachEvent.innerHTML = `${label}: <span class="percentage">${porcentaje}%</span> `;
                eventList.appendChild(eachEvent);
            });
        }

        population();
    </script>
</body>

</html>