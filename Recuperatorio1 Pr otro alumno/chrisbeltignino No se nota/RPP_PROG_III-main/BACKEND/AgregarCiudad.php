<?php

require_once("./clases/Ciudad.php");
use Tignino_Christian\Ciudad;

// Obtener los valores recibidos por POST
$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : null;
$poblacion = isset($_POST['poblacion']) ? $_POST['poblacion'] : null;
$pais = isset($_POST['pais']) ? $_POST['pais'] : null;
$foto = isset($_FILES['foto']) ? $_FILES['foto'] : null;

$tipo = explode("/", $foto["type"]);
$tipo = $tipo[1];
$nombreFoto = $nombre . '.' . $pais . '.' . date("His") . '.' . $tipo;
$destino = "./ciudades/fotos/" . $nombreFoto;

if (file_exists($destino)) {
    $existe = false;
    $mensaje = "El archivo ya existe. ¡Verifica!";
} elseif ($_FILES["foto"]["size"] > 5000000000000) {
    $existe = false;
    $mensaje = "El archivo es demasiado grande. ¡Verifica!";
} else {
    $tipoArchivo = pathinfo($destino, PATHINFO_EXTENSION);
    if($tipoArchivo != "jpg" && $tipoArchivo != "jpeg" && $tipoArchivo != "gif" && $tipoArchivo != "png") {
        $existe = false;
        $mensaje = "Solo se permiten imágenes con extensiones JPG, JPEG, PNG o GIF.";
    } else {
        // Crear una instancia de la clase Ciudad con el último ID + 1
        $ultimasCiudades = Ciudad::traer();
        $ultimoId = 0;
        foreach ($ultimasCiudades as $ciudad) {
            if ($ciudad->id > $ultimoId) {
                $ultimoId = $ciudad->id;
            }
        }
        $nuevoId = $ultimoId + 1;

        $ciudad = new Ciudad($nuevoId, $nombre, $poblacion, $pais, $destino);
        $ciudades = Ciudad::traer();

        if ($ciudad->existe($ciudades)) {
            $existe = false;
            $mensaje = "La ciudad ya existe en la base de datos.";
        } else {
            if ($ciudad->agregar()) {
                move_uploaded_file($foto["tmp_name"], $destino);
                $existe = true;
                $mensaje = "Ciudad agregada correctamente.";
            } else {
                $existe = false;
                $mensaje = "No se pudo agregar la ciudad en la base de datos.";
            }
        }
    }
}

// Devolver un JSON con el resultado
echo json_encode(array("éxito" => $existe, "mensaje" => $mensaje));

?> 