<?php
require_once './clases/Ciudad.php';
use Mendoza\Javier\Ciudad;


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['nombre'])) {
        // Verificar si la ciudad está en la base de datos
        $nombre = $_GET['nombre'];
        $ciudades = Ciudad::traer();

        $ciudadEncontrada = false;

        foreach ($ciudades as $ciudad) {
            if ($ciudad->nombre === $nombre) {
                $ciudadEncontrada = true;
                break;
            }
        }

        if ($ciudadEncontrada) {
            echo 'La ciudad está en la base de datos.';
        } else {
            echo 'La ciudad no está en la base de datos.';
        }
    } else {
        // Mostrar información de todas las ciudades borradas en una tabla HTML
        $ciudadesBorradas = file_get_contents('./archivos/ciudades_borradas.txt');
        $ciudadesBorradasArray = explode("\n", $ciudadesBorradas);

        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Id</th>';
        echo '<th>Nombre</th>';
        echo '<th>Pais</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        foreach ($ciudadesBorradasArray as $ciudadBorradaJson) {
            $ciudadBorradaData = json_decode($ciudadBorradaJson, true);

            if ($ciudadBorradaData) {
                echo '<tr>';
                echo '<td>' . $ciudadBorradaData['id'] . '</td>';
                echo '<td>' . $ciudadBorradaData['nombre'] . '</td>';
                echo '<td>' . $ciudadBorradaData['pais'] . '</td>';
                echo '</tr>';
            }
        }

        echo '</tbody>';
        echo '</table>';
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['ciudad_json']) && isset($_POST['accion']) && $_POST['accion'] === 'borrar') {
        // Decodificar el JSON
        $ciudadData = json_decode($_POST['ciudad_json'], true);

        if ($ciudadData) {
            // Crear una instancia de la Ciudad con los datos del JSON
            $ciudad = new Ciudad($ciudadData['id'], $ciudadData['nombre'], null, $ciudadData['pais'], null);

            // Borrar la ciudad de la base de datos
            $exitoBorrado = $ciudad->eliminar();

            if ($exitoBorrado) {
                // Invocar al método guardarEnArchivo
                Ciudad::guardarEnArchivo($ciudad);

                $response = [
                    'exito' => true,
                    'mensaje' => 'Ciudad borrada con éxito',
                ];
            } else {
                $response = [
                    'exito' => false,
                    'mensaje' => 'Hubo un problema al borrar la ciudad en la base de datos',
                ];
            }
        } else {
            $response = [
                'exito' => false,
                'mensaje' => 'Error en el formato del JSON de la ciudad',
            ];
        }
    } else {
        $response = [
            'exito' => false,
            'mensaje' => 'Parámetros incorrectos o faltantes',
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
