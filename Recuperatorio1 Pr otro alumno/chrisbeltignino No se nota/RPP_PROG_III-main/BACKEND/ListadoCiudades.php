<?php

use Tignino_Christian\Ciudad;
require_once "./clases/Ciudad.php";

if(isset($_GET["tabla"]) && $_GET["tabla"] == "mostrar")
{
    $ciudades = Ciudad::traer();
    $tabla = "<table><tr><td>ID</td><td>NOMBRE</td><td>POBLACION</td><td>PAIS</td><td>FOTO</td></tr>";

    foreach($ciudades as $ciudad) {
        //$ciudad = json_decode($e->toJSON());
        $tabla .= "<tr><td>{$ciudad->id}</td><td>{$ciudad->nombre}</td><td>{$ciudad->poblacion}</td><td>{$ciudad->pais}</td><td><img src='{$ciudad->pathFoto}'></td></tr>";
    }

    $tabla .= "</table>";

    echo $tabla;

} else {
    $ciudades = Ciudad::traer();
    $arrRt = array();

    foreach($ciudades as $ciudad) {
        $stdRt = new stdClass;
        $stdRt->id = $ciudad->id;
        $stdRt->nombre = $ciudad->nombre;
        $stdRt->poblacion = $ciudad->poblacion;
        $stdRt->pais = $ciudad->pais;
        $stdRt->pathFoto = $ciudad->pathFoto;
        array_push($arrRt, $stdRt);
    }

    echo json_encode($arrRt);
}

?>