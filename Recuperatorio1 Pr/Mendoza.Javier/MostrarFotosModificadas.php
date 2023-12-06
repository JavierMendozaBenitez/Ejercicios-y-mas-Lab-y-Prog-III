<?php
require_once './clases/Ciudad.php';
use Mendoza\Javier\Ciudad;

$imagenes = Ciudad::MostrarModificadas();

if (!empty($imagenes)) {
    echo '<table>';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Foto</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    foreach ($imagenes as $imagen) {
        echo '<tr>';
        echo '<td><img src="./ciudades/modificadas/' . htmlspecialchars($imagen) . '" alt="Imagen modificada"></td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
} else {
    echo 'No hay fotos modificadas disponibles.';
}
