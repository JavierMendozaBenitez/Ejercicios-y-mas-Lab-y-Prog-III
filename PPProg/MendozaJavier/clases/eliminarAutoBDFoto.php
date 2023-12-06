<?php

require_once('./clases/AutoBD.php');


$exito = false;


function mostrarAutosBorrados()
{
    
    $host = 'localhost';
    $dbname = 'garage_bd';
    $username = 'tu_usuario';
    $password = 'tu_contraseña';

    try {
        $conexion = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        
        $autosBorrados = MendozaJavier\AutoBD::traerBorrados($conexion);
        
        echo '<table border="1">';
        echo '<tr><th>Patente</th><th>Marca</th><th>Color</th><th>Precio</th><th>Imagen</th></tr>';
        foreach ($autosBorrados as $autoBorrado) {
            echo '<tr>';
            echo '<td>' . $autoBorrado->getPatente() . '</td>';
            echo '<td>' . $autoBorrado->getMarca() . '</td>';
            echo '<td>' . $autoBorrado->getColor() . '</td>';
            echo '<td>' . $autoBorrado->getPrecio() . '</td>';
            echo '<td><img src="' . $autoBorrado->getPathFoto() . '" alt="Imagen del auto"></td>';
            echo '</tr>';
        }
        echo '</table>';
    } catch (PDOException $e) {
        echo 'Error en la conexión a la base de datos';
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    mostrarAutosBorrados(); 
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['auto_json'])) {
        
        $autoData = json_decode($_POST['auto_json'], true);

        
        if ($autoData !== null && isset($autoData['patente'])) {
            
            $host = 'localhost';
            $dbname = 'garage_bd';
            $username = 'tu_usuario';
            $password = 'tu_contraseña';

            try {
                $conexion = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
                $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $auto = new MendozaJavier\AutoBD(
                    $autoData['patente'],
                    $autoData['marca'] ?? '', 
                    $autoData['color'] ?? '',
                    $autoData['precio'] ?? 0,
                    $autoData['pathFoto'] ?? ''
                );
                $exito = $auto->eliminar($conexion);

                if ($exito) {
                    $auto->guardarEnArchivo();
                }

                $respuesta = [
                    'exito' => $exito,
                    'mensaje' => $exito ? 'Auto eliminado correctamente' : 'Error al eliminar el auto',
                ];

                header('Content-Type: application/json');
                echo json_encode($respuesta);
            } catch (PDOException $e) {
                http_response_code(500); 
                echo json_encode(['exito' => false, 'mensaje' => 'Error en la conexión a la base de datos']);
            }
        } else {
            echo json_encode(['exito' => false, 'mensaje' => 'Formato JSON incorrecto o falta la patente']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['exito' => false, 'mensaje' => 'Falta el parámetro auto_json']);
    }
} else {
    http_response_code(405); 
    echo json_encode(['exito' => false, 'mensaje' => 'Método no permitido']);
}
