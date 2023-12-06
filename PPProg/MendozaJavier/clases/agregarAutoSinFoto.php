<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['auto_json'])) {
        
        $autoData = json_decode($_POST['auto_json'], true);
        
        if ($autoData !== null) {
            
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
                    $autoData['marca'],
                    $autoData['color'],
                    $autoData['precio']
                );

                
                $exito = $auto->agregar($conexion);

                $respuesta = [
                    'exito' => $exito,
                    'mensaje' => $exito ? 'Auto agregado correctamente' : 'Error al agregar el auto',
                ];

                header('Content-Type: application/json');
                echo json_encode($respuesta);
            } catch (PDOException $e) {
                
                http_response_code(500); 
                echo json_encode(['exito' => false, 'mensaje' => 'Error en la conexión a la base de datos']);
            }
        } else {
            
            http_response_code(400); 
            echo json_encode(['exito' => false, 'mensaje' => 'Formato JSON incorrecto']);
        }
    } else {
        
        http_response_code(400);
        echo json_encode(['exito' => false, 'mensaje' => 'Falta el parámetro auto_json']);
    }
} else {
    
    http_response_code(405); 
    echo json_encode(['exito' => false, 'mensaje' => 'Método no permitido']);
}
