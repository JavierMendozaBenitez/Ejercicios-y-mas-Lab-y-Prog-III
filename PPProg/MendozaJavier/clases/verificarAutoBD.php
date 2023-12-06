<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['obj_auto'])) {
        
        $objAuto = json_decode($_POST['obj_auto'], true);

        
        if ($objAuto !== null && isset($objAuto['patente'])) {
            
            require_once('./clases/AutoBD.php');

            
            $host = 'localhost';
            $dbname = 'garage_bd';
            $username = 'tu_usuario';
            $password = 'tu_contraseña';

            try {
                $conexion = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
                $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $autos = MendozaJavier\AutoBD::traer($conexion);
                
                foreach ($autos as $auto) {
                    if ($auto->getPatente() === $objAuto['patente']) {
                        
                        header('Content-Type: application/json');
                        echo $auto->toJSON();
                        exit(); 
                    }
                }
                
                header('Content-Type: application/json');
                echo '{}';
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
        echo json_encode(['exito' => false, 'mensaje' => 'Falta el parámetro obj_auto']);
    }
} else {
    http_response_code(405); 
    echo json_encode(['exito' => false, 'mensaje' => 'Método no permitido']);
}
