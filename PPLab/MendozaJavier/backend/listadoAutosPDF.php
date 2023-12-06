<?php

require_once __DIR__ . '/vendor/autoload.php'; // Asegúrate de cargar la biblioteca MPDF

// Crear una instancia de MPDF
$mpdf = new \Mpdf\Mpdf();

// Crear el contenido HTML para el PDF
$html = '
<!DOCTYPE html>
<html>
<head>
    <style>
        h1 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <header>
        <div style="float: left">Mendoza Javier</div>
        <div style="float: right">Página: {PAGENO}</div>
    </header>
    <h1>Listado de Autos</h1>
    <table>
        <tr>
            <th>Patente</th>
            <th>Marca</th>
            <th>Color</th>
            <th>Precio</th>
            <th>Foto</th>
        </tr>';

// Conectar a la base de datos
$conexion = new PDO("mysql:host=localhost;dbname=garage_bd", "root", "");

// Consulta SQL para obtener datos de la tabla de autos
$consulta = $conexion->query("SELECT * FROM autos");

// Generar filas de la tabla con datos de la base de datos
while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
    $html .= '<tr>';
    $html .= '<td>' . $fila['patente'] . '</td>';
    $html .= '<td>' . $fila['marca'] . '</td>';
    $html .= '<td>' . $fila['color'] . '</td>';
    $html .= '<td>' . $fila['precio'] . '</td>';

    // Extraer el nombre de la foto de la base de datos
    $nombreFoto = basename($fila['foto']);

    // Ruta completa de la foto en el directorio 'autos/imagenes/'
    $rutaFoto = 'autos/imagenes/' . $nombreFoto;    
    $rutaFotoModificada = 'autosModificados/' . $nombreFoto;

    // Verificar si la foto existe en la ubicación de la base de datos
    if (file_exists($rutaFoto)) {
        $html .= '<td><img src="' . $rutaFoto . '" alt="Foto"></td>';
    }
    // Si la foto no existe en la ubicación de la base de datos pero existe en 'autosModificados/', mostrarla desde allí
    elseif (file_exists($rutaFotoModificada)) {
        $html .= '<td><img src="' . $rutaFotoModificada . '" alt="Foto"></td>';
    } else {
        $html .= '<td>Sin imagen</td>';
    }

    $html .= '</tr>';
}

// Cerrar la conexión a la base de datos
$conexion = null;

$html .= '
    </table>
    <footer>
        <div style="text-align: center">Fecha: ' . date('Y-m-d') . '</div>
    </footer>
</body>
</html>
';

// Especificar el contenido HTML para el PDF
$mpdf->WriteHTML($html);

// Generar el archivo PDF
$mpdf->Output();
