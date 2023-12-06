<?php

if (isset($_GET['tabla']) && $_GET['tabla'] === 'mostrar') {
    
    mostrarTabla();
} else {
    
    retornarJSON();
}

function mostrarTabla() {
    
    require_once('./clases/AutoBD.php');
    
    $host = 'localhost';
    $dbname = 'garage_bd';
    $username = 'tu_usuario';
    $password = 'tu_contraseña';

    try {
        $conexion = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $autos = MendozaJavier\AutoBD::traer($conexion);

        
        echo '<table border="1">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Patente</th>';
        echo '<th>Marca</th>';
        echo '<th>Color</th>';
        echo '<th>Precio</th>';
        echo '<th>Foto</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        
        foreach ($autos as $auto) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($auto->getPatente()) . '</td>';
            echo '<td>' . htmlspecialchars($auto->getMarca()) . '</td>';
            echo '<td>' . htmlspecialchars($auto->getColor()) . '</td>';
            echo '<td>' . htmlspecialchars($auto->getPrecio()) . '</td>';
            echo '<td>';
            if ($auto->getFoto() !== null) {
                
                echo '<img src="' . htmlspecialchars($auto->getFoto()) . '" alt="Foto del auto" width="100">';
            }
            echo '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    } catch (PDOException $e) {
        
        echo 'Error en la conexión a la base de datos: ' . $e->getMessage();
    }
}

function retornarJSON() {
    
    require_once('./clases/AutoBD.php');
    
    $host = 'localhost';
    $dbname = 'garage_bd';
    $username = 'tu_usuario';
    $password = 'tu_contraseña';

    try {
        $conexion = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $autos = MendozaJavier\AutoBD::traer($conexion);
        
        header('Content-Type: application/json');
        echo json_encode($autos);
    } catch (PDOException $e) {
        
        http_response_code(500); 
        echo json_encode(['exito' => false, 'mensaje' => 'Error en la conexión a la base de datos']);
    }
}
