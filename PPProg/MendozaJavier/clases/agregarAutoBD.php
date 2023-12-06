<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (
        isset($_POST['patente']) &&
        isset($_POST['marca']) &&
        isset($_POST['color']) &&
        isset($_POST['precio']) &&
        isset($_FILES['foto'])
    ) {
        
        $patente = $_POST['patente'];
        $marca = $_POST['marca'];
        $color = $_POST['color'];
        $precio = $_POST['precio'];
        $foto = $_FILES['foto'];
        
        require_once('./clases/AutoBD.php');
        
        $host = 'localhost';
        $dbname = 'garage_bd';
        $username = 'tu_usuario';
        $password = 'tu_contraseña';

        try {
            $conexion = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $autos = MendozaJavier\AutoBD::traer($conexion);

            
            $nuevoAuto = new MendozaJavier\AutoBD($patente, $marca, $color, $precio);
            
            if ($nuevoAuto->existe($autos)) {
                $respuesta = [
                    'exito' => false,
                    'mensaje' => 'El auto ya existe en la base de datos',
                ];
            } else {
                
                $nombreImagen = $patente . '.' . date('His') . '.' . pathinfo($foto['name'], PATHINFO_EXTENSION);
                $rutaImagen = './autos/imagenes/' . $nombreImagen;
                move_uploaded_file($foto['tmp_name'], $rutaImagen);
                
                $nuevoAuto->setFoto($rutaImagen);
                
                $exito = $nuevoAuto->agregar($conexion);

                $respuesta = [
                    'exito' => $exito,
                    'mensaje' => $exito ? 'Auto registrado correctamente' : 'Error al registrar el auto',
                ];
            }

            header('Content-Type: application/json');
            echo json_encode($respuesta);
        } catch (PDOException $e) {
            
            http_response_code(500);
            echo json_encode(['exito' => false, 'mensaje' => 'Error en la conexión a la base de datos']);
        }
    } else {
        
        http_response_code(400); 
        echo json_encode(['exito' => false, 'mensaje' => 'Faltan datos obligatorios']);
    }
} else {
    
    http_response_code(405); 
    echo json_encode(['exito' => false, 'mensaje' => 'Método no permitido']);
}
