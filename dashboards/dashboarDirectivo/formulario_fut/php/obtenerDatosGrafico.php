<?php
function obtenerDatosGrafico() {
    // Configuración de la base de datos
    $host = "localhost";
    $dbname = "liveraco_efsrtBD";
    $username = "liveraco_pruebabd";
    $password = "JosePardo*2411";

    try {
        // Conexión a la base de datos
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Consulta para obtener los datos
        $sql = "SELECT MONTH(fecHoraAsignaDocente) mes,count(1) Cantidad from fut where fecHoraAsignaDocente is NOT null
                group by MONTH(fecHoraAsignaDocente)";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // Obtener datos en arrays separados para etiquetas y valores
        $etiquetas = [];
        $valores = [];
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $etiquetas[] = $fila['mes'];
            $valores[] = $fila['Cantidad'];
        }

        // Retornar los datos como un array
        return [
            "etiquetas" => $etiquetas,
            "valores" => $valores
        ];

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return null;
    }
}
?>
