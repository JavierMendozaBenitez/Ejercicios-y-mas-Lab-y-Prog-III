<?php
require_once './clases/Ciudad.php';
use Mendoza\Javier\Ciudad;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : null;
    $pais = isset($_POST['pais']) ? $_POST['pais'] : null;

    $ciudades = Ciudad::traer();

    $ciudadesFiltradas = [];

    foreach ($ciudades as $ciudad) {
        if (
            ($nombre === null || stristr($ciudad->nombre, $nombre)) &&
            ($pais === null || stristr($ciudad->pais, $pais))
        ) {
            $ciudadesFiltradas[] = $ciudad;
        }
    }

    if (count($ciudadesFiltradas) > 0) {
        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Nombre</th>';
        echo '<th>Población</th>';
        echo '<th>País</th>';
        echo '<th>Foto</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        foreach ($ciudadesFiltradas as $ciudad) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($ciudad->nombre) . '</td>';
            echo '<td>' . htmlspecialchars($ciudad->poblacion) . '</td>';
            echo '<td>' . htmlspecialchars($ciudad->pais) . '</td>';
            if (!empty($ciudad->pathFoto)) {
                echo '<td><img src="' . htmlspecialchars($ciudad->pathFoto) . '" alt="Foto de la ciudad"></td>';
            } else {
                echo '<td>No hay foto disponible</td>';
            }
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        echo 'No se encontraron ciudades que coincidan con los parámetros proporcionados.';
    }
}
