<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['auto_json'])) {
        
        $autoData = json_decode($_POST['auto_json'], true);
        
        if ($autoData !== null && isset($autoData['patente'])) {
            
            require_once('./clases/AutoBD.php');
            
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
                    $autoData['precio'] ?? 0
                );
                
                $exito = $auto->eliminar($conexion, $auto->getPatente());

                if ($exito) {
                    
                    $archivoJSON = './archivos/autos_eliminados.json';
                    $auto->guardarJSON($archivoJSON);
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
            
            http_response_code(400);
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
