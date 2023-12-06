<?php
// Incluir la clase AutoBD.php y otras dependencias necesarias
require_once('./clases/AutoBD.php');

// Función para mostrar la tabla HTML con la información de todos los autos modificados
function mostrarAutosModificados()
{
    // Crear una conexión PDO a la base de datos garage_bd (debes configurar la conexión previamente)
    $host = 'localhost';
    $dbname = 'garage_bd';
    $username = 'tu_usuario';
    $password = 'tu_contraseña';

    try {
        $conexion = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Obtener los autos modificados desde la base de datos
        $autosModificados = MendozaJavier\AutoBD::traerModificados($conexion);

        // Mostrar la tabla HTML con la información de los autos modificados y sus imágenes
        echo '<table border="1">';
        echo '<tr><th>Patente</th><th>Marca</th><th>Color</th><th>Precio</th><th>Imagen</th></tr>';
        foreach ($autosModificados as $autoModificado) {
            echo '<tr>';
            echo '<td>' . $autoModificado->getPatente() . '</td>';
            echo '<td>' . $autoModificado->getMarca() . '</td>';
            echo '<td>' . $autoModificado->getColor() . '</td>';
            echo '<td>' . $autoModificado->getPrecio() . '</td>';
            echo '<td><img src="' . $autoModificado->getPathFoto() . '" alt="Imagen del auto"></td>';
            echo '</tr>';
        }
        echo '</table>';
    } catch (PDOException $e) {
        // Si hubo un error en la conexión a la base de datos, mostrar un mensaje de error
        echo 'Error en la conexión a la base de datos';
    }
}

// Verificar si la solicitud es GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    mostrarAutosModificados(); // Mostrar la tabla HTML con la información de los autos modificados
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si se recibieron los valores necesarios
    if (isset($_POST['auto_json']) && isset($_FILES['foto'])) {
        // Decodificar el JSON recibido
        $autoData = json_decode($_POST['auto_json'], true);

        // Verificar si se pudo decodificar el JSON correctamente y contiene la patente
        if ($autoData !== null && isset($autoData['patente'])) {
            // Crear una conexión PDO a la base de datos garage_bd (debes configurar la conexión previamente)
            $host = 'localhost';
            $dbname = 'garage_bd';
            $username = 'tu_usuario';
            $password = 'tu_contraseña';

            try {
                $conexion = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
                $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Crear un objeto AutoBD con la patente recibida
                $auto = new MendozaJavier\AutoBD(
                    $autoData['patente'],
                    $autoData['marca'] ?? '', // Puedes agregar manejo para los otros campos opcionales
                    $autoData['color'] ?? '',
                    $autoData['precio'] ?? 0
                );

                // Llamar al método modificar para actualizar el auto en la base de datos
                $exito = $auto->modificar($conexion);

                if ($exito) {
                    // Mover la foto original al subdirectorio ./autosModificados/ con el nombre especificado
                    $nombreFoto = $autoData['patente'] . '.modificado.' . date('His') . '.' . pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                    $rutaFoto = './autosModificados/' . $nombreFoto;
                    move_uploaded_file($_FILES['foto']['tmp_name'], $rutaFoto);
                }

                $respuesta = [
                    'exito' => $exito,
                    'mensaje' => $exito ? 'Auto modificado correctamente' : 'Error al modificar el auto',
                ];

                header('Content-Type: application/json');
                echo json_encode($respuesta);
            } catch (PDOException $e) {
                // Si hubo un error en la conexión a la base de datos, devolver un mensaje de error
                http_response_code(500); // Internal Server Error
                echo json_encode(['exito' => false, 'mensaje' => 'Error en la conexión a la base de datos']);
            }
        } else {
            // Si no se pudo decodificar el JSON correctamente o falta la patente, devolver un mensaje de error
            http_response_code(400); // Bad Request
            echo json_encode(['exito' => false, 'mensaje' => 'Formato JSON incorrecto o falta la patente']);
        }
    } else {
        // Si faltan valores necesarios en POST, devolver un mensaje de error
        http_response_code(400); // Bad Request
        echo json_encode(['exito' => false, 'mensaje' => 'Faltan datos obligatorios']);
    }
} else {
    // Si la solicitud no es POST ni GET, devolver un error
    http_response_code(405); // Method Not Allowed
    echo json_encode(['exito' => false, 'mensaje' => 'Método no permitido']);
}
