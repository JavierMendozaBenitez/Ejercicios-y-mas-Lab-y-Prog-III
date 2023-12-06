<?php

require_once "./clases/Ciudad.php";
use Tignino_Christian\Ciudad;

// Verificar si se recibe el parámetro nombre por GET
if (isset($_GET["nombre"])) {
    $nombre = $_GET["nombre"];
    $ciudad = new Ciudad(0, $nombre, 0, null, null);

    if ($ciudad->existe($ciudad)) {
        echo "La ciudad está en la base de datos.";
    } else {
        echo "La ciudad no está en la base de datos.";
    }
}
// Mostrar todas las ciudades borradas
else if (empty($_GET)) {
    $file = file_get_contents("clases/archivos/ciudades_borradas.txt");
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Nombre</th><th>Población</th><th>País</th><th>PathFoto</th></tr>";
    $ciudades = explode("\n", $file);
    foreach ($ciudades as $c) {
        $data = explode(".", $c);
        echo "<tr>";
        foreach ($data as $d) {
            echo "<td>$d</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}
// Verificar si se recibe el parámetro ciudad_json por POST
else if (isset($_POST["ciudad_json"]) && isset($_POST["accion"]) && $_POST["accion"] === "borrar") {
    $ciudad_json = $_POST["ciudad_json"];
    $lectura = json_decode($ciudad_json, true);

    $ciudad = new Ciudad($lectura["id"], $lectura["nombre"], $lectura["poblacion"], $lectura["pais"], $lectura["pathFoto"]);

    if ($ciudad->eliminar()) {
        echo json_encode(array("exito" => true, "mensaje" => "Se pudo borrar."));
        $ciudad->guardarEnArchivo($ciudad); // Ajusta el path en este método según tus necesidades
    } else {
        echo json_encode(array("exito" => false, "mensaje" => "No se pudo borrar."));
    }
} else {
    // Mostrar mensaje alusivo si no se cumplen las condiciones anteriores
    echo "Ingrese los parámetros adecuados.";
}


?>